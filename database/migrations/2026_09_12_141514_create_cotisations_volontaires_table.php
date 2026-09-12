<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotisations_volontaires', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->foreign('matricule')->references('matricule')->on('membres')->cascadeOnDelete();
            $table->foreignId('cotisation_type_id')->constrained('cotisation_types')->restrictOnDelete();
            $table->decimal('montant_paye', 10, 2);
            $table->date('date_paiement');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Pas d'unicité (matricule, type) : un membre peut payer plusieurs
            // fois pour la même activité volontaire (ex. acomptes successifs).
            $table->index(['matricule']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisations_volontaires');
    }
};
