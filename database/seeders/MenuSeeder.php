<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Nettoyer la table (optionnel mais recommandé en dev)
        Menu::truncate();

        $menus = [
            [
                'title' => 'Tableau de Bord',
                'route' => 'dashboard',
                'icon' => 'fa-solid fa-chart-line',
                'permission_name' => 'dashboard.view',
                'sort_order' => 1,
            ],
            [
                'title' => 'Fournisseurs',
                'route' => 'fournisseurs.index',
                'icon' => 'fa-solid fa-truck',
                'permission_name' => 'fournisseurs.view',
                'sort_order' => 2,
            ],
            [
                'title' => 'Bon de Livraison',
                'route' => 'bondelivraison.index',
                'icon' => 'fa-solid fa-file',
                'permission_name' => 'bondelivraison.view',
                'sort_order' => 3,
            ],
            [
                'title' => 'Déploiement',
                'route' => 'deploiement.index',
                'icon' => 'fa-solid fa-box',
                'permission_name' => 'deploiement.view',
                'sort_order' => 4,
            ],
            [
                'title' => 'Matériels',
                'route' => 'gestionmateriel.index',
                'icon' => 'fa-solid fa-laptop',
                'permission_name' => 'gestionmateriel.view',
                'sort_order' => 5,
            ],
            [
                'title' => 'Marques',
                'route' => 'marque.index',
                'icon' => 'fa-solid fa-tag',
                'permission_name' => 'marque.view',
                'sort_order' => 6,
            ],
            [
                'title' => 'Services',
                'route' => 'service.index',
                'icon' => 'fa-solid fa-building',
                'permission_name' => 'service.view',
                'sort_order' => 7,
            ],
            [
                'title' => 'Répartition',
                'route' => 'repartition.index',
                'icon' => 'fa-solid fa-repeat',
                'permission_name' => 'repartition.view',
                'sort_order' => 8,
            ],
            [
                'title' => 'Profil',
                'route' => 'profil.index', // adapte si besoin
                'icon' => 'fa-solid fa-user',
                'permission_name' => 'profil.view',
                'sort_order' => 9,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}