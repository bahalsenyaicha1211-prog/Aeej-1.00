<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotisationDate extends Model
{
    protected $fillable = [
        'annee',
        'date_collecte',
    ];

    protected $casts = [
        'annee' => 'integer',
        'date_collecte' => 'date',
    ];

    public static function pourAnnee(int $annee)
    {
        return static::where('annee', $annee)->orderBy('date_collecte')->get();
    }
}
