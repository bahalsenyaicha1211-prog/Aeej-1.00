<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Catégorie de partenaire (ex. Entreprise, Association), créée
 * librement par l'admin depuis la page « Partenaires » — pas une liste
 * figée. Le "slug" (URL-friendly) se recalcule tout seul à chaque
 * sauvegarde à partir du nom (voir booted() ci-dessous), pas besoin de
 * le gérer à la main.
 */
class PartnerCategory extends Model
{
    protected $fillable = ['nom', 'slug'];

    protected static function booted(): void
    {
        static::saving(function (self $categorie) {
            if (empty($categorie->slug) || $categorie->isDirty('nom')) {
                $categorie->slug = Str::slug($categorie->nom);
            }
        });
    }

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }
}
