<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias "chef_tresorier" (voir bootstrap/app.php). Réservé au chef
 * trésorier uniquement (un seul compte à la fois, voir
 * Admin\TresorerieCompteController) : configuration des montants de
 * cotisation et des cotisations volontaires.
 */
class ChefTresorierMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->isChefTresorier()) {
            abort(403, 'Accès réservé au chef trésorier.');
        }

        return $next($request);
    }
}
