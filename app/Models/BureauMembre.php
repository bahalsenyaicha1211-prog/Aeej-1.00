<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BureauMembre extends Model
{
    use HasFactory;

    protected $table = 'bureau_membres';

    protected $fillable = [
        'matricule',
        'poste',
        'photo',
        'ordre',
        'is_actif',
    ];

    protected $casts = [
        'is_actif' => 'boolean',
        'ordre' => 'integer',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class, 'matricule', 'matricule');
    }

   public function getPhotoUrlAttribute(): string
{
    // 1. Si aucune photo n'est définie
    if (!$this->photo) {
        return asset('images/image1.JPG');
    }

    // 2. Si c'est une image Cloudinary (commence par http)
    if (str_starts_with($this->photo, 'http')) {
        return $this->photo;
    }

    // 3. Si c'est une ancienne image stockée localement
    return asset('storage/' . $this->photo);
}


}
