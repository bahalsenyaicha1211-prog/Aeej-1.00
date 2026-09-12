<?php

namespace App\Support;

use Carbon\Carbon;

/**
 * Une "année" de cotisation annuelle correspond à une année académique
 * (ex. 2026-2027, de la rentrée de septembre 2026 à l'été 2027), stockée
 * en base comme un simple entier "année de début" (2026) pour rester
 * compatible avec les colonnes/contraintes existantes ; seul l'affichage
 * change.
 */
class AcademicYear
{
    /**
     * Année de début de l'année académique en cours (rentrée en septembre).
     */
    public static function anneeActive(?Carbon $maintenant = null): int
    {
        $maintenant ??= now();

        return $maintenant->month >= 9 ? $maintenant->year : $maintenant->year - 1;
    }

    /**
     * Libellé d'affichage "2026-2027" à partir de l'année de début.
     */
    public static function label(int $anneeDebut): string
    {
        return $anneeDebut . '-' . ($anneeDebut + 1);
    }
}
