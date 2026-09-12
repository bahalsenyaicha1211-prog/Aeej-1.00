<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Retire la table "dates de collecte" : sur relecture, la contrainte ne
 * correspond pas au fonctionnement réel des cotisations (un an = une
 * cotisation annuelle, pas une liste de dates de réunion). Aucune donnée
 * de paiement n'y est liée (cotisations.date_paiement est une simple
 * colonne date, pas une clé étrangère) : suppression sans perte.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('cotisation_dates');
    }

    public function down(): void
    {
        Schema::create('cotisation_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('annee');
            $table->date('date_collecte');
            $table->timestamps();

            $table->unique(['annee', 'date_collecte']);
        });
    }
};
