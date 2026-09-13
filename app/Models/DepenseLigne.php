<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Une ligne de détail d'une dépense (ex. « Location salle » : 150 TND
 * dans la dépense « Journée culturelle »). Depense::recalculerTotal()
 * fait toujours la somme de ses lignes : ne jamais modifier
 * montant_total directement sans passer par cette méthode.
 */
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
