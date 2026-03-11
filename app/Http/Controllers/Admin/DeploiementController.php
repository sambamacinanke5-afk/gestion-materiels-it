<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Bondelivraison;
use App\Models\Deploiement;
use App\Models\LigneBondelivraison;
use App\Models\LigneDeploiement;
use App\Models\Repartition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeploiementController extends Controller
{
    public function index()
    {
        $deploiements = Deploiement::with('bondelivraison')
            ->orderBy('Datecreation', 'desc')
            ->paginate(10);

        return view('admin.deploiement.index', compact('deploiements'));
    }

    public function create()
    {
        /**
         * 🔹 1. BL autorisés = BL qui ont été répartis
         * (la répartition est la condition obligatoire)
         */
        $blRepartisIds = Repartition::pluck('bondelivraison_id')
            ->unique()
            ->toArray();

        /**
         * 🔹 2. Lignes déjà déployées
         * (pour les exclure de l'affichage)
         */
        $lignesDejaDeployees = LigneDeploiement::pluck('lignebondelivraison_id')
            ->toArray();

        /**
         * 🔹 3. Charger uniquement :
         * - les BL répartis
         * - avec au moins 1 matériel non encore déployé
         */
        $bondelivraisons = Bondelivraison::with([
            'lignes' => function ($query) use ($lignesDejaDeployees) {
                $query->whereNotIn('id', $lignesDejaDeployees)
                    ->with([
                        'materiel.marque',
                        'materiel.typemateriel'
                    ]);
            }
        ])
            ->whereIn('id', $blRepartisIds) // ✅ seulement BL répartis
            ->get()
            ->filter(fn($bon) => $bon->lignes->isNotEmpty()) // ✅ garder BL utiles
            ->values();

        /**
         * 🔹 4. Préparer les matériels par BL pour le JS
         */
        $materielsByBL = [];

        foreach ($bondelivraisons as $bon) {
            $materielsByBL[$bon->id] = $bon->lignes->map(function ($ligne) {
                return [
                    'ligne_bon_livraison_id' => $ligne->id,
                    'materiel_id' => $ligne->materiel?->id,
                    'designation' => $ligne->materiel?->designation ?? 'N/A',
                    'marque' => $ligne->materiel?->marque?->Designation ?? 'N/A',
                    'typemateriel' => $ligne->materiel?->typemateriel?->Designation ?? 'N/A',
                    'numero_serie' => $ligne->materiel?->numero_serie ?? 'N/A',
                ];
            });
        }

        /**
         * 🔹 5. Retour vue
         */
        return view('admin.deploiement.create', [
            'bondelivraisons' => $bondelivraisons,
            'materielsByBL' => $materielsByBL,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'bondelivraison_id' => 'required|exists:bondelivraisons,id',
            'Datecreation' => 'required|date',
            'Direction' => 'required|string',
            'Poste' => 'required|string',
            'Utilisateur' => 'required|string',
            'etat' => 'required|in:deployee',
            'lignebondelivraison_id' => 'required|exists:ligne_bondelivraisons,id',
        ]);

        DB::transaction(function () use ($request) {

            $deploiement = Deploiement::create([
                'bondelivraison_id' => $request->bondelivraison_id,
                'Datecreation' => $request->Datecreation,
                'Direction' => $request->Direction,
                'Poste' => $request->Poste,
                'Utilisateur' => $request->Utilisateur,
                'Nomordinateur' => $request->Nomordinateur,
                'Systeme' => $request->Systeme,
                'Ram' => $request->Ram,
                'Disque' => $request->Disque,
                'etat' => $request->etat,
            ]);

            // 🔹 UNE seule ligne = UN matériel
            LigneDeploiement::create([
                'deploiement_id' => $deploiement->id,
                'lignebondelivraison_id' => $request->lignebondelivraison_id,
            ]);
        });


        return redirect()->route('deploiement.index')
            ->with('success', 'Déploiement créé avec succès');
    }



    public function edit($id)
    {
        $deploiement = Deploiement::with('ligneDeploiements')->findOrFail($id);

        $bondelivraisons = Bondelivraison::orderBy('id', 'DESC')->get();

        // Matériel déjà utilisé ailleurs
        $dejaDeployes = LigneDeploiement::where('deploiement_id', '!=', $id)
            ->pluck('lignebondelivraison_id');

        $lignesBL = LigneBondelivraison::with([
            'materiel.marque',
            'materiel.typemateriel'
        ])
            ->whereNotIn('id', $dejaDeployes)
            ->get();

        $materielsByBL = [];

        foreach ($bondelivraisons as $bon) {
            $lignes = $lignesBL
                ->where('bondelivraison_id', $bon->id)
                ->values();

            if ($lignes->isEmpty())
                continue;

            $materielsByBL[$bon->id] = $lignes->map(function ($ligne) {
                $materiel = $ligne->materiel;

                return [
                    'ligne_bon_livraison_id' => $ligne->id,
                    'designation' => $materiel->designation ?? 'N/A',
                    'marque' => optional($materiel->marque)->Designation ?? 'N/A',
                    'typemateriel' => optional($materiel->typemateriel)->Designation ?? 'N/A',
                    'numero_serie' => $materiel->numero_serie ?? 'N/A',
                ];
            });
        }

        $selectedLigne = optional(
            $deploiement->ligneDeploiements->first()
        )->lignebondelivraison_id;

        return view('admin.deploiement.edit', compact(
            'deploiement',
            'bondelivraisons',
            'materielsByBL',
            'selectedLigne'
        ));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'bondelivraison_id' => 'required|exists:bondelivraisons,id',
            'Datecreation' => 'required|date',
            'Direction' => 'required|string',
            'Poste' => 'required|string',
            'Utilisateur' => 'required|string',
            'etat' => 'required|in:non_deploye,en_cours,deployee,hors_service',

            // ⬇️ FACULTATIFS
            'Nomordinateur' => 'nullable|string',
            'Systeme' => 'nullable|string',
            'Ram' => 'nullable|string',
            'Disque' => 'nullable|string',

            // ⬇️ MATÉRIEL (optionnel en update)
            'lignebondelivraison_id' => 'nullable|exists:ligne_bondelivraisons,id',
        ]);

        DB::transaction(function () use ($request, $id) {

            $deploiement = Deploiement::findOrFail($id);

            // 🔹 Mise à jour du déploiement
            $deploiement->update([
                'bondelivraison_id' => $request->bondelivraison_id,
                'Datecreation' => $request->Datecreation,
                'Direction' => $request->Direction,
                'Poste' => $request->Poste,
                'Utilisateur' => $request->Utilisateur,
                'Nomordinateur' => $request->Nomordinateur,
                'Systeme' => $request->Systeme,
                'Ram' => $request->Ram,
                'Disque' => $request->Disque,
                'etat' => $request->etat,
            ]);

            // 🔹 Mise à jour du matériel SI fourni
            if ($request->filled('lignebondelivraison_id')) {

                // Supprimer les anciennes lignes
                LigneDeploiement::where('deploiement_id', $deploiement->id)->delete();

                // Créer la nouvelle affectation
                LigneDeploiement::create([
                    'deploiement_id' => $deploiement->id,
                    'lignebondelivraison_id' => $request->lignebondelivraison_id,
                ]);
            }
        });

        return redirect()
            ->route('deploiement.index')
            ->with('success', 'Déploiement mis à jour avec succès');
    }


    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            // 🔥 SUPPRESSION FORCÉE DES LIGNES
            DB::table('ligne_deploiements')
                ->where('deploiement_id', $id)
                ->delete();

            // 🔥 SUPPRESSION DU DÉPLOIEMENT
            Deploiement::where('id', $id)->delete();
        });

        return redirect()
            ->route('deploiement.index')
            ->with('success', 'Déploiement supprimé définitivement.');
    }
    public function show($id)
    {
        // Récupère le déploiement avec ses lignes associées
        $deploiement = \App\Models\Deploiement::with('ligneDeploiements.lignebondelivraison')->findOrFail($id);

        // Retourne la vue show avec le déploiement
        return view('admin.deploiement.show', compact('deploiement'));
    }


}
