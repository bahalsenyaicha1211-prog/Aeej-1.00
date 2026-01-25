<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaleriePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $isPublished = $request->boolean('is_published');

        $rows = [];
        foreach ($request->file('images') as $img) {
            $path = $img->store('galerie', 'public');

            $rows[] = [
                'title'        => $data['title'] ?? null,
                'category'     => $data['category'],
                'event_date'   => $data['event_date'],
                'description'  => $data['description'] ?? null,
                'image_path'   => $path,
                'is_published' => $isPublished,
                'created_by'   => auth()->id(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        GaleriePhoto::insert($rows);

        return redirect()
            ->route('admin.galerie.index')
            ->with('success', 'Photos ajoutées à la galerie.');
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
            Storage::disk('public')->delete($photo->image_path);
            $data['image_path'] = '';
        }

        if ($request->hasFile('image')) {
            if ($photo->image_path) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $data['image_path'] = $request->file('image')->store('galerie', 'public');
        } else {
            unset($data['image_path']);
        }

        $photo->update($data);

        return redirect()
            ->route('admin.galerie.index')
            ->with('success', 'Photo mise à jour.');
    }

    public function destroy(GaleriePhoto $photo)
    {
        if ($photo->image_path) {
            Storage::disk('public')->delete($photo->image_path);
        }
        $photo->delete();

        return back()->with('success', 'Photo supprimée.');
    }

    public function toggle(GaleriePhoto $photo)
    {
        $photo->update(['is_published' => !$photo->is_published]);

        return back()->with('success', 'Statut de publication mis à jour.');
    }
}
