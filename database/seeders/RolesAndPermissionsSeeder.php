<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.admin',
            'dashboard.it',
            'dashboard.mg',
            'dashboard.audit',

            'materiel.view',
            'materiel.create',
            'materiel.update',
            'materiel.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $it = Role::firstOrCreate([
            'name' => 'it',
            'guard_name' => 'web',
        ]);

        $mg = Role::firstOrCreate([
            'name' => 'mg',
            'guard_name' => 'web',
        ]);

        $audit = Role::firstOrCreate([
            'name' => 'audit',
            'guard_name' => 'web',
        ]);

        $admin->givePermissionTo($permissions);

        $it->givePermissionTo([
            'dashboard.it',
            'materiel.view',
            'materiel.create',
            'materiel.update',
        ]);

        $mg->givePermissionTo([
            'dashboard.mg',
            'materiel.view',
        ]);

        $audit->givePermissionTo([
            'dashboard.audit',
            'materiel.view',
        ]);
    }
}