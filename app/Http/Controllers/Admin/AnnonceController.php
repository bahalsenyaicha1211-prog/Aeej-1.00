<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\User;
use App\Notifications\NewAnnoncePublished;
use App\Models\BureauMembre;
use App\Models\GaleriePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::orderByDesc('created_at')->paginate(15);
        return view('admin.annonces.index', compact('annonces'));
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
            $data['image_path'] = $request->file('image')->store('annonces', 'public');
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
            Storage::disk('public')->delete($annonce->image_path);
            $data['image_path'] = null;
        }

        // Remplacer / ajouter image
        if ($request->hasFile('image')) {
            if ($annonce->image_path) {
                Storage::disk('public')->delete($annonce->image_path);
            }
            $data['image_path'] = $request->file('image')->store('annonces', 'public');
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
            Storage::disk('public')->delete($annonce->image_path);
        }

        $annonce->delete();
        return back()->with('success', 'Annonce supprimée.');
    }
}
