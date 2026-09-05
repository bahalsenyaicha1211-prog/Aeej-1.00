<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute une validation de compte pour les inscriptions membres.
     *
     * L'inscription reste en libre-service, mais un nouveau compte membre
     * n'a accès à l'espace membre qu'une fois approuvé par un administrateur.
     * Tous les comptes déjà existants sont considérés approuvés (rétro-compat).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('email_verified_at');
        });

        // Ne rien casser pour les membres actuels : ils restent approuvés.
        DB::table('users')->whereNull('approved_at')->update(['approved_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
    }
};
