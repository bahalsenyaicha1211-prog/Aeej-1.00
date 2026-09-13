<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une personne à contacter affichée sur la page publique « Contact »
 * (président, secrétaire général...), gérée depuis l'admin. À ne pas
 * confondre avec BureauMembre (poste au bureau) : celle-ci ne sert que
 * pour la carte de contact public (photo, téléphone, email affichés).
 * is_highlighted = mise en avant visuelle (bordure/badge doré) ;
 * position = ordre d'affichage des cartes (croissant).
 */
class ContactPerson extends Model
{
    protected $fillable = [
        'nom',
        'poste',
        'telephone',
        'email',
        'photo',
        'is_highlighted',
        'position',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'is_highlighted' => 'boolean',
        'is_published' => 'boolean',
        'position' => 'integer',
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * URL affichable de la photo : URL Cloudinary telle quelle, chemin
     * public "images/..." legacy (données reprises depuis l'ancien code
     * en dur), sinon fichier du disque "public" (storage:link).
     */
    public function getPhotoUrlAttribute(): string
    {
        if (str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }

        if (str_starts_with($this->photo, 'images/')) {
            return asset($this->photo);
        }

        return asset('storage/' . ltrim($this->photo, '/'));
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }
}
