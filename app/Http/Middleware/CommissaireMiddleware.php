<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias "commissaire" (voir bootstrap/app.php). Réservé au commissaire
 * aux comptes : gestion des dépenses et du rapport financier PDF.
 */
class CommissaireMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->isCommissaireComptes()) {
            abort(403, 'Accès réservé au commissaire aux comptes.');
        }

        return $next($request);
    }
}
