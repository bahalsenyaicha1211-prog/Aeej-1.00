<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Mise à jour infos texte (name/email) - sans photo ici
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Mise à jour de la photo uniquement
     */

public function updatePhoto(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    // Action : Retirer la photo
    if ($request->has('remove_photo')) {
        // Note : On laisse généralement l'image sur Cloudinary par sécurité, 
        // ou on la supprime via l'API si nécessaire. Ici on nettoie juste la base.
        $user->update(['profile_photo_path' => null]);
        return back()->with('success', 'Photo supprimée.');
    }

    // Action : Upload sur Cloudinary
    if ($request->hasFile('photo')) {
        // Envoi vers Cloudinary dans un dossier "avatars"
        $upload = Cloudinary::upload($request->file('photo')->getRealPath(), [
            'folder' => 'avatars'
        ]);

        // On récupère l'URL sécurisée (https) fournie par Cloudinary
        $path = $upload->getSecurePath();

        // On enregistre l'URL complète en base de données
        $user->update(['profile_photo_path' => $path]);

        return back()->with('success', 'Photo de profil mise à jour sur le cloud !');
    }

    return back()->with('error', 'Aucune photo envoyée.');
}
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = Auth::user();

        // Supprimer la photo si elle existe
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
