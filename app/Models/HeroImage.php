<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Une photo du diaporama de la page d'accueil (bandeau du haut).
 * position = ordre d'affichage ; is_active = visible ou non sans
 * supprimer la ligne (garde l'historique/permet de la réactiver).
 */
class HeroImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'alt',
        'position',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position'  => 'integer',
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * URL affichable : URL Cloudinary telle quelle, sinon chemin public
     * (images/imgN.jpeg pour les photos importées, storage/... pour un
     * éventuel upload local).
     */
    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'images/')) {
            return asset($this->image_path);
        }

        return asset('storage/' . $this->image_path);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
