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

use Illuminate\Support\Facades\Http;

public function updatePhoto(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    if ($request->hasFile('photo')) {
        $img = $request->file('photo');
        $tempName = 'avatar-' . $user->id . '-' . time();

        // On utilise la même méthode HTTP que dans ta Galerie (plus fiable sur Render)
        $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/dg9lez6mx/image/upload", [
            'file'          => fopen($img->getRealPath(), 'r'),
            'upload_preset' => 'ml_default', // Assure-toi que ce preset est correct
            'public_id'     => $tempName,
            'folder'        => 'avatars',
        ]);

        if ($response->successful()) {
            $path = $response->json()['secure_url'];
            $user->update(['profile_photo_path' => $path]);
            return back()->with('success', 'Photo de profil mise à jour !');
        }

        return back()->withErrors('Erreur Cloudinary : ' . $response->body());
    }

    return back()->with('error', 'Aucune photo sélectionnée.');
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
