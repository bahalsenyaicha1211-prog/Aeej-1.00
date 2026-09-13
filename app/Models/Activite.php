<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Une activité de l'association (journée culturelle, camping, etc.),
 * affichée sur la page publique « Activités ». Clé primaire "idacti"
 * (héritée du schéma d'origine) au lieu du "id" habituel de Laravel.
 * Pas de lien direct avec la Galerie : le rapprochement entre une
 * activité et ses photos se fait par correspondance de texte
 * (voir FrontendController::galerie(), paramètre "q").
 */
class Activite extends Model
{
    use HasFactory;

    protected $table = 'activites';
    protected $primaryKey = 'idacti';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['libelle','categorie','date'];

    protected $casts = [
        'date' => 'date',
    ];
}
