<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
