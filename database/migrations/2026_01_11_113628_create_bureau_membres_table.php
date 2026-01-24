<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bureau_membres', function (Blueprint $table) {
            $table->id();

            $table->string('matricule');
            $table->string('poste', 120);

            $table->string('photo')->nullable(); // chemin storage
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('is_actif')->default(false);

            $table->timestamps();

            $table->foreign('matricule')
                ->references('matricule')
                ->on('membres')
                ->cascadeOnDelete();

            // Empêche doublon (un matricule + un poste)
            $table->unique(['matricule', 'poste']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bureau_membres');
    }
};
