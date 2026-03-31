<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::truncate();

        $menus = [

            // Dashboard
            [
                'title' => 'Tableau de Bord',
                'route' => 'dashboard',
                'icon' => 'fa-solid fa-gauge',
                'permission_name' => 'dashboard.view',
                'sort_order' => 1,
                'is_active' => 1,
            ],

            // Métier
            [
                'title' => 'Fournisseurs',
                'route' => 'fournisseurs.index',
                'icon' => 'fa-solid fa-truck',
                'permission_name' => 'fournisseurs.view',
                'sort_order' => 2,
                'is_active' => 1,
            ],
            [
                'title' => 'Bon de Livraison',
                'route' => 'bondelivraison.index',
                'icon' => 'fa-solid fa-file-invoice',
                'permission_name' => 'bondelivraison.view',
                'sort_order' => 3,
                'is_active' => 1,
            ],
            [
                'title' => 'Déploiement',
                'route' => 'deploiement.index',
                'icon' => 'fa-solid fa-network-wired',
                'permission_name' => 'deploiement.view',
                'sort_order' => 4,
                'is_active' => 1,
            ],
            [
                'title' => 'Matériels',
                'route' => 'materiels.index',
                'icon' => 'fa-solid fa-computer',
                'permission_name' => 'materiel.view',
                'sort_order' => 5,
                'is_active' => 1,
            ],
            [
                'title' => 'Marques',
                'route' => 'marque.index',
                'icon' => 'fa-solid fa-tags',
                'permission_name' => 'marque.view',
                'sort_order' => 6,
                'is_active' => 1,
            ],
            [
                'title' => 'Services',
                'route' => 'service.index',
                'icon' => 'fa-solid fa-screwdriver-wrench',
                'permission_name' => 'service.view',
                'sort_order' => 7,
                'is_active' => 1,
            ],
            [
                'title' => 'Répartition',
                'route' => 'repartition.index',
                'icon' => 'fa-solid fa-diagram-project',
                'permission_name' => 'repartition.view',
                'sort_order' => 8,
                'is_active' => 1,
            ],

            // Profil
            [
                'title' => 'Profil',
                'route' => 'profile.edit',
                'icon' => 'fa-solid fa-user',
                'permission_name' => 'profil.view',
                'sort_order' => 9,
                'is_active' => 1,
            ],

            // ADMIN
            [
                'title' => 'Utilisateurs',
                'route' => 'admin.users.index',
                'icon' => 'fa-solid fa-users',
                'permission_name' => 'users.view',
                'sort_order' => 10,
                'is_active' => 1,
            ],
            [
                'title' => 'Rôles',
                'route' => 'admin.roles.index',
                'icon' => 'fa-solid fa-user-shield',
                'permission_name' => 'roles.view',
                'sort_order' => 11,
                'is_active' => 1,
            ],
            [
                'title' => 'Permissions',
                'route' => 'admin.permissions.index',
                'icon' => 'fa-solid fa-shield-halved',
                'permission_name' => 'permissions.view',
                'sort_order' => 12,
                'is_active' => 1,
            ],
            [
                'title' => 'Menus',
                'route' => 'admin.menus.index',
                'icon' => 'fa-solid fa-list',
                'permission_name' => 'menus.view',
                'sort_order' => 13,
                'is_active' => 1,
            ],
              [
                'title' => 'Gestion Materiel',
                'route' => 'admin.gestionmateriel.index',
                'icon' => 'fa-solid fa-list',
                'permission_name' => 'gestionmateriel.view',
                'sort_order' => 14,
                'is_active' => 1,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
