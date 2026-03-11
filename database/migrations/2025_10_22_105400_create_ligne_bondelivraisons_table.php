<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ligne_bondelivraisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bondelivraison_id')
                ->constrained('bondelivraisons')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
                $table->foreignId('materiel_id')
                ->constrained('materiels')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('ligne_bondelivraisons');
    }
};
