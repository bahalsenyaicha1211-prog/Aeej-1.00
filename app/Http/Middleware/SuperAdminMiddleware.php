<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Alias "super_admin" (voir bootstrap/app.php). Le niveau de droits le
 * plus élevé : gestion des comptes admins et attribution des rôles
 * trésorerie. S'utilise TOUJOURS en plus de "admin" (jamais seul), sur
 * un sous-groupe de routes déjà protégé par AdminMiddleware.
 */
class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->is_super_admin) {
            abort(403, 'Accès réservé au super administrateur.');
        }

        return $next($request);
    }
}
