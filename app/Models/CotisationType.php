<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
