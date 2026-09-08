<?php

namespace App\Http\Controllers\Concerns;

use App\Services\CloudinaryUploader;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Résout une image de formulaire, quel que soit le chemin utilisé par le
 * composant <x-image-upload> :
 *  - `{name}_url` : le navigateur a déjà envoyé l'image à Cloudinary → on garde l'URL ;
 *  - `{name}` (fichier) : fallback → envoi serveur via CloudinaryUploader ;
 *  - rien : en création → erreur si requis, en édition → null (on conserve l'existant).
 */
trait HandlesImageUpload
{
    protected function resolveImageUrl(Request $request, string $name, string $folder, bool $required): ?string
    {
        $urlField = $name . '_url';

        if ($request->filled($urlField)) {
            $request->validate([
                $urlField => ['string', 'max:600', function ($attr, $value, $fail) {
                    if (! str_starts_with($value, 'https://res.cloudinary.com/')) {
                        $fail("L'image envoyée est invalide.");
                    }
                }],
            ]);

            return $request->input($urlField);
        }

        if ($request->hasFile($name)) {
            $request->validate([
                $name => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            $url = app(CloudinaryUploader::class)->upload(
                $request->file($name),
                $folder,
                time() . '-' . uniqid()
            );

            if ($url === null) {
                throw ValidationException::withMessages([
                    $name => "Échec de l'envoi de l'image. Réessayez.",
                ]);
            }

            return $url;
        }

        if ($required) {
            throw ValidationException::withMessages([
                $name => 'Une image est requise.',
            ]);
        }

        return null;
    }
}
