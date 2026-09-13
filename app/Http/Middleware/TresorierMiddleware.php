<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias "tresorier" (voir bootstrap/app.php). Autorise trésorier ET
 * chef trésorier (User::isTresorier() renvoie vrai pour les deux, le
 * chef trésorier ayant en plus des droits supplémentaires via
 * ChefTresorierMiddleware sur certaines routes).
 */
class TresorierMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->isTresorier()) {
            abort(403, 'Accès réservé aux trésoriers.');
        }

        return $next($request);
    }
}
