<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Un partenaire affiché sur la page publique « Nos partenaires »
 * (logo, description, lien vers son site). Classé par catégorie
 * (voir PartnerCategory) ; is_published = visible publiquement ou non.
 */
class Partner extends Model
{
    protected $fillable = [
        'nom',
        'logo_path',
        'description',
        'url',
        'partner_category_id',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function categorie()
    {
        return $this->belongsTo(PartnerCategory::class, 'partner_category_id');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * URL affichable du logo : URL Cloudinary telle quelle, sinon chemin public.
     */
    public function getLogoUrlAttribute(): string
    {
        if (str_starts_with($this->logo_path, 'http')) {
            return $this->logo_path;
        }

        return asset('storage/' . ltrim($this->logo_path, '/'));
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }
}
