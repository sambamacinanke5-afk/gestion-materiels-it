<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Bondelivraison;
use App\Models\Repartition;
use App\Models\Deploiement;
use App\Models\LigneBondelivraison;

class PdfController extends Controller
{
    // ================= BL =================


    public function bonLivraison($id)
    {
        $bondelivraison = Bondelivraison::with('lignes.materiel.typemateriel', 'lignes.materiel.marque', 'fournisseur')->findOrFail($id);

        $pdf = Pdf::loadView('PDF.Bondelivraison', compact('bondelivraison'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('BL_' . $bondelivraison->bondelivraison . '.pdf');
    }



    // ================= RÉPARTITION =================
    public function repartition($id)
    {
        // Récupérer la répartition avec le bon de livraison et les lignes
        $repartition = Repartition::with([
            'bondelivraison.fournisseur', // Fournisseur pour le PDF
            'lignes.service',             // Service lié à chaque ligne
        ])->findOrFail($id);

        // Pour chaque ligne, charger les matériels liés via les IDs stockés en JSON
        foreach ($repartition->lignes as $ligne) {
            $lbIds = json_decode($ligne->lignebondelivraison_id, true) ?? [];
            $ligne->materielsBL = LigneBondelivraison::with([
                'materiel',
                'materiel.marque',
                'materiel.typemateriel'
            ])->whereIn('id', $lbIds)->get();
        }

        // Génération du PDF
        $pdf = Pdf::loadView('pdf.repartition', compact('repartition'))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans');

        // Retourne le PDF en streaming
        return $pdf->stream('repartition_materiels.pdf');
    }




    // ================= DÉPLOIEMENT =================
    public function deploiement($id)
    {
        $deploiement = Deploiement::with([
            'lignes.materiel', // ici 'lignes()' doit exister dans Deploiement
            'utilisateur'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.deploiement', compact('deploiement'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Deploiement_' . $deploiement->id . '.pdf');
    }
}
