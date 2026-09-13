<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Annonce publiée par un admin, visible dans l'espace membre. Une
 * annonce épinglée (is_pinned) remonte en tête de liste ; une annonce
 * non publiée (is_published = false) reste un brouillon invisible des
 * membres. Déclenche une notification (voir NewAnnoncePublished) à sa
 * publication.
 */
class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenu',
        'is_published',
        'is_pinned',
        'created_by',
        'image_path',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return str_starts_with($this->image_path, 'http')
            ? $this->image_path
            : asset('storage/'.$this->image_path);
    }
   
    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lecteurs()
{
    return $this->belongsToMany(
        User::class,
        'annonces_lues'
    )->withPivot('read_at');
}

}
