<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repartitions', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('site_destination_id')->constrained('sites');
            $table->foreignId('service_destination_id')->constrained('services');
            $table->date('date_repartition');
            $table->string('statut')->default('brouillon');
            $table->foreignId('cree_par')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repartitions');
    }
};
