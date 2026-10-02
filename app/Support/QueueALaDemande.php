<?php

namespace App\Support;

use Illuminate\Queue\Events\JobQueued;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Worker d'e-mails « à la demande » : pas de processus permanent qui
 * interroge la table jobs (sur TiDB Starter, une base sollicitée toutes les
 * minutes ne se met jamais en veille et consomme ~30 RU/s en continu, ce qui
 * épuise le quota gratuit mensuel). À la place, dès qu'un job est mis en file
 * pendant une requête, on lance en arrière-plan, une fois la réponse
 * envoyée, un `queue:work --stop-when-empty` qui vide la file puis s'arrête.
 *
 * Limite : un mail repoussé par le plafond quotidien Brevo (RateLimited)
 * reste en file et partira avec le prochain envoi, pas tout seul le lendemain.
 */
class QueueALaDemande
{
    private static bool $planifie = false;

    public static function activer(): void
    {
        // Le worker détaché (« & ») ne fonctionne que sous Linux (Render) ; en
        // local Windows, lancer `php artisan queue:work` à la main.
        if (PHP_OS_FAMILY === 'Windows' || config('queue.default') !== 'database') {
            return;
        }

        Event::listen(JobQueued::class, function () {
            if (self::$planifie) {
                return;
            }
            self::$planifie = true;

            app()->terminating(fn () => self::lancerWorker());
        });
    }

    private static function lancerWorker(): void
    {
        $php = (new PhpExecutableFinder())->find(false) ?: 'php';

        exec(sprintf(
            'cd %s && nohup %s artisan queue:work --stop-when-empty --tries=3 --timeout=90 > /dev/null 2>&1 &',
            escapeshellarg(base_path()),
            escapeshellarg($php),
        ));
    }
}
