<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Bondelivraison;
use App\Models\Repartition;
use App\Models\Deploiement;
use App\Models\LigneBondelivraison;

class PdfController extends Controller
{
    // Fonction globale pour nettoyer les noms de fichiers
    private function safeFilename($name)
    {
        return preg_replace('/[\/\\\\:*?"<>|]/', '-', $name);
    }

    // ================= BL =================
    public function bonLivraison($id)
    {
        $bondelivraison = Bondelivraison::with(
            'lignes.materiel.typemateriel',
            'lignes.materiel.marque',
            'fournisseur'
        )->findOrFail($id);

        $pdf = Pdf::loadView('PDF.Bondelivraison', compact('bondelivraison'))
            ->setPaper('A4', 'portrait');

        $filename = $this->safeFilename('BL_' . $bondelivraison->bondelivraison . '.pdf');

        return $pdf->stream($filename);
    }

    // ================= RÉPARTITION =================
    public function repartition($id)
    {
        $repartition = Repartition::with([
            'bondelivraison.fournisseur',
            'lignes.service',
        ])->findOrFail($id);

        foreach ($repartition->lignes as $ligne) {
            $lbIds = json_decode($ligne->lignebondelivraison_id, true) ?? [];

            $ligne->materielsBL = LigneBondelivraison::with([
                'materiel',
                'materiel.marque',
                'materiel.typemateriel'
            ])->whereIn('id', $lbIds)->get();
        }

        $pdf = Pdf::loadView('pdf.repartition', compact('repartition'))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans');

        $filename = $this->safeFilename('repartition_materiels.pdf');

        return $pdf->stream($filename);
    }

    // ================= DÉPLOIEMENT =================
    public function deploiement($id)
    {
        $deploiement = Deploiement::with([
            'lignes.materiel',
            'utilisateur'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.deploiement', compact('deploiement'))
            ->setPaper('A4', 'portrait');

        $filename = $this->safeFilename('Deploiement_' . $deploiement->id . '.pdf');

        return $pdf->stream($filename);
    }
}
