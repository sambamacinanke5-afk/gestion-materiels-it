<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bondelivraisons', function (Blueprint $table) {
            $table->string('statut')->default('brouillon');
        });
    }

    public function down()
    {
        Schema::table('bondelivraisons', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }

};
