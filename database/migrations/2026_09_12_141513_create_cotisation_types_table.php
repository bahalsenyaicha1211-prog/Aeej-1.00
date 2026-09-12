<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotisation_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('annee');
            $table->string('nom');
            $table->decimal('montant', 10, 2);
            $table->boolean('actif')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['annee', 'nom']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisation_types');
    }
};
