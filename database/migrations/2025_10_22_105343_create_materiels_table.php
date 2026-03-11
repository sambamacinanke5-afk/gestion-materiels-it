<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('typemateriel_id');
            $table->foreign('typemateriel_id')
                  ->references('id')->on('type_materiels')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('marque_id');
            $table->foreign('marque_id')
                  ->references('id')->on('marques')
                  ->onDelete('cascade')->onUpdate('cascade');
            $table->string('designation');
            $table->string('numero_serie')->unique();
            $table->enum('etat', ['non_deploye', 'en_cours', 'deployee', 'hors_service'])
                  ->default('non_deploye');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
