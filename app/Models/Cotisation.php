<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Paiement de la cotisation ANNUELLE OBLIGATOIRE d'un membre pour une
 * année académique donnée (colonne "annee" = année de début, ex. 2026
 * pour "2026-2027" — voir App\Support\AcademicYear::label()). Un seul
 * enregistrement par (matricule, annee) : unique en base. À ne pas
 * confondre avec CotisationVolontaire (activités optionnelles, camping
 * etc.), qui vit dans une table séparée et peut se cumuler librement.
 */
class Cotisation extends Model
{
    protected $fillable = [
        'matricule',
        'annee',
        'categorie',
        'montant_du',
        'montant_paye',
        'reste',
        'date_paiement',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'annee' => 'integer',
        'montant_du' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'reste' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class, 'matricule', 'matricule');
    }

    public function tresorier()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dernierEditeur()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function recalculerReste(): void
    {
        $this->reste = max(0, $this->montant_du - $this->montant_paye);
    }
}
