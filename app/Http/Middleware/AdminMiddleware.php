<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Alias "admin" (voir bootstrap/app.php). Protège tout le back-office
 * (/admin/*) : exige un compte avec is_admin = true. Pour les actions
 * réservées au super-admin (gestion des admins, rôles trésorerie), voir
 * SuperAdminMiddleware, appliqué EN PLUS de celui-ci sur ces routes.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->is_admin) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
