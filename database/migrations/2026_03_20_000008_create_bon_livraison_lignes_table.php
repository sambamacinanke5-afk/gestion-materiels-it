<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bon_livraison_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_livraison_id')->constrained()->cascadeOnDelete();
            $table->foreignId('categorie_id')->constrained('categories_materiel');
            $table->foreignId('marque_id')->constrained('marques');
            $table->string('modele')->nullable();
            $table->integer('quantite');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bon_livraison_lignes');
    }
};
