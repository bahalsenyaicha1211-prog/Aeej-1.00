<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Un paiement effectué par un membre pour une cotisation volontaire
 * précise (voir CotisationType, ex. « Camping 2026 »). Pas de contrainte
 * d'unicité : un membre peut payer plusieurs activités différentes la
 * même année, contrairement à la cotisation annuelle (voir Cotisation).
 * Attention : la table s'appelle "cotisations_volontaires" (au pluriel
 * sur les deux mots) — différent de la convention Eloquent par défaut,
 * d'où le $table explicite ci-dessous.
 */
class CotisationVolontaire extends Model
{
    protected $table = 'cotisations_volontaires';

    protected $fillable = [
        'matricule',
        'cotisation_type_id',
        'montant_paye',
        'date_paiement',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'montant_paye' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class, 'matricule', 'matricule');
    }

    public function type()
    {
        return $this->belongsTo(CotisationType::class, 'cotisation_type_id');
    }

    public function tresorier()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dernierEditeur()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
