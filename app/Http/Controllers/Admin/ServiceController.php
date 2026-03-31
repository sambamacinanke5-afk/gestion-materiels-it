<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
//use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    /**
     * Liste des services
     */
    public function index()
    {
        $services = Service::all();
        return view('admin.servicess.index', compact('services'));
    }

    /**
     * Formulaire de création
     */
   public function create()
{
    //$sites = Site::all(); // 🔥 récupération des sites

    return view('admin.servicess.create', compact('services'));
}

public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        //'site_id' => 'nullable|exists:sites,id',
    ]);

    Service::create([
        'nom' => $request->nom,
        //'site_id' => $request->site_id,
    ]);

    return redirect()->route('service.index')
        ->with('success', 'Service ajouté avec succès !');
}
    /**
     * Formulaire d’édition
     */
    public function edit(int $id)
    {
        $service = Service::findOrFail($id);
        return view('admin.servicess.edit', compact('service'));
    }

    /**
     * Mise à jour du service
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        try {
            $service = Service::findOrFail($id);
            $service->update(
                $request->only('nom')
            );

            session()->flash('success', 'Le service a été modifié avec succès.');

        } catch (\Throwable $e) {

            session()->flash('error', 'Une erreur est survenue lors de la modification du service.');
        }

        return redirect()->route('service.index');
    }

    /**
     * Suppression du service
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            Service::findOrFail($id)->delete();

            session()->flash('success', 'Le service a été supprimé avec succès.');

        } catch (\Throwable $e) {

            session()->flash('error', 'Impossible de supprimer le service.');
        }

        return redirect()->route('service.index');
    }
}
