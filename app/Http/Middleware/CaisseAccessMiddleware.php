<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
