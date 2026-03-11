<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repartitions', function (Blueprint $table) {
            $table->id();
            $table->date('date_repartition');
            $table->unsignedBigInteger('bondelivraison_id');
            $table->integer('ordinateur_complets')->default(0);
            $table->integer('ordinateur_portables')->default(0);
            $table->integer('imprimantes')->default(0);
            $table->integer('scanners')->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ← ajouté
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repartitions');
    }
};
