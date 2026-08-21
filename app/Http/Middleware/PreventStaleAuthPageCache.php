<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Empêche le navigateur de restaurer une page HTML depuis son cache
 * (bouton "précédent") après un changement d'état de connexion. Sans ça, un
 * membre qui quitte son espace sans se déconnecter peut retomber sur
 * l'accueil tel qu'il était affiché avant sa connexion (lien "S'inscrire"
 * visible), cliquer dessus, puis se faire renvoyer vers son tableau de bord
 * par le middleware "guest" — sans comprendre pourquoi.
 */
class PreventStaleAuthPageCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}
