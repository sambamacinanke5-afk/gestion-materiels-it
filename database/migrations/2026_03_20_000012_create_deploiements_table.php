<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deploiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('beneficiaire_id')->constrained();
            $table->foreignId('technicien_id')->nullable()->constrained('users');
            $table->date('date_deploiement');
            $table->string('statut')->default('planifie');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deploiements');
    }
};
