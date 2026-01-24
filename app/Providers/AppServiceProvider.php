<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
    Gate::define('delete-admin', function (User $authUser, User $targetUser) {
        // Le super admin uniquement
        if (!$authUser->is_super_admin) return false;

        // Optionnel: empêcher la suppression de soi-même
        if ($authUser->id === $targetUser->id) return false;

        // On ne supprime que des admins
        return (bool) $targetUser->is_admin;
    });
}
}
