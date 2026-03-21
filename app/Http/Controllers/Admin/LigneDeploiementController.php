<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LigneDeploiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LigneDeploiementController extends Controller
{
    public function index()
    {
        // Récupération de toutes les machines, déployées ou non
        $materiels = DB::table('materiels')
            ->leftJoin('type_materiels', 'type_materiels.id', '=', 'materiels.typemateriel_id')
            ->leftJoin('marques', 'marques.id', '=', 'materiels.marque_id')
            ->leftJoin('ligne_bondelivraisons', 'ligne_bondelivraisons.materiel_id', '=', 'materiels.id')
            ->leftJoin('bondelivraisons', 'bondelivraisons.id', '=', 'ligne_bondelivraisons.bondelivraison_id')
            ->leftJoin('ligne_deploiements', 'ligne_deploiements.lignebondelivraison_id', '=', 'ligne_bondelivraisons.id')
            ->leftJoin('deploiements', 'deploiements.id', '=', 'ligne_deploiements.deploiement_id')
            ->select(
                'materiels.designation',
                'materiels.numero_serie',
                'type_materiels.Designation as type',
                'marques.Designation as marque',
                'bondelivraisons.bondelivraison',
                DB::raw("COALESCE(deploiements.etat, 'Non déployé') as etat")
            )
            ->get();

        // Totaux par type et état
        $totaux = [
            'ordinateur_deploye' => $materiels->where('type', 'ORDINATEUR COMPLETS')->where('etat', 'Déployée')->count(),
            'ordinateur_non_deploye' => $materiels->where('type', 'ORDINATEUR COMPLETS')->where('etat', 'Non déployé')->count(),
            'imprimante_deploye' => $materiels->where('type', 'IMPRIMANTE')->where('etat', 'Déployée')->count(),
            'imprimante_non_deploye' => $materiels->where('type', 'IMPRIMANTE')->where('etat', 'Non déployé')->count(),
            'scanner_deploye' => $materiels->where('type', 'SCANNER')->where('etat', 'Déployée')->count(),
            'scanner_non_deploye' => $materiels->where('type', 'SCANNER')->where('etat', 'Non déployé')->count(),
            'non_deploye_total' => $materiels->where('etat', 'Non déployé')->count(),
        ];

        return view('admin.gestiondeploiement.index', [
            'materiels' => $materiels,
            'totaux' => $totaux
        ]);
    }




}
