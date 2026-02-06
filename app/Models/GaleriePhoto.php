<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'event_date',
        'description',
        'image_path',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
        return $this->image_path;
    }
        return asset('storage/' . $this->image_path);
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }
}
