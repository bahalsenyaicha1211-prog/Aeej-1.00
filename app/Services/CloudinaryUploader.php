<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

/**
 * Point d'entrée unique pour les envois d'images vers Cloudinary.
 *
 * Centralise le cloud name et le preset d'upload (auparavant codés en dur
 * dans 4 contrôleurs). Pour passer un jour à un preset SIGNÉ, seule cette
 * classe est à modifier.
 */
class CloudinaryUploader
{
    private string $cloudName;
    private string $uploadPreset;

    public function __construct(?string $cloudName = null, ?string $uploadPreset = null)
    {
        $this->cloudName    = $cloudName    ?? (string) config('services.cloudinary.cloud_name');
        $this->uploadPreset = $uploadPreset ?? (string) config('services.cloudinary.upload_preset');
    }

    /**
     * Envoie une image et renvoie l'URL sécurisée, ou null en cas d'échec.
     */
    public function upload(UploadedFile $file, string $folder, ?string $publicId = null): ?string
    {
        $payload = [
            'file'          => fopen($file->getRealPath(), 'r'),
            'upload_preset' => $this->uploadPreset,
            'folder'        => $folder,
        ];

        if ($publicId !== null) {
            $payload['public_id'] = $publicId;
        }

        $response = Http::asMultipart()
            ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", $payload);

        if (! $response->successful()) {
            return null;
        }

        $url = $response->json('secure_url');

        return is_string($url) && $url !== '' ? $url : null;
    }
}
