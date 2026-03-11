<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bondelivraison;
use App\Models\LigneDeploiement;
use App\Models\Materiel;
use App\Models\Repartition;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
    {
        /* ===========================
         | 1️⃣ Bons de livraison NON répartis
         ============================*/
        $totalBL = Bondelivraison::doesntHave('repartitions')->count();

        /* ===========================
         | 2️⃣ Répartitions enregistrées
         ============================*/
        $totalRepartitions = Repartition::count();

        /* ===========================
         | 3️⃣ Matériels non déployés
         ============================*/
        $deployedMaterielIds = LigneDeploiement::join(
                'ligne_bondelivraisons',
                'ligne_deploiements.lignebondelivraison_id',
                '=',
                'ligne_bondelivraisons.id'
            )
            ->pluck('ligne_bondelivraisons.materiel_id')
            ->toArray();

        $totalMachinesDeployees = Materiel::whereNotIn('id', $deployedMaterielIds)->count();

        /* ===========================
         | 4️⃣ Totaux par type d'équipement
         ============================*/
        $totaux = [
            'ordinateur_deploye' => Materiel::where('typemateriel_id', 1)
                ->whereIn('id', $deployedMaterielIds)
                ->count(),

            'ordinateur_non_deploye' => Materiel::where('typemateriel_id', 1)
                ->whereNotIn('id', $deployedMaterielIds)
                ->count(),

            'imprimante_deploye' => Materiel::where('typemateriel_id', 2)
                ->whereIn('id', $deployedMaterielIds)
                ->count(),

            'imprimante_non_deploye' => Materiel::where('typemateriel_id', 2)
                ->whereNotIn('id', $deployedMaterielIds)
                ->count(),

            'scanner_deploye' => Materiel::where('typemateriel_id', 3)
                ->whereIn('id', $deployedMaterielIds)
                ->count(),

            'scanner_non_deploye' => Materiel::where('typemateriel_id', 3)
                ->whereNotIn('id', $deployedMaterielIds)
                ->count(),

            'non_deploye_total' => Materiel::whereNotIn('id', $deployedMaterielIds)->count(),
        ];

        /* ===========================
         | 5️⃣ BL EN INSTANCE
         | (même logique que Admin Dashboard)
         ============================*/
        $blEnInstance = Bondelivraison::whereIn(
                'id',
                Repartition::pluck('bondelivraison_id')
            )
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('ligne_bondelivraisons')
                    ->leftJoin(
                        'ligne_deploiements',
                        'ligne_deploiements.lignebondelivraison_id',
                        '=',
                        'ligne_bondelivraisons.id'
                    )
                    ->whereColumn(
                        'ligne_bondelivraisons.bondelivraison_id',
                        'bondelivraisons.id'
                    )
                    ->whereNull('ligne_deploiements.id');
            })
            ->withCount([
                'lignes as total_materiels',
                'lignes as materiels_deployes' => function ($q) {
                    $q->whereIn(
                        'id',
                        LigneDeploiement::pluck('lignebondelivraison_id')
                    );
                }
            ])
            ->get();

        $repartitionsEnInstance = $blEnInstance->count();

        /* ===========================
         | 6️⃣ BL TERMINÉS
         ============================*/
        $blTermines = Bondelivraison::whereIn(
                'id',
                Repartition::pluck('bondelivraison_id')
            )
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('ligne_bondelivraisons')
                    ->leftJoin(
                        'ligne_deploiements',
                        'ligne_deploiements.lignebondelivraison_id',
                        '=',
                        'ligne_bondelivraisons.id'
                    )
                    ->whereColumn(
                        'ligne_bondelivraisons.bondelivraison_id',
                        'bondelivraisons.id'
                    )
                    ->whereNull('ligne_deploiements.id');
            })
            ->count();

        /* ===========================
         | 7️⃣ Retour vue
         ============================*/
        return view('user.dashboard', compact(
            'totalBL',
            'totalRepartitions',
            'totalMachinesDeployees',
            'totaux',
            'blEnInstance',
            'repartitionsEnInstance',
            'blTermines'
        ));
    }
}
