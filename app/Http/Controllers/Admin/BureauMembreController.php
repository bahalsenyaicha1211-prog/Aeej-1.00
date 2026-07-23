<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BureauMembre;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class BureauMembreController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $bureau = BureauMembre::with('membre')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('matricule', 'like', "%{$q}%")
                        ->orWhere('poste', 'like', "%{$q}%")
                        ->orWhereHas('membre', function ($m) use ($q) {
                            $m->where('nom', 'like', "%{$q}%")
                              ->orWhere('prenom', 'like', "%{$q}%")
                              ->orWhereRaw("CONCAT(prenom, ' ', nom) like ?", ["%{$q}%"])
                              ->orWhereRaw("CONCAT(nom, ' ', prenom) like ?", ["%{$q}%"]);
                        });
                });
            })
            ->orderByDesc('is_actif')
            ->orderBy('ordre')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bureau.index', compact('bureau', 'q'));
    }

    public function create()
    {
        $membres = Membre::orderBy('prenom')->orderBy('nom')
            ->get(['matricule','nom','prenom','email']);

        return view('admin.bureau.create', compact('membres'));
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'matricule' => ['required', 'exists:membres,matricule'],
        'poste'     => ['required', 'string', 'max:120'],
        'ordre'     => ['nullable', 'integer', 'min:0', 'max:9999'],
        'is_actif'  => ['nullable', 'boolean'],
        'photo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    $data = [
        'matricule' => $validated['matricule'],
        'poste'     => $validated['poste'],
        'ordre'     => $validated['ordre'] ?? 0,
        'is_actif'  => (bool) $request->boolean('is_actif'),
    ];

    if ($request->hasFile('photo')) {
        $img = $request->file('photo');
        $tempName = 'bureau-' . time() . '-' . uniqid();

        $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/dg9lez6mx/image/upload", [
            'file'          => fopen($img->getRealPath(), 'r'),
            'upload_preset' => 'ml_default',
            'public_id'     => $tempName,
            'folder'        => 'bureau',
        ]);

        if ($response->successful()) {
            $data['photo'] = $response->json()['secure_url'];
        }
    }

    BureauMembre::create($data);

    return redirect()->route('admin.bureau.index')->with('success', 'Membre du bureau ajouté.');
}

    public function edit(BureauMembre $bureau)
    {
        $membres = Membre::orderBy('prenom')->orderBy('nom')
            ->get(['matricule','nom','prenom','email']);

        return view('admin.bureau.edit', compact('bureau', 'membres'));
    }

   public function update(Request $request, BureauMembre $bureau)
{
    $validated = $request->validate([
        'matricule' => ['required', 'exists:membres,matricule'],
        'poste'     => ['required', 'string', 'max:120'],
        'ordre'     => ['nullable', 'integer', 'min:0', 'max:9999'],
        'is_actif'  => ['nullable', 'boolean'],
        'photo'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'remove_photo' => ['nullable', 'boolean'],
    ]);

    $data = [
        'matricule' => $validated['matricule'],
        'poste'     => $validated['poste'],
        'ordre'     => $validated['ordre'] ?? 0,
        'is_actif'  => (bool) $request->boolean('is_actif'),
    ];

    if ($request->boolean('remove_photo')) {
        $data['photo'] = null;
    }

    if ($request->hasFile('photo')) {
        $img = $request->file('photo');
        $tempName = 'bureau-' . time() . '-' . uniqid();

        $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/dg9lez6mx/image/upload", [
            'file'          => fopen($img->getRealPath(), 'r'),
            'upload_preset' => 'ml_default',
            'public_id'     => $tempName,
            'folder'        => 'bureau',
        ]);

        if ($response->successful()) {
            $data['photo'] = $response->json()['secure_url'];
        }
    }

    $bureau->update($data);

    return redirect()->route('admin.bureau.index')->with('success', 'Membre du bureau mis à jour.');
    }

    public function destroy(BureauMembre $bureau)
    {
        if ($bureau->photo && !str_starts_with($bureau->photo, 'http')) {
            Storage::disk('public')->delete($bureau->photo);
        }

        $bureau->delete();

        return redirect()
            ->route('admin.bureau.index')
            ->with('success', 'Membre du bureau supprimé.');
    }
}
