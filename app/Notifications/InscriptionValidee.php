<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscriptionValidee extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function backoff(): array
    {
        return [300, 1800, 7200, 21600];
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre inscription à l\'AEEJ est validée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Bonne nouvelle : votre inscription a été validée par un administrateur de l\'AEEJ.')
            ->line('Vous pouvez dès maintenant vous connecter à la plateforme avec votre adresse e-mail (' . $notifiable->email . ') et le mot de passe que vous avez défini.')
            ->action('Me connecter', route('login'))
            ->line('— AEEJ');
    }
}
