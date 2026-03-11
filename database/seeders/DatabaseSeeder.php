<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===============================
        // Création des rôles (si inexistants)
        // ===============================

        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');

        if (!$adminRoleId) {
            $adminRoleId = DB::table('roles')->insertGetId([
                'name' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $userRoleId = DB::table('roles')->where('name', 'User')->value('id');

        if (!$userRoleId) {
            $userRoleId = DB::table('roles')->insertGetId([
                'name' => 'User',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ===============================
        // Création de l'utilisateur Admin
        // ===============================

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'role_id' => $adminRoleId,
            ]
        );

        // ===============================
        // Création de l'utilisateur User
        // ===============================

        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'password' => Hash::make('123456'),
                'role_id' => $userRoleId,
            ]
        );
    }
}
