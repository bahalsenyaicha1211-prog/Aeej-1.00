<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_tresorier')->default(false)->after('is_admin');
            $table->boolean('is_chef_tresorier')->default(false)->after('is_tresorier');
            $table->boolean('is_commissaire_comptes')->default(false)->after('is_chef_tresorier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_tresorier', 'is_chef_tresorier', 'is_commissaire_comptes']);
        });
    }
};
