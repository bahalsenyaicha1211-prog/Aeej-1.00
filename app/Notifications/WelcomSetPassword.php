<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeSetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // On construit l'URL avec l'email inclus pour éviter l'erreur de Token
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Bienvenue à l\'AEEJ - Définissez votre mot de passe')
            ->greeting('Félicitations ' . $notifiable->name . ' !')
            ->line('Votre compte a été créé avec succès sur notre plateforme.')
            ->line('Pour pouvoir accéder à votre espace membre, vous devez maintenant définir votre mot de passe personnel.')
            ->action('Définir mon mot de passe', $url)
            ->line('Ce lien expirera dans 60 minutes.')
            ->line('Si vous n\'êtes pas à l\'origine de cette inscription, ignorez ce message.');
    }
}