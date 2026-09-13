<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Département d'études d'un membre (ex. Droit, Économie). Clé primaire
 * "iddep" (héritée du schéma d'origine) au lieu du "id" habituel.
 * Référentiel simple géré depuis l'admin, utilisé par le formulaire
 * d'inscription public.
 */
class Departement extends Model
{
    use HasFactory;

    protected $table = 'departements';
    protected $primaryKey = 'iddep';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nom'];

    public function membres()
    {
        return $this->hasMany(Membre::class, 'iddep', 'iddep');
    }
}