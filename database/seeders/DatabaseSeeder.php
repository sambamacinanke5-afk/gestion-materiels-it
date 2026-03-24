<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Appel du seeder Spatie (rôles + permissions)
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        //appel du seeder de création des menus
            $this->call(MenuSeeder::class);

        // ===============================
        // Création Admin
        // ===============================

        $admin = User::firstOrCreate(
            ['email' => 'admin@oditech.ml'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
            ]
        );

        // Assigner rôle
        $admin->assignRole('admin');

        // ===============================
        // Création User IT
        // ===============================

        $user = User::firstOrCreate(
            ['email' => 'it@oditech.ml'],
            [
                'name' => 'IT',
                'password' => Hash::make('123456'),
            ]
        );

        $user->assignRole('it');
         // ===============================
        // Création User MG
        // ===============================

        $user = User::firstOrCreate(
            ['email' => 'mg@oditech.ml'],
            [
                'name' => 'MG',
                'password' => Hash::make('123456'),
            ]
        );

        $user->assignRole('mg');

         // ===============================
        // Création User AUDIT
        // ===============================

        $user = User::firstOrCreate(
            ['email' => 'audit@oditech.ml'],
            [
                'name' => 'AUDIT',
                'password' => Hash::make('123456'),
            ]
        );

        $user->assignRole('audit');
    }
}