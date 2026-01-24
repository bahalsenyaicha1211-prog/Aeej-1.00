<?php

namespace App\Notifications;

use App\Models\Annonce;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAnnoncePublished extends Notification
{
    use Queueable;

    public function __construct(public Annonce $annonce) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'annonce_id'    => $this->annonce->id,
            'excerpt'       => mb_strimwidth($this->annonce->contenu, 0, 120, '...'),
            'is_pinned'     => (bool) $this->annonce->is_pinned,
            'published_at'  => optional($this->annonce->published_at)->toDateTimeString(),
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle annonce AEEJ')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle annonce a été publiée.')
            ->line(mb_strimwidth($this->annonce->contenu, 0, 180, '...'))
            ->action('Voir l’annonce', route('membre.annonces.show', $this->annonce))
            ->line('— AEEJ');
    }
}
