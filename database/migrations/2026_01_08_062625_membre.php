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
        Schema::create('membres', function (Blueprint $table) {
    $table->string('matricule')->primary();
    $table->string('nom');
    $table->string('prenom');
    $table->enum('sexe', ['M','F']);

    $table->unsignedBigInteger('iddep');
    $table->unsignedBigInteger('idpays');

    $table->year('annee_adhesion'); 
    $table->string('telephone', 20)->nullable(); 
    $table->string('email')->unique();
    $table->string('adresse')->nullable();

    $table->timestamps();

    $table->foreign('iddep')->references('iddep')->on('departements')->onDelete('cascade');
    $table->foreign('idpays')->references('idpays')->on('pays')->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres');
    }
};
