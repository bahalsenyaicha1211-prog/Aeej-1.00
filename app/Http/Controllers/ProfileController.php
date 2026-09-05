<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;




class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', [
            'user'   => $user,
            'membre' => $user->membre()->with(['departement', 'pays'])->first(),
            'unreadAnnoncesCount' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mise à jour des coordonnées du membre (téléphone / adresse).
     */
    public function updateCoordonnees(Request $request)
    {
        $user = Auth::user();
        $membre = $user->membre;

        if (! $membre) {
            return back()->with('error', "Aucune fiche membre n'est associée à ce compte.");
        }

        $validated = $request->validateWithBag('updateCoordonnees', [
            'telephone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+()\s.\-]{6,20}$/'],
            'adresse'   => ['nullable', 'string', 'max:255'],
        ], [
            'telephone.regex' => "Le numéro de téléphone n'est pas valide.",
        ]);

        $membre->update($validated);

        return back()->with('success', 'Vos coordonnées ont été mises à jour.');
    }

    /**
     * Mise à jour infos texte (name/email) - sans photo ici
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validateWithBag('updateProfile', [
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Votre nom a été mis à jour.');
    }

    /**
     * Mise à jour de la photo uniquement
     */


public function updatePhoto(Request $request)
{
    $user = Auth::user();

    // Si on demande la suppression
    if ($request->has('remove_photo')) {
        $user->update(['profile_photo_path' => null]);
        return back()->with('success', 'Photo supprimée.');
    }

    // Si on télécharge une photo
    $request->validate([
        'photo' => ['required', 'image', 'max:2048'],
    ]);

    if ($request->hasFile('photo')) {
        $url = app(CloudinaryUploader::class)->upload($request->file('photo'), 'avatars');

        if ($url !== null) {
            $user->update(['profile_photo_path' => $url]);
            return back()->with('success', 'Photo mise à jour !');
        }
    }

    return back()->with('error', 'Échec de l\'envoi.');
}
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = Auth::user();

        if ($user->profile_photo_path && !str_starts_with($user->profile_photo_path, 'http')) {
    Storage::disk('public')->delete($user->profile_photo_path);
    }
    $user->update(['profile_photo_path' => null]);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
