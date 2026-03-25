<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_livraison_id')->nullable()->constrained('bons_livraison');
            $table->foreignId('categorie_id')->constrained('categories_materiel');
            $table->foreignId('marque_id')->constrained('marques');
            $table->foreignId('typemateriel_id')->constrained('type_materiels');

            $table->string('code_inventaire')->unique();
            $table->string('numero_serie')->nullable()->unique();
            $table->string('modele')->nullable();
            $table->string('statut')->default('recu');
            $table->foreignId('site_id')->nullable()->constrained();
            $table->foreignId('service_id')->nullable()->constrained();
            $table->foreignId('beneficiaire_id')->nullable()->constrained();
            $table->string('responsable_actuel')->nullable();
            $table->date('date_reception')->nullable();
            $table->date('date_validation')->nullable();
            $table->date('date_repartition')->nullable();
            $table->date('date_deploiement')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
