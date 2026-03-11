<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Models\LigneBondelivraison;
use Illuminate\Http\Request;

class LigneBondelivraisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */

  // 🔹 Liste des lignes
  public function index(Request $request)
  {
      $etat = $request->query('etat'); // ex: "déployé", "non déployé", etc.

      $lignes = LigneBondelivraison::with([
          'typemateriel',
          'marque',
          'bondelivraison',
          'ligneDeploiements.deploiement'
      ])->get();

      // Si on a un filtre d'état, on le applique
      if ($etat) {
          $lignes = $lignes->filter(function ($ligne) use ($etat) {
              // Récupère tous les états des déploiements de la ligne
              $etats = $ligne->ligneDeploiements->map(fn($ld) => $ld->deploiement->etat ?? 'Non déployé');
              // Vérifie si le filtre correspond à au moins un état
              return $etats->contains($etat);
          });
      }

      return view('user.gestiondeploiement.index', compact('lignes', 'etat'));
  }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LigneBondelivraison $ligneBondelivraison)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LigneBondelivraison $ligneBondelivraison)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LigneBondelivraison $ligneBondelivraison)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LigneBondelivraison $ligneBondelivraison)
    {
        //
    }
}
