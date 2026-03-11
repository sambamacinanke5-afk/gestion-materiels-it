<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ligne_deploiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deploiement_id')
                  ->constrained('deploiements')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('lignebondelivraison_id')
                  ->constrained('ligne_bondelivraisons')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');//quand le bon est pas repartier qu'il naffiche pas le lignebon
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('ligne_deploiements');
    }
};
