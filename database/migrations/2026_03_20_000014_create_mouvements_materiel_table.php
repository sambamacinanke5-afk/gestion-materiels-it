<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mouvements_materiel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained()->cascadeOnDelete();
            $table->string('type_mouvement');
            $table->string('ancien_statut')->nullable();
            $table->string('nouveau_statut');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_materiel');
    }
};
