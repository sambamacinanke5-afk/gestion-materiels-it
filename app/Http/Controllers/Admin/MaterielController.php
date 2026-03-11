<?php

namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers;

use App\Models\Marque;
use App\Models\Materiel;
use App\Models\TypeMateriel;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

class MaterielController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $materiels = Materiel::all();
        $typemateriels = TypeMateriel::all();
               $marques=Marque::all();

        return view("admin.materiel.index", compact("typemateriels","materiels","marques"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $materiels = Materiel::all();
        $typemateriels = TypeMateriel::all();
        $marques=Marque::all();
     return view("admin.materiel.create", compact("typemateriels","materiels","marques"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validation
        $request->validate([
            "Designation" => "required|string|max:255",
            "Numerodeserie" => "required|string|max:255|unique:materiels,Numerodeserie",
            "typemateriel_id" => "required|exists:type_materiels,id",
            "marque_id" => "required|exists:marques,id",
        ]);

        try {
            // ✅ Création
            Materiel::create([
                'Designation' => $request->Designation,
                'Numerodeserie' => $request->Numerodeserie,
                'typemateriel_id' => $request->typemateriel_id,
                'marque_id' => $request->marque_id,
            ]);

            // ✅ Notification de succès
            toastr()->success('Le matériel a été ajouté avec succès !', 'Succès');

            return redirect()->route('materiel.index');
        } catch (\Exception $e) {
            // ✅ Notification d’erreur
            toastr()->error('Une erreur est survenue lors de l\'ajout du matériel.', 'Erreur');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Materiel $materiel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $materiel = Materiel::findOrFail($id);
        $typemateriels = TypeMateriel::all();
        $marques = Marque::all();

        return view('admin.materiel.edit', compact('materiel', 'typemateriels', 'marques'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Designation' => 'required',
            'Numerodeserie' => 'required|string|max:255|unique:materiels,Numerodeserie,' . $id,
            'typemateriel_id' => 'required|exists:type_materiels,id',
            'marque_id' => 'required|exists:marques,id',
        ]);

        try {
            $materiel = Materiel::findOrFail($id);
            $materiel->update($request->all());

            toastr()->success('Le matériel a été mis à jour avec succès !', 'Succès');
            return redirect()->route('materiel.index');
        } catch (\Exception $e) {
            toastr()->error('Une erreur est survenue lors de la mise à jour.', 'Erreur');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $materiel = Materiel::findOrFail($id);
        $materiel->delete();

        toastr()->success('La marque a été supprimé avec succès.');

        return redirect()->route('materiel.index');
    }
}
