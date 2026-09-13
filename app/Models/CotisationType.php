<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une cotisation VOLONTAIRE configurée par le chef trésorier (ex.
 * « Camping 2026 », montant fixe) : un motif que les membres peuvent
 * choisir de payer en plus de la cotisation annuelle obligatoire
 * (voir Cotisation). actif = false retire le motif des choix proposés
 * sans supprimer l'historique des paiements déjà liés (paiements()).
 */
class CotisationType extends Model
{
    protected $fillable = [
        'annee',
        'nom',
        'montant',
        'actif',
        'created_by',
    ];

    protected $casts = [
        'annee' => 'integer',
        'montant' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function paiements()
    {
        return $this->hasMany(CotisationVolontaire::class);
    }

    public function scopeActifs($q)
    {
        return $q->where('actif', true);
    }
}
