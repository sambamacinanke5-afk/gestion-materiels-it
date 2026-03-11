<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeMateriel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TypeMaterielController extends Controller
{
    /**
     * Affiche la liste des types de matériel.
     */
    public function index()
    {
        $typemateriels = TypeMateriel::all();
        return view('admin.gestionmateriel.index', compact('typemateriels'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('admin.gestionmateriel.create');
    }

    /**
     * Enregistre un nouveau type de matériel.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'Designation' => 'required|string|max:255',
        ]);

        try {
            TypeMateriel::create([
                'Designation' => $request->Designation,
            ]);

            session()->flash('success', 'Le type de matériel a été ajouté avec succès.');

        } catch (\Throwable $th) {

            session()->flash('error', 'Une erreur est survenue lors de l’ajout du type de matériel.');
        }

        return redirect()->route('gestionmateriel.index');
    }

    /**
     * Affiche le formulaire d’édition.
     */
    public function edit($id)
    {
        $typemateriel = TypeMateriel::findOrFail($id);
        return view('admin.gestionmateriel.edit', compact('typemateriel'));
    }

    /**
     * Met à jour un type de matériel.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'Designation' => 'required|string|max:255',
        ]);

        try {
            $typemateriel = TypeMateriel::findOrFail($id);
            $typemateriel->update([
                'Designation' => $request->Designation,
            ]);

            session()->flash('success', 'Le type de matériel a été modifié avec succès.');

        } catch (\Throwable $th) {

            session()->flash(
                'error',
                'Une erreur est survenue lors de la modification du type de matériel.'
            );
        }

        return redirect()->route('gestionmateriel.index');
    }

    /**
     * Supprime un type de matériel.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $typemateriel = TypeMateriel::findOrFail($id);
            $typemateriel->delete();

            session()->flash('success', 'Le type de matériel a été supprimé avec succès.');

        } catch (\Throwable $th) {

            session()->flash(
                'error',
                'Une erreur est survenue lors de la suppression du type de matériel.'
            );
        }

        return redirect()->route('gestionmateriel.index');
    }
}
