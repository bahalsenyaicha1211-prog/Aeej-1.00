<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriePhoto;
use App\Services\CloudinaryUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalerieController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $query = GaleriePhoto::query()->orderByDesc('event_date')->orderByDesc('id');

        if ($request->filled('category')) {
            $query->where('category', (string) $request->input('category'));

        }

        if ($request->filled('status')) {
            if ($request->status === 'published') $query->where('is_published', true);
            if ($request->status === 'draft')     $query->where('is_published', false);
        }

        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $photos = $query->paginate(18)->withQueryString();

        $categories = GaleriePhoto::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.galerie.index', ['photos' => $photos, 'categories' => $categories, 'q' => $search]);
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
    $uploader = app(CloudinaryUploader::class);
    foreach ($request->file('images') as $img) {
    $path = $uploader->upload($img, 'galerie', time() . '-' . uniqid());

    if ($path === null) {
        return back()->withErrors("Échec de l'envoi d'une image vers Cloudinary.");
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
         $url = app(CloudinaryUploader::class)->upload($request->file('image'), 'galerie', time() . '-' . uniqid());
         if ($url === null) {
             return back()->withErrors(['image' => "Échec de l'envoi de l'image vers Cloudinary."]);
         }
         $data['image_path'] = $url;
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
