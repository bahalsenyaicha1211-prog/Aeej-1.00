<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
