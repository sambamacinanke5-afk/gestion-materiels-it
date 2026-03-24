<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            // Libellé du menu
            $table->string('title');

            // Nom de la route Laravel
            $table->string('route')->nullable();

            // Classe icône (Font Awesome, Heroicons, etc.)
            $table->string('icon')->nullable();

            // Permission Spatie associée
            $table->string('permission_name')->nullable();

            // Parent pour gérer les sous-menus
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menus')
                ->cascadeOnDelete();

            // Ordre d'affichage
            $table->unsignedInteger('sort_order')->default(0);

            // Actif / inactif
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};