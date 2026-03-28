<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deploiement;
use App\Models\Bondelivraison;
use App\Models\User;
use App\Models\Materiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeploiementController extends Controller
{
    /**
     * 🔹 Liste des déploiements
     */
    public function index()
    {
        // Avec relations pour affichage
        $deploiements = Deploiement::with([
            'bondelivraison.fournisseur',
            'materiel',
            'utilisateur'
        ])->orderByDesc('date_deploiement')->paginate(10);

        return view('admin.deploiement.index', compact('deploiements'));
    }

    /**
     * 🔹 Formulaire de création
     */
    public function create()
    {
        $bondelivraisons = Bondelivraison::with('lignes.materiel')->get();

        $users = User::orderBy('name')->get();

        // 🔹 Construire tableau des matériels par BL
        $materielsByBL = [];

        foreach ($bondelivraisons as $bon) {
            $materielsByBL[$bon->id] = $bon->lignes->map(function ($ligne) {
                return [
                    'id' => $ligne->materiel->id ?? null,
                    'designation' => $ligne->materiel->designation ?? 'Non défini',
                    'numero_serie' => $ligne->materiel->numero_serie ?? 'N/A',
                ];
            });
        }

        return view('admin.deploiement.create', compact(
            'bondelivraisons',
            'users',
            'materielsByBL'
        ));
    }

    /**
     * 🔹 Stockage d'un déploiement
     */
    public function store(Request $request)
    {
        $request->validate([
            'bondelivraison_id' => 'required|exists:bons_livraison,id',
            'materiel_id' => 'required|exists:materiels,id',
            'beneficiaire_id' => 'required|exists:users,id',
            'technicien_id' => 'nullable|exists:users,id',
            'date_deploiement' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        Deploiement::create($request->all());

        return redirect()->route('deploiement.index')
            ->with('success', 'Déploiement créé avec succès.');
    }

    /**
     * 🔹 Affichage d'un déploiement
     */
    public function show($id)
    {
        $deploiement = Deploiement::with([
            'bondelivraison.fournisseur',
            'materiel',
            'utilisateur',
            'ligneDeploiements'
        ])->findOrFail($id);

        return view('admin.deploiement.show', compact('deploiement'));
    }

    /**
     * 🔹 Formulaire d'édition
     */
    public function edit($id)
    {
        $deploiement = Deploiement::findOrFail($id);
        $bonsLivraison = Bondelivraison::orderByDesc('date_livraison')->get();
        $users = User::orderBy('name')->get();

        return view('admin.deploiement.edit', compact('deploiement', 'bonsLivraison', 'users'));
    }

    /**
     * 🔹 Mise à jour
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bondelivraison_id' => 'required|exists:bons_livraison,id',
            'materiel_id' => 'required|exists:materiels,id',
            'beneficiaire_id' => 'required|exists:users,id',
            'technicien_id' => 'nullable|exists:users,id',
            'date_deploiement' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $deploiement = Deploiement::findOrFail($id);
        $deploiement->update($request->all());

        return redirect()->route('deploiement.index')
            ->with('success', 'Déploiement mis à jour avec succès.');
    }

    /**
     * 🔹 Suppression
     */
    public function destroy($id)
    {
        $deploiement = Deploiement::findOrFail($id);
        $deploiement->delete();

        return redirect()->route('deploiement.index')
            ->with('success', 'Déploiement supprimé avec succès.');
    }
}
