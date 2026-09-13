<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Fiche d'un membre de l'association (créée lors de l'inscription
 * publique). Clé primaire "matricule" (chaîne, PAS un entier auto-
 * incrémenté : $incrementing = false), ex. "GN240009" — les 2 premières
 * lettres correspondent à la signature du pays (voir Pays, vérifié par
 * App\Rules\MatriculePaysMatch). Chaque membre approuvé par un admin a
 * un compte User associé (relation user(), même matricule des deux
 * côtés) qui sert à se connecter ; la fiche Membre, elle, ne contient
 * aucun identifiant de connexion.
 */
class Membre extends Model
{
    use HasFactory;

    protected $table = 'membres';
    protected $primaryKey = 'matricule';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matricule','nom','prenom','sexe',
        'iddep','idpays','annee_adhesion',
        'telephone','email','adresse'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'iddep', 'iddep');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'idpays', 'idpays');
    }

    public function bureauMembres()
    {
        return $this->hasOne(BureauMembre::class, 'matricule', 'matricule');
    }

    public function estMembreDuBureau(): bool
    {
        return BureauMembre::where('matricule', $this->matricule)
            ->where('is_actif', true)
            ->exists();
    }
    
    public function user()
    {
        return $this->hasOne(User::class, 'matricule', 'matricule');
    }

    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'matricule', 'matricule');
    }

    public function cotisationsVolontaires()
    {
        return $this->hasMany(CotisationVolontaire::class, 'matricule', 'matricule');
    }
}
