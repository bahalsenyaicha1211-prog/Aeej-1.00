<?php

namespace App\Providers;

use App\Models\Activite;
use App\Models\Annonce;
use App\Models\BureauMembre;
use App\Models\Departement;
use App\Models\Membre;
use App\Models\Pays;
use App\Models\User;
use App\Support\StatsCache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forcer le HTTPS en production pour le CSS/JS
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Vider le cache des chiffres agrégés (accueil + dashboard admin) dès
        // qu'une donnée sous-jacente change, où que se produise l'écriture.
        foreach ([Membre::class, Pays::class, Departement::class, Activite::class, BureauMembre::class, Annonce::class] as $model) {
            $model::saved(fn () => StatsCache::flush());
            $model::deleted(fn () => StatsCache::flush());
        }

        // Vos Gates existantes
        Gate::define('delete-admin', function (User $authUser, User $targetUser) {
            // Le super admin uniquement
            if (!$authUser->is_super_admin) return false;

            // Empêcher la suppression de soi-même
            if ($authUser->id === $targetUser->id) return false;

            // On ne supprime que des admins
            return (bool) $targetUser->is_admin;
        });
    }
}