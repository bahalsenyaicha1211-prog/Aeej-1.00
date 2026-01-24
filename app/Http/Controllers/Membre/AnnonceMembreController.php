<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Notifications\AnnoncePublished;

class AnnonceMembreController extends Controller
{
    public function index()
    {
        $annonces = Annonce::query()
            ->where('is_published', true)
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('membre.annonces.index', compact('annonces'));
    }

    public function show(Annonce $annonce)
    {
        abort_if(!$annonce->is_published, 404);

        // ✅ Marquer comme lue la notification liée à cette annonce
        $user = auth()->user();

        $user->unreadNotifications()
            ->where('type', AnnoncePublished::class)
            ->where('data->annonce_id', $annonce->id)
            ->update(['read_at' => now()]);

        return view('membre.annonces.show', compact('annonce'));
    }
}
