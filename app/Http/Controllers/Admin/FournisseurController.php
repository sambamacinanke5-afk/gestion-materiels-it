<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Fournisseur;

class FournisseurController extends Controller
{
    /**
     * Affiche la liste des fournisseurs.
     */
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        return view('admin.fournisseur.index', compact('fournisseurs'));
    }

    /**
     * Affiche le formulaire de création d'un fournisseur.
     */
    public function create()
    {
        return view('admin.fournisseur.create');
    }

    /**
     * Enregistre un nouveau fournisseur.
     */
     public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'contact_nom' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
            'actif' => 'nullable|boolean',
        ]);

        // S'assurer que 'actif' est bien booléen
        $validated['actif'] = $request->has('actif') ? 1 : 0;

        Fournisseur::create($validated);

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur créé avec succès.');
    }
    /**
     * Affiche le formulaire d'édition d'un fournisseur.
     */
   public function edit($id)
{
    $fournisseur = Fournisseur::findOrFail($id);
    return view('admin.fournisseur.edit', compact('fournisseur'));
}

    /**
     * Met à jour un fournisseur existant.
     */

public function update(Request $request, $id): RedirectResponse
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'contact_nom' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'adresse' => 'nullable|string|max:255',
        'actif' => 'nullable|boolean',
    ]);

    // Gestion du checkbox actif
    $validated['actif'] = $request->has('actif') ? 1 : 0;

    try {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->update($validated);

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur modifié avec succès.');

    } catch (\Throwable $th) {

        return redirect()->route('fournisseurs.index')
                         ->with('error', 'Erreur lors de la modification.');
    }
}

    /**
     * Supprime un fournisseur.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $fournisseur = Fournisseur::findOrFail($id);
            $fournisseur->delete();

            session()->flash('success', 'Le fournisseur a été supprimé avec succès.');

        } catch (\Throwable $th) {

            session()->flash('error', 'Une erreur est survenue lors de la suppression du fournisseur.');
        }

        return redirect()->route('fournisseurs.index');
    }
}
