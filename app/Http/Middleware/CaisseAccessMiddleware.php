<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias "caisse_access" (voir bootstrap/app.php). Autorise le chef
 * trésorier ET le commissaire aux comptes (les deux rôles qui ont
 * besoin de voir la vue d'ensemble de la caisse).
 */
class CaisseAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->isChefTresorier() && !$request->user()->isCommissaireComptes()) {
            abort(403, 'Accès réservé au chef trésorier et au commissaire aux comptes.');
        }

        return $next($request);
    }
}
