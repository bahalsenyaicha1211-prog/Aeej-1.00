<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Http;




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
        $img = $request->file('photo');
        $cloudName = "dg9lez6mx"; 

        $response = Http::asMultipart()->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
            'file'          => fopen($img->getRealPath(), 'r'),
            'upload_preset' => 'ml_default', 
            'folder'        => 'avatars',
        ]);

        if ($response->successful()) {
            $user->update(['profile_photo_path' => $response->json()['secure_url']]);
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
