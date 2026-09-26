<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementEnregistre extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function backoff(): array
    {
        return [300, 1800, 7200, 21600];
    }

    public function __construct(
        public string $libelle,
        public float $montantPaye,
        public ?float $reste,
        public string $datePaiement,
        public string $enregistrePar,
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Paiement enregistré - ' . $this->libelle)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre paiement a bien été enregistré par ' . $this->enregistrePar . '.')
            ->line('Cotisation : ' . $this->libelle)
            ->line('Montant payé : ' . number_format($this->montantPaye, 2, ',', ' ') . ' TND')
            ->line('Date du paiement : ' . $this->datePaiement);

        if ($this->reste !== null) {
            $mail->line('Reste à payer : ' . number_format($this->reste, 2, ',', ' ') . ' TND');
        }

        return $mail
            ->action('Voir mes cotisations', route('membre.cotisations.index'))
            ->line('— AEEJ');
    }
}
