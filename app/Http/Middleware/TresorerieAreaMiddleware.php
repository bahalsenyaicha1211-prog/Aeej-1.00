<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias "tresorerie_area" (voir bootstrap/app.php). Porte d'entrée de
 * tout /tresorerie/* : autorise trésorier, chef trésorier OU
 * commissaire aux comptes. Chaque route ajoute ensuite son propre
 * middleware de rôle précis (tresorier / chef_tresorier / commissaire /
 * caisse_access) pour affiner l'accès à l'intérieur de l'espace.
 */
class TresorerieAreaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if (!$user->isTresorier() && !$user->isCommissaireComptes()) {
            abort(403, 'Accès réservé à la trésorerie.');
        }

        return $next($request);
    }
}
