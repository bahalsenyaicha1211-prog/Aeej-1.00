<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotisation_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('annee');
            $table->date('date_collecte');
            $table->timestamps();

            $table->unique(['annee', 'date_collecte']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisation_dates');
    }
};
