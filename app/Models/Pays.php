<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Un pays représenté à l'AEEJ (ex. Guinée). Clé primaire "idpays"
 * (héritée du schéma d'origine) au lieu du "id" habituel. "signature"
 * = préfixe à 2 lettres du matricule des membres de ce pays (ex. "GN"),
 * vérifié à l'inscription par App\Rules\MatriculePaysMatch.
 */
class Pays extends Model
{
    use HasFactory;

    protected $table = 'pays';
    protected $primaryKey = 'idpays';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['idpays', 'nom', 'signature'];

    public function membres()
    {
        return $this->hasMany(Membre::class, 'idpays', 'idpays');
    }
}
