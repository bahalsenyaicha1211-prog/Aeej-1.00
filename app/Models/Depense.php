<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une dépense de l'association (ex. « Journée culturelle 2026 »),
 * saisie par le commissaire aux comptes. Le montant peut être détaillé
 * en plusieurs lignes (voir DepenseLigne, relation lignes()) ; figure
 * dans le rapport financier PDF (DepenseController::rapportPdf) et
 * dans le calcul du solde de la Caisse (Cotisation - Depense).
 */
class Depense extends Model
{
    protected $fillable = [
        'nom_evenement',
        'montant_total',
        'date_depense',
        'created_by',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'date_depense' => 'date',
    ];

    public function lignes()
    {
        return $this->hasMany(DepenseLigne::class);
    }

    public function commissaire()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recalculerTotal(): void
    {
        $this->montant_total = $this->lignes()->sum('montant');
    }
}
