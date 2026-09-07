<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use App\Services\CloudinaryUploader;
use Illuminate\Http\Request;

class HeroImageController extends Controller
{
    public function index()
    {
        $images = HeroImage::query()
            ->orderBy('position')
            ->orderByDesc('id')
            ->paginate(24);

        return view('admin.hero-images.index', compact('images'));
    }

    public function create()
    {
        return view('admin.hero-images.create');
    }

    /**
     * Upload multiple — input name : images[]
     */
    public function store(Request $request)
    {
        // Chemin normal : le navigateur a déjà envoyé les images à Cloudinary
        // (public/js/bulk-upload.js) et ne transmet que les URLs.
        if ($request->filled('image_urls')) {
            $data = $request->validate([
                'alt'          => ['nullable', 'string', 'max:180'],
                'is_active'    => ['nullable'],
                'image_urls'   => ['required', 'array', 'max:500'],
                'image_urls.*' => ['required', 'string', 'max:500', function ($attr, $value, $fail) {
                    if (! str_starts_with($value, 'https://res.cloudinary.com/')) {
                        $fail('URL d\'image invalide.');
                    }
                }],
            ]);

            $urls = $data['image_urls'];
        } else {
            // Fallback (JS désactivé) : envoi serveur séquentiel.
            $data = $request->validate([
                'alt'       => ['nullable', 'string', 'max:180'],
                'is_active' => ['nullable'],
                'images'    => ['required', 'array', 'min:1'],
                'images.*'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            $uploader = app(CloudinaryUploader::class);
            $urls = [];
            foreach ($request->file('images') as $img) {
                $url = $uploader->upload($img, 'accueil', time() . '-' . uniqid());
                if ($url === null) {
                    return back()->withErrors("Échec de l'envoi d'une image vers Cloudinary.");
                }
                $urls[] = $url;
            }
        }

        $position = (int) (HeroImage::max('position') ?? 0);
        $isActive = $request->boolean('is_active');
        $now = now();

        $rows = array_map(function ($url) use (&$position, $data, $isActive, $now) {
            $position++;
            return [
                'image_path' => $url,
                'alt'        => $data['alt'] ?? null,
                'position'   => $position,
                'is_active'  => $isActive,
                'created_by' => auth()->id(),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $urls);

        foreach (array_chunk($rows, 50) as $chunk) {
            HeroImage::insert($chunk);
        }

        return redirect()->route('admin.hero-images.index')
            ->with('success', count($rows) . ' photo(s) ajoutée(s) au diaporama.');
    }

    public function edit(HeroImage $heroImage)
    {
        return view('admin.hero-images.edit', ['image' => $heroImage]);
    }

    public function update(Request $request, HeroImage $heroImage)
    {
        $data = $request->validate([
            'alt'       => ['nullable', 'string', 'max:180'],
            'position'  => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $url = app(CloudinaryUploader::class)->upload($request->file('image'), 'accueil', time() . '-' . uniqid());

            if ($url === null) {
                return back()->withErrors(['image' => "Échec de l'envoi de l'image vers Cloudinary."]);
            }

            $data['image_path'] = $url;
        } else {
            unset($data['image_path']);
        }

        $heroImage->update($data);

        return redirect()->route('admin.hero-images.index')->with('success', 'Photo mise à jour.');
    }

    public function destroy(HeroImage $heroImage)
    {
        // On supprime uniquement la ligne : l'image reste sur Cloudinary (garde-fou),
        // comme pour la galerie.
        $heroImage->delete();

        return back()->with('success', 'Photo retirée du diaporama.');
    }

    public function toggle(HeroImage $heroImage)
    {
        $heroImage->update(['is_active' => ! $heroImage->is_active]);

        return back()->with('success', 'Visibilité de la photo mise à jour.');
    }
}
