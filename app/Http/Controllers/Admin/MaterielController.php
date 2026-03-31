<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use App\Models\Marque;
use App\Models\TypeMateriel;
use App\Models\CategorieMateriel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MaterielController extends Controller
{
    /**
     * LISTE
     */
    public function index()
    {
        $materiels = Materiel::with([
            'marque',
            'typemateriel',
            'categorie'
        ])->latest()->get();

        return view('admin.materiel.index', compact('materiels'));
    }

    /**
     * FORMULAIRE CREATE
     */
    public function create()
    {
        $marques = Marque::all();
        $types = TypeMateriel::all();
        $categories = CategorieMateriel::all();

       return view('admin.materiel.create', compact('marques', 'types', 'categories'));
    }

    /**
     * ENREGISTRER
     */
    public function store(Request $request)
    {
        $request->validate([
            'code_inventaire' => 'required|unique:materiels,code_inventaire',
            'numero_serie'    => 'nullable|unique:materiels,numero_serie',
            'marque_id'       => 'required|exists:marques,id',
            'typemateriel_id' => 'required|exists:type_materiels,id',
            'categorie_id'    => 'required|exists:categories_materiel,id',
        ]);

        Materiel::create([
            'code_inventaire' => $request->code_inventaire,
            'numero_serie'    => $request->numero_serie,
            'modele'          => $request->modele,
            'marque_id'       => $request->marque_id,
            'typemateriel_id' => $request->typemateriel_id,
            'categorie_id'    => $request->categorie_id,
            'statut'          => $request->statut ?? 'recu',
        ]);

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel ajouté avec succès');
    }

    /**
     * EDIT
     */
    public function edit(Materiel $materiel)
    {
        $marques = Marque::all();
        $types = TypeMateriel::all();
        $categories = CategorieMateriel::all();

       return view('admin.materiels.edit', compact('marques', 'types', 'categories'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Materiel $materiel)
    {
        $request->validate([
            'code_inventaire' => 'required|unique:materiels,code_inventaire,' . $materiel->id,
            'numero_serie'    => 'nullable|unique:materiels,numero_serie,' . $materiel->id,
            'marque_id'       => 'required|exists:marques,id',
            'typemateriel_id' => 'required|exists:type_materiels,id',
            'categorie_id'    => 'required|exists:categories_materiel,id',
        ]);

        $materiel->update([
            'code_inventaire' => $request->code_inventaire,
            'numero_serie'    => $request->numero_serie,
            'modele'          => $request->modele,
            'marque_id'       => $request->marque_id,
            'typemateriel_id' => $request->typemateriel_id,
            'categorie_id'    => $request->categorie_id,
            'statut'          => $request->statut,
        ]);

        return redirect()->route('materiels.index')
            ->with('success', 'Matériel mis à jour');
    }

    /**
     * DELETE
     */
    public function destroy(Materiel $materiel)
    {
        $materiel->delete();

        return back()->with('success', 'Matériel supprimé');
    }
}
