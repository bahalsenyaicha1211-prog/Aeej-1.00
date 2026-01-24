<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->hasOne(User::class, 'matricule', 'matricule');
    }
}
