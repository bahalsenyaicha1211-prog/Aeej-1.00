<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'tresorier' => \App\Http\Middleware\TresorierMiddleware::class,
            'chef_tresorier' => \App\Http\Middleware\ChefTresorierMiddleware::class,
            'commissaire' => \App\Http\Middleware\CommissaireMiddleware::class,
            'caisse_access' => \App\Http\Middleware\CaisseAccessMiddleware::class,
            'tresorerie_area' => \App\Http\Middleware\TresorerieAreaMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
