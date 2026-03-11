<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\Repartition;
use Illuminate\Http\Request;
use App\Models\Bondelivraison;
use App\Models\LigneRepartition;
use Illuminate\Support\Facades\DB;
use App\Models\LigneBondelivraison;
use App\Http\Controllers\Controller;
use App\Models\Materiel;
use App\Models\Notification;

class RepartitionController extends Controller
{
    /**
     * 🔹 Liste des répartitions
     */
    public function index()
    {
        $repartitions = Repartition::with(['bondelivraison', 'lignes.service'])
            ->orderByDesc('date_repartition')
            ->get();

        return view('admin.repartition.index', compact('repartitions'));
    }


    /**
     * 🔹 Formulaire de création
     */
    public function create()
    {
        // BL récents
        $bondelivraisons = BonDeLivraison::orderByDesc('id')->get();

        // Services
        $services = Service::all();

        // Matériels classés par BL
        $materielsByBL = [];

        foreach ($bondelivraisons as $bon) {

            // Charger lignes BL + matériel + marque + type
            $lignesBL = LigneBondelivraison::where('bondelivraison_id', $bon->id)
                ->with([
                    'materiel',
                    'materiel.marque',
                    'materiel.typemateriel'
                ])
                ->get();

            $materielsByBL[$bon->id] = $lignesBL->map(function ($ligne) {

                $m = $ligne->materiel;

                return [
                    'ligneBL_id'   => $ligne->id,
                    'id'           => $m->id,
                    'designation'  => $m->designation ?? 'N/A',
                    'marque'       => $m->marque->Designation ?? 'N/A',
                    'typemateriel' => $m->typemateriel->Designation ?? 'N/A',
                    'numero_serie' => $m->numero_serie ?? 'N/A',
                    'quantite'     => $ligne->quantite ?? 1
                ];
            });
        }

        return view('admin.repartition.create', compact(
            'bondelivraisons',
            'services',
            'materielsByBL'
        ));
    }
    /**
     * 🔹 Enregistrer une nouvelle répartition
     */
    // ======================
    // STORE
    // ======================
    public function store(Request $request)
    {
        // Création de la répartition principale
        $repartition = Repartition::create([
            'bondelivraison_id' => $request->bondelivraison_id,
            'date_repartition' => $request->date_repartition,
            'ordinateur_complets' => $request->ordinateur_complets,
            'ordinateur_portables' => $request->ordinateur_portables,
            'imprimantes' => $request->imprimantes,
            'scanners' => $request->scanners,
        ]);

        // LIGNES DE REPARTITION
        $counter = 1;

        foreach ($request->lignes as $ligne) {

            LigneRepartition::create([
                'repartition_id' => $repartition->id,
                'num_ligne'      => $counter++,
                'service_id'     => $ligne['service_id'],
                'destinataire'   => $ligne['destinataire'] ?? null,
                'quantite'       => $ligne['quantite'] ?? 0,
                'lignebondelivraison_id' => json_encode($ligne['materiel']),
            ]);
        }

        return redirect()->route('repartition.index')
            ->with('success', 'Répartition créée avec succès.');
    }




    public function edit($id)
    {
        $repartition = Repartition::with(['lignes', 'bondelivraison.fournisseur'])->findOrFail($id);

        $bondelivraisons = BonDeLivraison::orderByDesc('id')->get();
        $services = Service::all();

        // Préparer les matériels disponibles par BL
        $materielsByBL = [];
        foreach ($bondelivraisons as $bon) {
            $lignesBL = LigneBondelivraison::where('bondelivraison_id', $bon->id)
                ->with(['materiel', 'materiel.marque', 'materiel.typemateriel'])
                ->get();

            $materielsByBL[$bon->id] = $lignesBL->map(function ($ligne) {
                $m = $ligne->materiel;
                return [
                    'ligneBL_id'   => $ligne->id,
                    'id'           => $m->id,
                    'designation'  => $m->designation ?? 'N/A',
                    'marque'       => $m->marque->Designation ?? 'N/A',
                    'typemateriel' => $m->typemateriel->Designation ?? 'N/A',
                    'numero_serie' => $m->numero_serie ?? 'N/A',
                    'quantite'     => $ligne->quantite ?? 1,
                ];
            });
        }

        return view('admin.repartition.edit', compact('repartition', 'bondelivraisons', 'services', 'materielsByBL'));
    }

    /**
     * 🔹 Mettre à jour une répartition
     */
    public function update(Request $request, $id)
    {
        // Récupération de la répartition
        $repartition = Repartition::findOrFail($id);

        // Mise à jour des champs principaux
        $repartition->update([
            'bondelivraison_id' => $request->bondelivraison_id,
            'date_repartition'   => $request->date_repartition,

            'ordinateur_complets'   => $request->ordinateur_complets,
            'ordinateur_portables'  => $request->ordinateur_portables,
            'imprimantes'           => $request->imprimantes,
            'scanners'              => $request->scanners,
        ]);

        // Suppression des anciennes lignes
        $repartition->lignes()->delete();

        // Recréation des lignes
        $counter = 1; // Numérotation automatique

        foreach ($request->lignes as $ligne) {

            LigneRepartition::create([
                'repartition_id' => $repartition->id,
                'num_ligne'      => $counter++,
                'service_id'     => $ligne['service_id'],
                'destinataire'   => $ligne['destinataire'] ?? null,
                'quantite'       => $ligne['quantite'] ?? 0,

                // Matériels sélectionnés dans les checkbox du front
                'lignebondelivraison_id' => json_encode($ligne['materiel']),
            ]);
        }

        return redirect()->route('repartition.index')
            ->with('success', 'Répartition mise à jour avec succès !');
    }
    public function show($id)
    {
        // Charger la répartition avec le bon de livraison et le fournisseur, et les lignes
        $repartition = Repartition::with([
            'bondelivraison.fournisseur', // info BL et fournisseur
            'lignes.service',             // info service pour chaque ligne
        ])->findOrFail($id);

        $lignes = $repartition->lignes;

        foreach ($lignes as $ligne) {
            // Transformer lignebondelivraison_id en tableau si nécessaire
            if (!is_array($ligne->lignebondelivraison_id)) {
                $ligne->lignebondelivraison_id = is_string($ligne->lignebondelivraison_id)
                    ? json_decode($ligne->lignebondelivraison_id, true) ?: []
                    : (array) $ligne->lignebondelivraison_id;
            }

            // Récupérer les lignes de bon de livraison associées
            $ligne->ligneBLs = \App\Models\LigneBondelivraison::with([
                'materiel.marque',
                'materiel.typemateriel',
            ])->whereIn('id', $ligne->lignebondelivraison_id)->get();
        }

        return view('admin.repartition.show', compact('repartition', 'lignes'));
    }
    /**
     * 🔹 Supprimer une répartition
     */
    /**
     * 🔹 Supprimer une répartition
     */
    public function destroy($id)
    {
        try {
            // Récupérer la répartition
            $repartition = Repartition::with('lignes')->findOrFail($id);

            // Supprimer les lignes associées
            $repartition->lignes()->delete();

            // Supprimer la répartition principale
            $repartition->delete();

            return redirect()->route('repartition.index')
                ->with('success', 'Répartition et ses lignes ont été supprimées avec succès.');
        } catch (\Exception $e) {
            // Gestion d'erreur
            return redirect()->route('repartition.index')
                ->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }
    //la notification
    public function asRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = now();
        $notification->save();

        return route('repartition.index');
    }
}
