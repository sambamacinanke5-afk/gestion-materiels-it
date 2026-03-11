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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom'     => 'required|string|max:255',
            'Adresse' => 'required|string|max:255',
            'Contact' => 'required|string|max:50',
        ]);

        try {
            Fournisseur::create($request->only('nom', 'Adresse', 'Contact'));

            // Message de succès
            session()->flash('success', 'Fournisseur ajouté avec succès.');

        } catch (\Throwable $th) {

            // Message d'erreur
            session()->flash('error', 'Une erreur est survenue lors de la création du fournisseur.');
        }

        return redirect()->route('fournisseurs.index');
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
        $request->validate([
            'nom'     => 'required|string|max:255',
            'Adresse' => 'required|string|max:255',
            'Contact' => 'required|string|max:50',
        ]);

        try {
            $fournisseur = Fournisseur::findOrFail($id);
            $fournisseur->update($request->only('nom', 'Adresse', 'Contact'));

            session()->flash('success', 'Le fournisseur a été modifié avec succès.');

        } catch (\Throwable $th) {

            session()->flash('error', 'Une erreur est survenue lors de la modification du fournisseur.');
        }

        return redirect()->route('fournisseurs.index');
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
