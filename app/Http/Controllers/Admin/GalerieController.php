<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class GalerieController extends Controller
{
    public function index(Request $request)
    {
        $q = GaleriePhoto::query()->orderByDesc('event_date')->orderByDesc('id');

        if ($request->filled('category')) {
            $q->where('category', (string) $request->input('category'));

        }

        if ($request->filled('status')) {
            if ($request->status === 'published') $q->where('is_published', true);
            if ($request->status === 'draft')     $q->where('is_published', false);
        }

        $photos = $q->paginate(18)->withQueryString();

        $categories = GaleriePhoto::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.galerie.index', compact('photos', 'categories'));
    }

    public function create()
    {
        return view('admin.galerie.create');
    }

    /**
     * Upload multiple
     * input name: images[]
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'category'     => ['required', 'string', 'max:80'],
        'event_date'   => ['required', 'date'],
        'title'        => ['nullable', 'string', 'max:180'],
        'description'  => ['nullable', 'string', 'max:2000'],
        'is_published' => ['nullable'],
        'images'       => ['required', 'array', 'min:1'],
        'images.*'     => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
    ]);

    $rows = [];
    foreach ($request->file('images') as $img) {
    $tempName = time() . '-' . uniqid();

    // On utilise asMultipart() pour un envoi de fichier standard, plus fiable
    $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/dg9lez6mx/image/upload", [
        'file'          => fopen($img->getRealPath(), 'r'),
        'upload_preset' => 'ml_default',
        'public_id'     => $tempName,
        'folder'        => 'galerie', // On remet le dossier ici, SANS slash
    ]);

    if ($response->successful()) {
        $path = $response->json()['secure_url'];
    } else {
        // Affiche l'erreur complète pour comprendre si c'est encore le slash
        return back()->withErrors('Détails Erreur : ' . $response->body());
    }

        $rows[] = [
            'title'        => $data['title'] ?? null,
            'category'     => $data['category'],
            'event_date'   => $data['event_date'],
            'description'  => $data['description'] ?? null,
            'image_path'   => $path,
            'is_published' => $request->boolean('is_published'),
            'created_by'   => auth()->id(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ];
    }

    GaleriePhoto::insert($rows);
    return redirect()->route('admin.galerie.index')->with('success', 'Photos ajoutées.');
}

    public function edit(GaleriePhoto $photo)
    {
        return view('admin.galerie.edit', compact('photo'));
    }

    public function update(Request $request, GaleriePhoto $photo)
    {
        $data = $request->validate([
            'category'     => ['required', 'string', 'max:80'],
            'event_date'   => ['required', 'date'],
            'title'        => ['nullable', 'string', 'max:180'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'is_published' => ['nullable'],

            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['nullable'],
        ]);

        $data['is_published'] = $request->boolean('is_published');

        if ($request->boolean('remove_image') && $photo->image_path) {
            if (!str_starts_with($photo->image_path, 'http')) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $data['image_path'] = '';
        }

        if ($request->hasFile('image')) {
         $upload =Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder'=> 'galerie'
             ]);
         $data['image_path'] = $upload->getSecurePath();
    } else {
    // Crucial : si on ne télécharge pas de nouvelle image, 
    // on retire image_path des données à mettre à jour 
    // pour garder l'ancienne URL en base de données.
    unset($data['image_path']);
    }

        $photo->update($data);

        return redirect()
            ->route('admin.galerie.index')
            ->with('success', 'Photo mise à jour.');
    }

    public function destroy(GaleriePhoto $photo) {
    // On supprime juste la ligne en base de données. 
    // L'image restera sur ton compte Cloudinary (ce qui est une sécurité).
    $photo->delete();

    return back()->with('success', 'Photo supprimée.');
}

    public function toggle(GaleriePhoto $photo)
    {
        $photo->update(['is_published' => !$photo->is_published]);

        return back()->with('success', 'Statut de publication mis à jour.');
    }
}
