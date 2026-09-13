<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Montant de la cotisation annuelle obligatoire pour une année donnée,
 * selon la catégorie du membre (simple ou bureau). Définie par le chef
 * trésorier (« Montants cotisation »). Un seul montant actif à la fois
 * pour les nouveaux paiements : App\Support\AcademicYear::anneeActive().
 */
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
