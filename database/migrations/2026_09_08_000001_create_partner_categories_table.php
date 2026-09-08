<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partner_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 80)->unique();
            $table->string('slug', 90)->unique();
            $table->timestamps();
        });

        $now = now();
        $rows = collect(['Administration', 'Club', 'Entreprise', 'Association', 'Autre'])
            ->map(fn ($nom) => [
                'nom'        => $nom,
                'slug'       => Str::slug($nom),
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

        DB::table('partner_categories')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_categories');
    }
};
