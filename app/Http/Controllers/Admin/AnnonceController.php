<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\User;
use App\Notifications\NewAnnoncePublished;
use App\Models\BureauMembre;
use App\Models\GaleriePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $annonces = Annonce::when($q !== '', fn ($query) => $query->where('contenu', 'like', "%{$q}%"))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.annonces.index', compact('annonces', 'q'));
    }

    public function create()
{
    $bureau = BureauMembre::orderBy('ordre')->get();

    return view('admin.annonces.create', compact('bureau'));
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'contenu'      => ['required', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
            'is_published' => ['nullable'],
            'is_pinned'    => ['nullable'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['is_pinned']    = $request->boolean('is_pinned');
        $data['created_by']   = auth()->id();
        $data['published_at'] = $data['is_published'] ? now() : null;

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->uploadImageCloudinary($request->file('image'));
        }

        $annonce = Annonce::create($data);

        // ✅ Notifier seulement si publiée
        if ($annonce->is_published) {
            User::query()
                ->where('is_admin', false)
                ->whereNotNull('email_verified_at')
                ->chunkById(500, function ($users) use ($annonce) {
                    Notification::send($users, new NewAnnoncePublished($annonce));
                });
        }

        return redirect()->route('admin.annonces.index')->with('success', 'Annonce créée.');
    }


public function edit(Annonce $annonce)
{
    $bureau = BureauMembre::orderBy('ordre')->get();

    return view('admin.annonces.edit', compact('annonce', 'bureau'));
}


    public function update(Request $request, Annonce $annonce)
    {
        $data = $request->validate([
            'contenu'      => ['required', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
            'remove_image' => ['nullable'],
            'is_published' => ['nullable'],
            'is_pinned'    => ['nullable'],
        ]);

        $wasPublished = (bool) $annonce->is_published;

        $data['is_published'] = $request->boolean('is_published');
        $data['is_pinned']    = $request->boolean('is_pinned');

        // published_at : première publication ou dépublication
        if ($data['is_published'] && !$annonce->published_at) {
            $data['published_at'] = now();
        }
        if (!$data['is_published']) {
            $data['published_at'] = null;
        }

        // Retirer l'image
        if ($request->boolean('remove_image') && $annonce->image_path) {
            $this->supprimerImageLocaleSiApplicable($annonce->image_path);
            $data['image_path'] = null;
        }

        // Remplacer / ajouter image
        if ($request->hasFile('image')) {
            if ($annonce->image_path) {
                $this->supprimerImageLocaleSiApplicable($annonce->image_path);
            }
            $data['image_path'] = $this->uploadImageCloudinary($request->file('image'));
        }

        $annonce->update($data);
        $annonce->refresh(); // ✅ important

        // ✅ Transition NON -> OUI (publication)
        if (!$wasPublished && $annonce->is_published) {
            User::query()
                ->where('is_admin', false)
                ->whereNotNull('email_verified_at')
                ->chunkById(500, function ($users) use ($annonce) {
                    Notification::send($users, new NewAnnoncePublished($annonce));
                });
        }

        return redirect()->route('admin.annonces.index')->with('success', 'Annonce mise à jour.');
    }

    public function galerie(Request $request)
{
    $q = GaleriePhoto::published()
        ->orderByDesc('event_date')
        ->orderByDesc('id');

    $categories = GaleriePhoto::published()
        ->select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    if ($request->filled('category')) {
        $q->where('category', $request->string('category'));
    }

    $photos = $q->paginate(24)->withQueryString();

    return view('galerie', compact('photos', 'categories'));
}


    public function destroy(Annonce $annonce)
    {
        if ($annonce->image_path) {
            $this->supprimerImageLocaleSiApplicable($annonce->image_path);
        }

        $annonce->delete();
        return back()->with('success', 'Annonce supprimée.');
    }

    /**
     * Upload vers Cloudinary (mêmes identifiants que GalerieController/BureauMembreController).
     * Retourne l'URL sécurisée à stocker dans image_path.
     */
    private function uploadImageCloudinary($file): string
    {
        $tempName = 'annonce-' . time() . '-' . uniqid();

        $response = Http::asMultipart()->post('https://api.cloudinary.com/v1_1/dg9lez6mx/image/upload', [
            'file'          => fopen($file->getRealPath(), 'r'),
            'upload_preset' => 'ml_default',
            'public_id'     => $tempName,
            'folder'        => 'annonces',
        ]);

        if (!$response->successful()) {
            abort(back()->withErrors(['image' => "Échec de l'envoi de l'image : " . $response->body()]));
        }

        return $response->json()['secure_url'];
    }

    /**
     * Anciennes annonces : image_path pouvait pointer vers le disque local.
     * Les nouvelles images (Cloudinary) ne doivent jamais passer par ici.
     */
    private function supprimerImageLocaleSiApplicable(string $imagePath): void
    {
        if (!str_starts_with($imagePath, 'http')) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
