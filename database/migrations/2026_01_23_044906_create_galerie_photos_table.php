<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('galerie_photos', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();              // optionnel
            $table->string('category', 80);                   // formation, fete, visite...
            $table->date('event_date');                       // date de l'événement
            $table->text('description')->nullable();          // optionnel

            $table->string('image_path');                     // storage path
            $table->boolean('is_published')->default(true);   // visible sur le public
            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['category', 'event_date', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galerie_photos');
    }
};
