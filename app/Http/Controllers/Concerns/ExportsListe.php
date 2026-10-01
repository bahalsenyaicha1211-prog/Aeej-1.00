<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Contracts\View\View;

/**
 * Page d'impression A4 d'une liste admin (membres, bureau…) : le navigateur
 * l'imprime ou l'« Enregistre en PDF ».
 * $colonnes = libellés d'en-tête, $lignes = tableaux de valeurs dans le même ordre,
 * $resume = texte affiché en haut à droite (par défaut : nombre de personnes).
 */
trait ExportsListe
{
    protected function imprimerListe(string $titre, array $colonnes, array $lignes, ?string $resume = null): View
    {
        $resume ??= count($lignes) . (count($lignes) > 1 ? ' personnes' : ' personne');

        return view('admin.export.liste', compact('titre', 'colonnes', 'lignes', 'resume'));
    }
}
