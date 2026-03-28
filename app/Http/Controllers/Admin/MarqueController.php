<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class MarqueController extends Controller
{
    /**
     * Affiche la liste des marques.
     */
    public function index()
{

    $marques = Marque::all(); // ✅ doit être ça

    return view('admin.marque.index', compact('marques'));
}

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('admin.marque.create');
    }

    /**
     * Enregistre une nouvelle marque.
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'Designation' => 'required|string|max:255',
        ]);

        try {
            Marque::create([
                 'Designation' => $request->Designation,
            ]);

            session()->flash('success', 'La marque a été ajoutée avec succès.');

        } catch (\Throwable $th) {

            session()->flash(
                'error',
                'Une erreur est survenue lors de l’ajout de la marque.'
            );
        }

        return redirect()->route('marque.index');
    }

    /**
     * Affiche le formulaire d’édition.
     */
    public function edit($id)
    {
        $marque = Marque::findOrFail($id);
        return view('admin.marque.edit', compact('marque'));
    }

    /**
     * Met à jour une marque.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'Designation' => 'required|string|max:255',
        ]);

        try {
            $marque = Marque::findOrFail($id);
            $marque->update([
                'Designation' => $request->Designation,
            ]);

            session()->flash('success', 'La marque a été modifiée avec succès.');

        } catch (\Throwable $th) {

            session()->flash(
                'error',
                'Une erreur est survenue lors de la modification de la marque.'
            );
        }

        return redirect()->route('marque.index');
    }

    /**
     * Supprime une marque.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $marque = Marque::findOrFail($id);
            $marque->delete();

            session()->flash('success', 'La marque a été supprimée avec succès.');

        } catch (\Throwable $th) {

            session()->flash(
                'error',
                'Une erreur est survenue lors de la suppression de la marque.'
            );
        }

        return redirect()->route('marque.index');
    }
}
