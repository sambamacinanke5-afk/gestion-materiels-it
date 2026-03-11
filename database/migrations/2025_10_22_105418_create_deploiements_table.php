<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deploiements', function (Blueprint $table) {
            $table->id();
            $table->date('Datecreation');
            $table->string('Direction');
            $table->string('Utilisateur');
            $table->string('Poste');
            $table->string('Systeme')->nullable();
            $table->string('Ram')->nullable();
            $table->string('Disque')->nullable();
            $table->string('Nomordinateur')->nullable();
            $table->foreignId('bondelivraison_id')
                  ->constrained('bondelivraisons')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');//quand le bon est pas repartier qu'il naffiche pas le bon 
            $table->enum('etat', ['non_deploye', 'en_cours', 'deployee', 'hors_service'])
                  ->default('non_deploye');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('deploiements');
    }
};
