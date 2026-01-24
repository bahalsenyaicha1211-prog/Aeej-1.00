<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
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
