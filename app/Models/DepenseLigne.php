<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepenseLigne extends Model
{
    protected $fillable = [
        'depense_id',
        'designation',
        'montant',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    public function depense()
    {
        return $this->belongsTo(Depense::class);
    }
}
