<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Chiffres agrégés (page d'accueil, tableau de bord admin) mis en cache pour
 * éviter de relancer une dizaine de requêtes sur la base distante à chaque
 * visite. À vider dès qu'une donnée sous-jacente change (membre, pays,
 * département, activité, bureau, photo du diaporama).
 */
class StatsCache
{
    /** @var list<string> */
    public const KEYS = ['home.stats', 'admin.dashboard'];

    public static function flush(): void
    {
        foreach (self::KEYS as $key) {
            Cache::forget($key);
        }
    }
}
