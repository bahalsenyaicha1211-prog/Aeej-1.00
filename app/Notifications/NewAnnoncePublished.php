<?php

namespace App\Notifications;

use App\Models\Annonce;
use App\Notifications\Concerns\RespecteQuotaMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewAnnoncePublished extends Notification implements ShouldQueue
{
    use Queueable, RespecteQuotaMail;

    public function backoff(): array
    {
        return [300, 1800, 7200, 21600];
    }

    public function __construct(public Annonce $annonce, public bool $avecMail = true) {}

    public function via($notifiable): array
    {
        return $this->avecMail ? ['database', 'mail'] : ['database'];
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
