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
        Schema::create('cotisations', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->foreign('matricule')->references('matricule')->on('membres')->cascadeOnDelete();
            $table->unsignedSmallInteger('annee');
            $table->enum('categorie', ['membre', 'bureau']);
            $table->decimal('montant_du', 10, 2);
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('reste', 10, 2)->default(0);
            $table->date('date_paiement');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['matricule', 'annee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotisations');
    }
};
