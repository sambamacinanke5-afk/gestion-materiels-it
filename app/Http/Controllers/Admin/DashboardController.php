<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bondelivraison;
use App\Models\Materiel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        /* =========================
         | 1️⃣ BL NON RÉPARTIS
         ==========================*/
        $totalBL = Bondelivraison::whereDoesntHave('repartitions')->count();

        /* =========================
         | 2️⃣ RÉPARTITIONS EN INSTANCE
         ==========================*/
        $repartitionsEnInstance = Bondelivraison::whereHas('repartitions')
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
            ->count();

        /* =========================
         | 3️⃣ MATÉRIEL DÉPLOYÉ / NON
         ==========================*/
        $materielsDeployesIds = DB::table('ligne_deploiements')
            ->join(
                'ligne_bondelivraisons',
                'ligne_deploiements.lignebondelivraison_id',
                '=',
                'ligne_bondelivraisons.id'
            )
            ->pluck('ligne_bondelivraisons.materiel_id');

        $totalMachinesDeployees = Materiel::whereNotIn('id', $materielsDeployesIds)->count();

        /* =========================
         | 4️⃣ TOTAUX PAR TYPE
         ==========================*/
        $totaux = [
            'ordinateur_deploye' => Materiel::where('typemateriel_id', 1)
                ->whereIn('id', $materielsDeployesIds)
                ->count(),
            'ordinateur_non_deploye' => Materiel::where('typemateriel_id', 1)
                ->whereNotIn('id', $materielsDeployesIds)
                ->count(),

            'imprimante_deploye' => Materiel::where('typemateriel_id', 2)
                ->whereIn('id', $materielsDeployesIds)
                ->count(),
            'imprimante_non_deploye' => Materiel::where('typemateriel_id', 2)
                ->whereNotIn('id', $materielsDeployesIds)
                ->count(),

            'scanner_deploye' => Materiel::where('typemateriel_id', 3)
                ->whereIn('id', $materielsDeployesIds)
                ->count(),
            'scanner_non_deploye' => Materiel::where('typemateriel_id', 3)
                ->whereNotIn('id', $materielsDeployesIds)
                ->count(),

            'non_deploye_total' => Materiel::whereNotIn('id', $materielsDeployesIds)->count(),
        ];

        /* =========================
         | 5️⃣ LISTE BL EN INSTANCE
         ==========================*/
        $blEnInstance = Bondelivraison::whereHas('repartitions')
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
                        DB::table('ligne_deploiements')
                            ->pluck('lignebondelivraison_id')
                    );
                }
            ])
            ->get();

        /* =========================
         | 6️⃣ VIEW
         ==========================*/
        return view('admin.dashboard', compact(
            'totalBL',
            'repartitionsEnInstance',
            'totalMachinesDeployees',
            'totaux',
            'blEnInstance'
        ));
    }
}
