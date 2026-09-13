<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Message envoyé par un visiteur via le formulaire public de la page
 * Contact (donnée non fiable : n'importe qui peut la soumettre, ne
 * jamais afficher son contenu sans échappement dans les vues).
 * Consultable uniquement depuis l'admin (Admin\ContactMessageController).
 */
class ContactMessage extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'message',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
