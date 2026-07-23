<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotisationConfig extends Model
{
    protected $fillable = [
        'annee',
        'montant_membre',
        'montant_bureau',
    ];

    protected $casts = [
        'annee' => 'integer',
        'montant_membre' => 'decimal:2',
        'montant_bureau' => 'decimal:2',
    ];

    public static function pourAnnee(int $annee): ?self
    {
        return static::where('annee', $annee)->first();
    }
}
