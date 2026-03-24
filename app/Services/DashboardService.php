<?php

namespace App\Services;

use App\Models\Materiel;
use App\Models\MouvementMateriel;
use App\Models\Repartition;
use App\Models\RepartitionLigne;
use App\Models\Site;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
     public function getItDashboardData(?string $period = null): array
    {
        [$startDate, $endDate] = $this->resolvePeriod($period);

        return [
            'kpis' => [
                'materiels_recus' => $this->countMaterielsByStatut('recu', $startDate, $endDate),
                'a_valider_it' => $this->countMaterielsByStatut('recu', $startDate, $endDate),
                'a_repartir_mg' => $this->countMaterielsByStatut('valide_it', $startDate, $endDate),
                'a_deployer_it' => $this->countMaterielsByStatut('reparti', $startDate, $endDate),
            ],

            'actions_urgentes' => [
                'validation_it' => Materiel::where('statut', 'recu')
                    ->where('created_at', '<=', now()->subHours(24))
                    ->count(),

                'non_repartis' => Materiel::where('statut', 'valide_it')
                    ->where('updated_at', '<=', now()->subHours(24))
                    ->count(),

                'non_deployes_48h' => Materiel::where('statut', 'reparti')
                    ->where('updated_at', '<=', now()->subHours(48))
                    ->count(),
            ],

            'flux' => [
                'reception' => $this->countMaterielsByStatut('recu', $startDate, $endDate),
                'validation' => $this->countMaterielsByStatut('valide_it', $startDate, $endDate),
                'repartition' => $this->countMaterielsByStatut('reparti', $startDate, $endDate),
                'deploiement' => Materiel::whereIn('statut', ['deploye', 'installe'])
                    ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                    ->count(),
            ],

            'stats_materiel' => [
                'ordinateurs' => $this->countByTypeName(['ordinateur', 'ordinateurs', 'pc']),
                'imprimantes' => $this->countByTypeName(['imprimante', 'imprimantes']),
                'scanners' => $this->countByTypeName(['scanner', 'scanners']),
                'autres' => $this->countAutresTypes(),
                'installe' => Materiel::where('statut', 'installe')->count(),
                'en_panne' => Materiel::where('statut', 'en_panne')->count(),
                'en_maintenance' => Materiel::where('statut', 'en_maintenance')->count(),
            ],

            'activites_recentes' => class_exists(MouvementMateriel::class)
                ? MouvementMateriel::with(['materiel', 'utilisateur'])->latest()->take(5)->get()
                : collect(),
        ];
    }




    private function countByTypeName(array $names): int
    {
        return Materiel::whereHas('typeMateriel', function ($q) use ($names) {
            $q->where(function ($query) use ($names) {
                foreach ($names as $name) {
                    $query->orWhereRaw('LOWER(Designation) = ?', [mb_strtolower($name)]);
                }
            });
        })->count();
    }

    private function countAutresTypes(): int
    {
        return Materiel::whereDoesntHave('typeMateriel', function ($q) {
            $q->whereIn('Designation', ['Ordinateur', 'ordinateur', 'PC', 'Imprimante', 'Scanner']);
        })->count();
    }

    public function getMgDashboardData(?string $period = null): array
    {
        [$startDate, $endDate] = $this->resolvePeriod($period);

        return [
            'kpis' => [
                'materiels_valides_it' => $this->countMaterielsByStatut('valide_it', $startDate, $endDate),
                'a_repartir_mg' => $this->countMaterielsByStatut('valide_it', $startDate, $endDate),
                'repartitions_en_cours' => Repartition::whereIn('statut', ['brouillon', 'en_cours', 'validee'])->count(),
                'repartitions_livrees' => Repartition::where('statut', 'livree')->count(),
            ],

            'actions_urgentes' => [
                'non_repartis' => Materiel::where('statut', 'valide_it')
                    ->where('updated_at', '<=', now()->subHours(24))
                    ->count(),

                'attente_livraison' => Repartition::whereIn('statut', ['validee', 'en_cours'])->count(),

                'livraisons_en_retard' => Repartition::whereIn('statut', ['validee', 'en_cours'])
                    ->where('updated_at', '<=', now()->subHours(48))
                    ->count(),
            ],

            'flux' => [
                'validation_it' => $this->countMaterielsByStatut('valide_it', $startDate, $endDate),
                'repartition' => RepartitionLigne::where('statut', 'a_repartir')->count(),
                'livraison' => Repartition::where('statut', 'livree')->count(),
            ],

            'materiels_a_repartir' => Materiel::select('categorie_id', DB::raw('COUNT(*) as total'))
                ->with('categorie:id,nom,code')
                ->where('statut', 'valide_it')
                ->groupBy('categorie_id')
                ->orderByDesc('total')
                ->get(),

            'repartition_par_site' => Site::select('sites.id', 'sites.nom', DB::raw('COUNT(repartitions.id) as total'))
                ->leftJoin('repartitions', 'repartitions.site_destination_id', '=', 'sites.id')
                ->groupBy('sites.id', 'sites.nom')
                ->orderByDesc('total')
                ->take(10)
                ->get(),

            'activites_recentes' => $this->getRecentActivities(['repartition', 'livraison'], 5),
        ];
    }

    public function getAdminDashboardData(?string $period = null): array
    {
        [$startDate, $endDate] = $this->resolvePeriod($period);

        return [
            'kpis' => [
                'materiels_totaux' => Materiel::count(),
                'en_service' => Materiel::where('statut', 'en_service')->count(),
                'en_panne' => Materiel::where('statut', 'en_panne')->count(),
                'repartitions_livrees' => Repartition::where('statut', 'livree')->count(),
            ],

            'synthese_globale' => [
                'non_repartis' => Materiel::where('statut', 'valide_it')->count(),
                'attente_livraison' => Repartition::whereIn('statut', ['validee', 'en_cours'])->count(),
                'retards' => Repartition::whereIn('statut', ['validee', 'en_cours'])
                    ->where('updated_at', '<=', now()->subHours(48))
                    ->count(),
            ],

            'flux' => [
                'validation_it' => $this->countMaterielsByStatut('valide_it', $startDate, $endDate),
                'repartition' => $this->countMaterielsByStatut('reparti', $startDate, $endDate),
                'livraison' => Repartition::where('statut', 'livree')->count(),
            ],

            'par_categorie' => Materiel::select('categorie_id', DB::raw('COUNT(*) as total'))
                ->with('categorie:id,nom,code')
                ->groupBy('categorie_id')
                ->orderByDesc('total')
                ->get(),

            'par_statut' => Materiel::select('statut', DB::raw('COUNT(*) as total'))
                ->groupBy('statut')
                ->orderByDesc('total')
                ->get(),

            'activites_recentes' => $this->getRecentActivities([], 5),

            'utilisateurs_roles' => [
                'total_utilisateurs' => User::count(),
                'admins' => method_exists(User::class, 'role') ? User::role('admin')->count() : 0,
                'it' => method_exists(User::class, 'role') ? User::role('it')->count() : 0,
                'mg' => method_exists(User::class, 'role') ? User::role('mg')->count() : 0,
                'audit' => method_exists(User::class, 'role') ? User::role('audit')->count() : 0,
            ],
        ];
    }

    public function getAuditDashboardData(?string $period = null): array
    {
        [$startDate, $endDate] = $this->resolvePeriod($period);
        $today = Carbon::today();

        return [
            'kpis' => [
                'mouvements_aujourdhui' => MouvementMateriel::whereDate('created_at', $today)->count(),
                'alertes_critiques' => Materiel::whereIn('statut', ['en_panne', 'reforme'])->count(),
                'historique_total' => MouvementMateriel::count(),
            ],

            'journal_mouvements' => MouvementMateriel::with(['materiel', 'utilisateur'])
                ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                ->latest()
                ->take(10)
                ->get(),

            'alertes_anomalies' => [
                'materiels_en_panne' => Materiel::with(['categorie', 'marque'])
                    ->where('statut', 'en_panne')
                    ->latest()
                    ->take(5)
                    ->get(),

                'repartitions_non_livrees' => Repartition::with(['siteDestination', 'serviceDestination'])
                    ->whereIn('statut', ['validee', 'en_cours'])
                    ->where('updated_at', '<=', now()->subHours(48))
                    ->latest()
                    ->take(5)
                    ->get(),
            ],

            'rapports' => [
                'inventaire_complet' => Materiel::count(),
                'historique_mouvements' => MouvementMateriel::count(),
                'audit_utilisateurs' => User::count(),
            ],
        ];
    }

    private function resolvePeriod(?string $period = null): array
    {
        return match ($period) {
            'today' => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
            '7d' => [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endOfDay()],
            '30d' => [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endOfDay()],
            default => [null, null],
        };
    }

    private function countMaterielsByStatut(string $statut, $startDate = null, $endDate = null): int
    {
        return Materiel::where('statut', $statut)
            ->when($startDate && $endDate, fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
            ->count();
    }

    private function countByCategorieCode(string $code): int
    {
        return Materiel::whereHas('categorie', function ($q) use ($code) {
            $q->where('code', $code);
        })->count();
    }

    private function getRecentActivities(array $types = [], int $limit = 5): Collection
    {
        return MouvementMateriel::with(['materiel', 'utilisateur'])
            ->when(! empty($types), fn ($q) => $q->whereIn('type_mouvement', $types))
            ->latest()
            ->take($limit)
            ->get();
    }
}