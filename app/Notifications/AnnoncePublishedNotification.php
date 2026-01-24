<?php

namespace App\Notifications;

use App\Models\Annonce;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnoncePubliee extends Notification
{
    use Queueable;

    public function __construct(public Annonce $annonce) {}

    public function via($notifiable): array
    {
        // On garde SIMPLE : database (et email plus tard)
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $contenuCourt = mb_substr(trim($this->annonce->contenu), 0, 80);

        return [
            'title' => $this->annonce->is_pinned ? 'Annonce épinglée' : 'Nouvelle annonce',
            'body'  => $contenuCourt . (mb_strlen($this->annonce->contenu) > 80 ? '…' : ''),
            'url'   => route('membre.annonces.show', $this->annonce),
            'annonce_id' => $this->annonce->id,
        ];
    }
}
