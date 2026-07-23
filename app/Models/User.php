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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
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
