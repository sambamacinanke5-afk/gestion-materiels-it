<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Redirection automatique selon le rôle
     */
    public function redirect(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Utilisateur non authentifié.');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->hasRole('it')) {
            return redirect()->route('dashboard.it');
        }

        if ($user->hasRole('mg')) {
            return redirect()->route('dashboard.mg');
        }

        if ($user->hasRole('audit')) {
            return redirect()->route('dashboard.audit');
        }

        abort(403, 'Aucun dashboard attribué à ce rôle.');
    }

    /**
     * Dashboard IT
     */
    public function it(Request $request): View
    {
        $period = $request->get('period');
        $data = $this->dashboardService->getItDashboardData($period);

        return view('dashboard.it', [
            'pageTitle' => 'Tableau de bord IT',
            'period' => $period,
            ...$data,
        ]);
    }

    /**
     * Dashboard Moyens Généraux (Logistique)
     */
    public function mg(Request $request): View
    {
        $period = $request->get('period');
        $data = $this->dashboardService->getMgDashboardData($period);

        return view('dashboard.mg', [
            'pageTitle' => 'Tableau de bord Logistique',
            'period' => $period,
            ...$data,
        ]);
    }

    /**
     * Dashboard Administrateur
     */
    public function admin(Request $request): View
    {
        $period = $request->get('period');
        $data = $this->dashboardService->getAdminDashboardData($period);

        return view('dashboard.admin', [
            'pageTitle' => 'Tableau de bord Administrateur',
            'period' => $period,
            ...$data,
        ]);
    }

    /**
     * Dashboard Audit & Reporting
     */
    public function audit(Request $request): View
    {
        $period = $request->get('period');
        $data = $this->dashboardService->getAuditDashboardData($period);

        return view('dashboard.audit', [
            'pageTitle' => 'Tableau de bord Audit & Reporting',
            'period' => $period,
            ...$data,
        ]);
    }
}