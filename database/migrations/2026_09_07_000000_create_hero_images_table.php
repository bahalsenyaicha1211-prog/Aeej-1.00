<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hero_images', function (Blueprint $table) {
            $table->id();

            $table->string('image_path');                     // URL Cloudinary ou chemin local (images/imgN.jpeg)
            $table->string('alt')->nullable();               // texte alternatif (accessibilité)
            $table->unsignedInteger('position')->default(0); // ordre d'affichage dans le diaporama
            $table->boolean('is_active')->default(true);     // visible sur la page d'accueil

            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['is_active', 'position']);
        });

        // Import des 18 images actuellement codées en dur dans resources/views/accueil.blade.php
        // (img1..img9, img11..img19 — img10 n'existe pas). Elles restent supprimables depuis l'admin.
        $numeros = array_merge(range(1, 9), range(11, 19));
        $now = now();
        $rows = [];
        foreach (array_values($numeros) as $i => $n) {
            $rows[] = [
                'image_path' => "images/img{$n}.jpeg",
                'alt'        => 'AEEJ ' . ($i + 1),
                'position'   => $i + 1,
                'is_active'  => true,
                'created_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('hero_images')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_images');
    }
};
