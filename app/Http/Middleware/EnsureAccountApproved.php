<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias "approved" (voir bootstrap/app.php). Bloque l'accès aux espaces
 * internes tant qu'un administrateur n'a pas validé l'inscription du
 * membre (colonne users.approved_at). L'utilisateur reste connecté et
 * peut consulter la page « compte en attente » ou se déconnecter.
 */
class EnsureAccountApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isApproved()) {
            if ($request->expectsJson()) {
                abort(403, "Votre compte est en attente de validation par un administrateur.");
            }

            return redirect()->route('account.pending');
        }

        return $next($request);
    }
}
