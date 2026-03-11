<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lignerepartitions', function (Blueprint $table) {
            $table->id();
            $table->integer('num_ligne')->nullable();
            $table->string('destinataire');
            $table->integer('quantite')->default(0);
            // Clés étrangères avec cascade on update et on delete
            $table->foreignId('repartition_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->json('lignebondelivraison_id')->nullable();   // matériels venant du BL ⭐
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lignerepartitions');
    }
};
