<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_people', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('poste', 150);
            $table->string('telephone', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('photo');                            // URL Cloudinary ou chemin public legacy
            $table->boolean('is_highlighted')->default(false);  // mise en avant (ex. président)
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_published', 'position']);
        });

        // Reprend tel quel le contenu actuellement codé en dur dans la vue
        // publique, pour que la page ne change pas d'un pixel tant que
        // l'admin n'a rien modifié depuis le nouveau panneau.
        $adminId = DB::table('users')->where('is_super_admin', true)->orderBy('id')->value('id')
            ?? DB::table('users')->where('is_admin', true)->orderBy('id')->value('id');

        DB::table('contact_people')->insert([
            [
                'nom' => 'MOHADED DIANE',
                'poste' => 'Président',
                'telephone' => '+216 56 464 039',
                'email' => 'dianemohamed0701@gmail.com',
                'photo' => 'images/team/president.jpg',
                'is_highlighted' => true,
                'position' => 1,
                'is_published' => true,
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'AHMED AKRAM',
                'poste' => 'Secrétaire Général',
                'telephone' => '+216 56 660 514',
                'email' => 'Ahmed390akram@gmail.com',
                'photo' => 'images/team/secretaire.jpg',
                'is_highlighted' => false,
                'position' => 2,
                'is_published' => true,
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'ALSENY BAH',
                'poste' => 'Chargée de la Communication',
                'telephone' => '+216 53 877 709',
                'email' => 'bahalseny.aicha1211@gmail.com',
                'photo' => 'images/team/chargercom.jpg',
                'is_highlighted' => false,
                'position' => 3,
                'is_published' => true,
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_people');
    }
};
