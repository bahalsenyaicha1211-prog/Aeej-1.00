<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->string('logo_path');                      // URL Cloudinary
            $table->text('description');
            $table->string('url', 255)->nullable();           // site du partenaire
            $table->foreignId('partner_category_id')->nullable()
                  ->constrained('partner_categories')->nullOnDelete();
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_published', 'partner_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
