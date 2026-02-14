<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class VerifyEmailFrench extends VerifyEmail
{
    /**
     * Le message du mail en français.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Vérifiez votre adresse email - AEEJ')
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Merci de vous être inscrit sur la plateforme de l\'AEEJ.')
            ->line('Veuillez cliquer sur le bouton ci-dessous pour confirmer votre adresse email et finaliser la création de votre compte.')
            ->action('Confirmer mon adresse email', $verificationUrl)
            ->line('Si vous n\'avez pas créé de compte, vous pouvez ignorer cet email.')
            ->salutation('Cordialement, L\'équipe AEEJ');
    }

    /**
     * Génère l'URL de vérification officielle de Laravel.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}