<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'is_suspended')) {
            $table->boolean('is_suspended')->default(false)->after('password');
        }

        if (!Schema::hasColumn('users', 'suspended_at')) {
            $table->timestamp('suspended_at')->nullable()->after('is_suspended');
        }
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_suspended', 'suspended_at']);
        });
    }
};
