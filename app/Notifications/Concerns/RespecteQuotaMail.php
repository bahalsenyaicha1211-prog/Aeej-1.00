<?php

namespace App\Notifications\Concerns;

use DateTimeInterface;
use Illuminate\Queue\Middleware\RateLimited;

/**
 * Les mails en file passent par le limiteur "brevo-quotidien" (voir
 * AppServiceProvider) : au-delà du quota, le job est remis en file au lieu
 * d'échouer, et repart quand la fenêtre de 24 h se libère (jusqu'à 3 jours).
 */
trait RespecteQuotaMail
{
    public function middleware(object $notifiable, string $channel): array
    {
        return $channel === 'mail' ? [new RateLimited('brevo-quotidien')] : [];
    }

    public function retryUntil(): DateTimeInterface
    {
        return now()->addDays(3);
    }
}
