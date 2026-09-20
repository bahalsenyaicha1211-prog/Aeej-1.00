<?php

namespace App\Support;

/**
 * Format imposé du matricule : 2 lettres (signature pays, cf. Pays::signature
 * et App\Rules\MatriculePaysMatch) + 2 chiffres (année d'adhésion, ex. "24"
 * pour 2024) + 4 chiffres (numéro de séquence). Ex. "GN240009".
 */
class Matricule
{
    public static function anneeAdhesion(string $matricule): int
    {
        return 2000 + (int) substr($matricule, 2, 2);
    }
}
