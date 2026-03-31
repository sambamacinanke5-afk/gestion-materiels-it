<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bondelivraison;
use App\Models\Fournisseur;
use App\Models\Marque;
use App\Models\TypeMateriel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BondelivraisonController extends Controller
{
    /**
     * Liste des bons de livraison
     */
   public function index()
{
    // 🔹 Récupère les bons de livraison avec fournisseur, paginés
    $bonsLivraison = Bondelivraison::with('fournisseur')
        ->orderByDesc('date_livraison')
        ->paginate(10); // ne pas faire ->get()->toArray()

    return view('admin.bondelivraison.index', compact('bonsLivraison'));
}

    /**
     * Formulaire création BL
     */
    public function create()
{
    // Récupération des fournisseurs, types et marques
    $fournisseurs = Fournisseur::orderBy('nom')->get();
    $typemateriels = TypeMateriel::orderBy('nom')->get();
    $marques = Marque::orderBy('Designation')->get();

    // Passer les variables à la vue
    return view('admin.bondelivraison.create', compact('fournisseurs', 'typemateriels', 'marques'));
}

    /**
     * Stockage BL
     */
    public function store(Request $request)
    {
        $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'numero_bl' => 'required|unique:bons_livraison,numero_bl',
            'date_livraison' => 'required|date',
        ]);

        try {
            Bondelivraison::create([
                'fournisseur_id' => $request->fournisseur_id,
                'numero_bl' => $request->numero_bl,
                'date_livraison' => $request->date_livraison,
                'statut' => 'brouillon',
            ]);

            return redirect()->route('bondelivraison.index')
                ->with('success', 'Bon de livraison créé avec succès !');

        } catch (\Throwable $th) {
            return redirect()->back()->withInput()
                ->withErrors(['error' => $th->getMessage()]);
        }
    }

    /**
     * Voir un BL
     */
    public function show($id)
    {
        $bon = Bondelivraison::with(['fournisseur', 'lignes.materiel'])->findOrFail($id);

        return view('admin.bondelivraison.show', compact('bon'));
    }

    /**
     * Formulaire édition
     */
    public function edit($id)
    {
        $bon = Bondelivraison::findOrFail($id);
        $fournisseurs = Fournisseur::all();

        return view('admin.bondelivraison.edit', compact('bon', 'fournisseurs'));
    }

    /**
     * Mise à jour BL
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'numero_bl' => "required|unique:bons_livraison,numero_bl,{$id}",
            'date_livraison' => 'required|date',
        ]);

        $bon = Bondelivraison::findOrFail($id);
        $bon->update([
            'fournisseur_id' => $request->fournisseur_id,
            'numero_bl' => $request->numero_bl,
            'date_livraison' => $request->date_livraison,
        ]);

        return redirect()->route('bondelivraison.index')
            ->with('success', 'Bon de livraison mis à jour avec succès !');
    }

    /**
     * Supprimer BL
     */
    public function destroy($id)
    {
        $bon = Bondelivraison::findOrFail($id);
        $bon->delete();

        return redirect()->route('bondelivraison.index')
            ->with('success', 'Bon de livraison supprimé avec succès !');
    }
}
