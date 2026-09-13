<?php

namespace App\Models;
use App\Notifications\WelcomeSetPassword;
use App\Notifications\VerifyEmailFrench;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;


/**
 * Compte de connexion (auth Laravel). À ne pas confondre avec Membre :
 * un User sert à se connecter (email/mot de passe), un Membre est la
 * fiche associative (matricule, pays, département...) — reliés par le
 * même matricule (relation membre()). Un admin créé directement par un
 * super-admin peut ne PAS avoir de matricule/Membre associé.
 *
 * Rôles (colonnes booléennes, cumulables sauf mention contraire) :
 *   - is_admin              : accès au back-office /admin
 *   - is_super_admin        : + gestion des comptes admins et des rôles
 *                             trésorerie (le plus haut niveau de droits)
 *   - is_tresorier          : enregistre les paiements de cotisation
 *   - is_chef_tresorier     : + configure les montants, un seul à la fois
 *                             (voir Admin\TresorerieCompteController)
 *   - is_commissaire_comptes: gère les dépenses et consulte la caisse
 * approved_at : renseigné par un admin après validation de l'inscription
 * (voir isApproved()) — tant qu'il est null, le membre est redirigé vers
 * l'écran d'attente et ne peut pas utiliser l'espace membre.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'matricule',
        'role',
        'is_admin',
        'profile_photo_path',
        'is_tresorier',
        'is_chef_tresorier',
        'is_commissaire_comptes',
        'email_verified_at',
        'approved_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at'       => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
        'is_tresorier'          => 'boolean',
        'is_chef_tresorier'     => 'boolean',
        'is_commissaire_comptes' => 'boolean',
    ];


    public function membre()
    {
        return $this->belongsTo(Membre::class, 'matricule', 'matricule');
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Un compte est utilisable si c'est un admin, ou si un administrateur a
     * validé l'inscription du membre (colonne approved_at renseignée).
     */
    public function isApproved(): bool
    {
        return $this->is_admin || $this->approved_at !== null;
    }

    public function isSuperAdmin(): bool
{
    return (bool) ($this->is_super_admin ?? false);
}

    public function isTresorier(): bool
    {
        return (bool) $this->is_tresorier || $this->isChefTresorier();
    }

    public function isChefTresorier(): bool
    {
        return (bool) $this->is_chef_tresorier;
    }

    public function isCommissaireComptes(): bool
    {
        return (bool) $this->is_commissaire_comptes;
    }

    /**
     * URL avatar prêt à l’emploi : auth()->user()->profile_photo_url
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : asset('images/default-avatar.png');
    }

    /**
     * Photo à afficher dans les listes (admins, comptes trésorerie…) :
     * 1) sa propre photo de profil, sinon
     * 2) la photo de sa fiche « membre du bureau », sinon
     * 3) l'avatar par défaut.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return \Illuminate\Support\Str::startsWith($this->profile_photo_path, 'http')
                ? $this->profile_photo_path
                : asset('storage/' . $this->profile_photo_path);
        }

        $bureau = $this->membre?->bureauMembres;
        if ($bureau && $bureau->photo) {
            return $bureau->photo_url;
        }

        return asset('images/default-avatar.png');
    }

  
    public function annoncesLues()
    {
        return $this->belongsToMany(
            \App\Models\Annonce::class,
            'annonces_lues',
            'user_id',
            'annonce_id'
        )->withPivot('read_at');
    }

public function sendEmailVerificationNotification()
{
    $this->notify(new VerifyEmailFrench);
}


    public function sendPasswordResetNotification($token)
{
    $this->notify(new WelcomeSetPassword($token));
}
}
