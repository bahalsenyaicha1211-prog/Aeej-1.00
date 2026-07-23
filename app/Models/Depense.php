<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
