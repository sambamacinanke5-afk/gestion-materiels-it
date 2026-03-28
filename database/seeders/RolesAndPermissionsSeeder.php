<?php

namespace Database\Seeders;

use App\Models\User;
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
            // Dashboard
            'dashboard.view',

            // Menus
            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',

            // Rôles
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',

            // Utilisateurs
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.suspend',
            'users.reset-password',

            // Métier
            'fournisseurs.view',
            'bondelivraison.view',
            'deploiement.view',
            'materiel.view',
            'marque.view',
            'service.view',
            'repartition.view',
            'profil.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('admin', 'web');
        $it = Role::findOrCreate('it', 'web');
        $mg = Role::findOrCreate('mg', 'web');
        $audit = Role::findOrCreate('audit', 'web');

        // Admin : tout
        $admin->syncPermissions(Permission::all());

        // IT
        $it->syncPermissions([
            'dashboard.view',
            'materiel.view',
            'bondelivraison.view',
            'deploiement.view',
            'marque.view',
            'service.view',
            'repartition.view',
            'profil.view',
        ]);

        // MG
        $mg->syncPermissions([
            'dashboard.view',
            'materiel.view',
            'repartition.view',
            'deploiement.view',
            'bondelivraison.view',
        ]);

        // AUDIT
        $audit->syncPermissions([
            'dashboard.view',
            'fournisseurs.view',
            'bondelivraison.view',
            'materiel.view',
            'repartition.view',
            'profil.view',
        ]);

        // Attribution automatique du rôle admin à l'utilisateur admin
        $adminUser = User::where('email', 'admin@oditech.ml')->first();

        if ($adminUser) {
            $adminUser->syncRoles(['admin']);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
