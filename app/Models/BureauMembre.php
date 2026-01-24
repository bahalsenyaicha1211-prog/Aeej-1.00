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
    return $this->photo
        ? asset('storage/' . $this->photo)
        : asset('images/image1.JPG');
}


}
