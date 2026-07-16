<?php

namespace App\Rules;

use Closure;
use App\Models\Pays;
use Illuminate\Contracts\Validation\ValidationRule;

class MatriculePaysMatch implements ValidationRule
{
    protected $idPays;

    public function __construct($idPays)
    {
        $this->idPays = $idPays;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Trouver le pays sélectionné dans le formulaire
        $pays = Pays::where('idpays', $this->idPays)->first();

        if (!$pays) {
            $fail("Pays sélectionné introuvable.");
            return;
        }

        // Sécurité : un pays sans signature configurée ne peut pas être vérifié,
        // on refuse l'inscription plutôt que de laisser passer n'importe quel matricule.
        if (!$pays->signature) {
            $fail("Aucun préfixe de matricule n'est encore configuré pour '{$pays->nom}'. Contactez un administrateur.");
            return;
        }

        // 2. Extraire la signature attendue (ex: GN)
        $signatureAttendue = strtoupper($pays->signature);

        // 3. Extraire le début du matricule saisi (2 premières lettres)
        $debutMatricule = strtoupper(substr($value, 0, 2));

        // 4. Comparaison
        if ($debutMatricule !== $signatureAttendue) {
            $fail("Le matricule doit obligatoirement commencer par '{$signatureAttendue}' pour correspondre au pays choisi.");
        }
    }
}