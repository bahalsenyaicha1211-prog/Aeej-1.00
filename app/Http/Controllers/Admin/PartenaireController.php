<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Services\CloudinaryUploader;
use Illuminate\Http\Request;

class PartenaireController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $slug = trim((string) $request->query('categorie', ''));

        $partenaires = Partner::query()
            ->with('categorie')
            ->when($q !== '', fn ($query) => $query->where('nom', 'like', "%{$q}%"))
            ->when($slug === 'non-classe', fn ($query) => $query->whereNull('partner_category_id'))
            ->when($slug !== '' && $slug !== 'non-classe', fn ($query) => $query->whereHas('categorie', fn ($c) => $c->where('slug', $slug)))
            ->orderBy('nom')
            ->paginate(24)
            ->withQueryString();

        $categories = PartnerCategory::orderBy('nom')->withCount('partners')->get();

        return view('admin.partenaires.index', compact('partenaires', 'categories', 'q', 'slug'));
    }

    public function create()
    {
        return view('admin.partenaires.create', [
            'categories' => PartnerCategory::orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, logoRequired: true);

        $url = app(CloudinaryUploader::class)->upload($request->file('logo'), 'partenaires', time() . '-' . uniqid());
        if ($url === null) {
            return back()->withInput()->withErrors(['logo' => "Échec de l'envoi du logo vers Cloudinary."]);
        }

        Partner::create([
            'nom'                 => $data['nom'],
            'partner_category_id' => $data['partner_category_id'] ?? null,
            'url'                 => $data['url'] ?? null,
            'description'         => $data['description'],
            'logo_path'           => $url,
            'is_published'        => $request->boolean('is_published'),
            'created_by'          => auth()->id(),
        ]);

        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire ajouté.');
    }

    public function edit(Partner $partenaire)
    {
        return view('admin.partenaires.edit', [
            'partenaire' => $partenaire,
            'categories' => PartnerCategory::orderBy('nom')->get(),
        ]);
    }

    public function update(Request $request, Partner $partenaire)
    {
        $data = $this->validated($request, logoRequired: false);

        $payload = [
            'nom'                 => $data['nom'],
            'partner_category_id' => $data['partner_category_id'] ?? null,
            'url'                 => $data['url'] ?? null,
            'description'         => $data['description'],
            'is_published'        => $request->boolean('is_published'),
        ];

        if ($request->hasFile('logo')) {
            $url = app(CloudinaryUploader::class)->upload($request->file('logo'), 'partenaires', time() . '-' . uniqid());
            if ($url === null) {
                return back()->withInput()->withErrors(['logo' => "Échec de l'envoi du logo vers Cloudinary."]);
            }
            $payload['logo_path'] = $url;
        }

        $partenaire->update($payload);

        return redirect()->route('admin.partenaires.index')->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(Partner $partenaire)
    {
        // On supprime la ligne ; le logo reste sur Cloudinary (cf. galerie).
        $partenaire->delete();

        return back()->with('success', 'Partenaire supprimé.');
    }

    public function toggle(Partner $partenaire)
    {
        $partenaire->update(['is_published' => ! $partenaire->is_published]);

        return back()->with('success', 'Visibilité du partenaire mise à jour.');
    }

    private function validated(Request $request, bool $logoRequired): array
    {
        return $request->validate([
            'nom'                 => ['required', 'string', 'max:150'],
            'partner_category_id' => ['nullable', 'exists:partner_categories,id'],
            'url'                 => ['nullable', 'url', 'max:255'],
            'description'         => ['required', 'string', 'max:5000'],
            'is_published'        => ['nullable'],
            'logo'                => [$logoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
