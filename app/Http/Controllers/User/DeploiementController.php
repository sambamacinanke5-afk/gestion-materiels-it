<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Bondelivraison;
use App\Models\Deploiement;
use App\Models\LigneBondelivraison;
use App\Models\LigneDeploiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeploiementController extends Controller
{
    public function index()
    {
        $deploiements = Deploiement::with('bondelivraison')
            ->orderBy('Datecreation', 'desc')
            ->paginate(5);

        return view('user.deploiement.index', compact('deploiements'));
    }

    public function create()
    {
        $bondelivraisons = Bondelivraison::all();

        // 🔴 IDs déjà déployés
        $dejaDeployes = \App\Models\LigneDeploiement::pluck('lignebondelivraison_id');

        // 🟢 Lignes NON encore déployées
        $ligneBondelivraisonsByBL = LigneBondelivraison::with([
            'materiel.marque',
            'materiel.typeMateriel'
        ])
            ->whereNotIn('id', $dejaDeployes) // ⭐ LA LIGNE CLÉ
            ->get()
            ->groupBy('bondelivraison_id')
            ->map(function ($lignes) {
                return $lignes->map(function ($ligne) {
                    return [
                        'id' => $ligne->id,
                        'designation' => $ligne->materiel->designation ?? '',
                        'marque' => $ligne->materiel->marque->libelle ?? '',
                        'typemateriel' => $ligne->materiel->typeMateriel->libelle ?? '',
                        'numero_serie' => $ligne->materiel->numero_serie ?? null,
                    ];
                });
            });

        return view('user.deploiement.create', compact(
            'bondelivraisons',
            'ligneBondelivraisonsByBL'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'Datecreation'       => 'required|date',
            'Direction'          => 'required|string',
            'Utilisateur'        => 'required|string',
            'Poste'              => 'required|string',
            'Systeme'            => 'required|string',
            'Ram'                => 'required|string',
            'Disque'             => 'required|string',
            'Nomordinateur'      => 'required|string',
            'bondelivraison_id'  => 'required|exists:bondelivraisons,id',
            'etat'               => 'required|string',
            'lignes'             => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            $deploiement = Deploiement::create($request->only([
                'Datecreation',
                'Direction',
                'Utilisateur',
                'Poste',
                'Systeme',
                'Ram',
                'Disque',
                'Nomordinateur',
                'bondelivraison_id',
                'etat'
            ]));

            foreach ($request->lignes as $ligne) {
                if (!isset($ligne['lignebondelivraison_id']) || empty($ligne['lignebondelivraison_id'])) continue;

                foreach ($ligne['lignebondelivraison_id'] as $ligneBLId) {
                    LigneDeploiement::create([
                        'deploiement_id' => $deploiement->id,
                        'lignebondelivraison_id' => $ligneBLId
                    ]);
                }
            }
        });

        return redirect()->route('deploiement.index')
            ->with('success', 'Déploiement créé avec succès.');
    }
    public function edit($id)
    {
        $deploiement = Deploiement::with('ligneDeploiements')->findOrFail($id);
        $bondelivraisons = Bondelivraison::all();

        $ligneBondelivraisonsByBL = LigneBondelivraison::with([
            'materiel.marque',
            'materiel.typeMateriel'
        ])
            ->get()
            ->groupBy('bondelivraison_id')
            ->map(function ($lignes) {
                return $lignes->map(function ($ligne) {
                    return [
                        'id' => $ligne->id,
                        'designation' => $ligne->materiel->designation ?? '',
                        'marque' => $ligne->materiel->marque->libelle ?? '',
                        'typemateriel' => $ligne->materiel->typeMateriel->libelle ?? '',
                        'numero_serie' => $ligne->materiel->numero_serie ?? null,
                    ];
                });
            });

        $selectedLignes = $deploiement->ligneDeploiements
            ->pluck('lignebondelivraison_id')
            ->toArray();

        return view('user.deploiement.edit', compact(
            'deploiement',
            'bondelivraisons',
            'ligneBondelivraisonsByBL',
            'selectedLignes'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Datecreation'      => 'required|date',
            'Direction'         => 'required|string',
            'Utilisateur'       => 'required|string',
            'Poste'             => 'required|string',
            'Systeme'           => 'required|string',
            'Ram'               => 'required|string',
            'Disque'            => 'required|string',
            'Nomordinateur'     => 'required|string',
            'bondelivraison_id' => 'required|exists:bondelivraisons,id',
            'etat'              => 'required|string',
            'lignes'            => 'required|array|min:1',
        ]);

        $deploiement = Deploiement::findOrFail($id);

        $deploiement->update($request->only([
            'Datecreation',
            'Direction',
            'Utilisateur',
            'Poste',
            'Systeme',
            'Ram',
            'Disque',
            'Nomordinateur',
            'bondelivraison_id',
            'etat'
        ]));

        // Supprimer les anciennes lignes
        $deploiement->ligneDeploiements()->delete();

        // Ajouter les nouvelles lignes
        foreach ($request->lignes as $ligne) {
            if (!isset($ligne['lignebondelivraison_id']) || empty($ligne['lignebondelivraison_id'])) continue;

            foreach ($ligne['lignebondelivraison_id'] as $ligneBLId) {
                LigneDeploiement::create([
                    'deploiement_id' => $deploiement->id,
                    'lignebondelivraison_id' => $ligneBLId
                ]);
            }
        }

        return redirect()->route('deploiement.index')
            ->with('success', 'Déploiement mis à jour avec succès.');
    }

    public function destroy(Deploiement $deploiement)
    {
        $deploiement->ligneDeploiements()->delete();
        $deploiement->delete();

        return redirect()->route('deploiement.index')
            ->with('success', 'Déploiement supprimé avec succès.');
    }
}
