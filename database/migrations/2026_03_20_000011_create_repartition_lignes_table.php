<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repartition_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repartition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('materiel_id')->constrained()->cascadeOnDelete();
            $table->string('statut')->default('a_repartir');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repartition_lignes');
    }
};
