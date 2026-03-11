<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bondelivraison;
use App\Models\Fournisseur;
use App\Models\LigneBondelivraison;
use App\Models\Marque;
use App\Models\Materiel;
use App\Models\Notification;
use App\Models\TypeMateriel;
use App\Notifications\BonDeLivraisonNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\BonLivraisonCreatedMail;
use Illuminate\Support\Facades\Mail;

class BondelivraisonController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();

        // On récupère tous les BL, avec lignes et matériels
        $bondelivraisons = Bondelivraison::with('lignes.materiel', 'fournisseur')
            ->orderByDesc('date_livraison')
            ->paginate(10);

        return view('user.bondelivraison.index', compact('bondelivraisons'));
    }



    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('nom', 'asc')->get();
        $typemateriels = TypeMateriel::orderBy('Designation', 'asc')->get(); // 🔹 nom exact
        $marques = Marque::orderBy('Designation', 'asc')->get();

        return view('user.bondelivraison.create', compact('fournisseurs', 'typemateriels', 'marques'));
    }


    public function store(Request $request)
    {
        // Valider les champs principaux
        $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'bondelivraison' => 'required|string',
            'date_livraison' => 'required|date',
            'lignes.*.designation' => 'required|string',
            'lignes.*.typemateriel_id' => 'required|exists:type_materiels,id',
            'lignes.*.marque_id' => 'required|exists:marques,id',
            'lignes.*.numero_serie' => 'required|string|unique:materiels,numero_serie',
        ]);

        // Créer le bon de livraison
        $bon = \App\Models\Bondelivraison::create([
            'fournisseur_id' => $request->fournisseur_id,
            'bondelivraison' => $request->bondelivraison,
            'date_livraison' => $request->date_livraison,
        ]);

        // Boucle sur chaque ligne pour créer le matériel et la ligne de BL
        foreach ($request->lignes as $ligne) {
            // Créer le matériel
            $materiel = \App\Models\Materiel::create([
                'designation' => $ligne['designation'],
                'typemateriel_id' => $ligne['typemateriel_id'],
                'marque_id' => $ligne['marque_id'],
                'numero_serie' => $ligne['numero_serie'],
                'etat' => 'non_deploye',
            ]);

            // Créer la ligne de BL avec materiel_id
            $bon->lignes()->create([
                'materiel_id' => $materiel->id,
            ]);
        }

        return redirect()->route('bondelivraison.index')
            ->with('success', 'Bon de livraison enregistré avec succès !');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bondelivraison = Bondelivraison::with([
            'lignes.materiel', // chaque ligne charge son matériel
            'fournisseur'
        ])->findOrFail($id);

        return view('user.bondelivraison.show', compact('bondelivraison'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // 🔹 Récupération du bon de livraison avec ses lignes et matériels
        $bon = Bondelivraison::with('lignes.materiel')->findOrFail($id);

        // 🔹 Liste des fournisseurs pour le select
        $fournisseurs = Fournisseur::all();

        // 🔹 Liste des types de matériels
        $typemateriels = TypeMateriel::all();

        // 🔹 Liste des marques
        $marques = Marque::all();

        return view('user.bondelivraison.edit', compact(
            'bon',
            'fournisseurs',
            'typemateriels',
            'marques'
        ));
    }


    public function update(Request $request, $id)
    {
        // 🔹 Validation de base
        $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'bondelivraison' => 'required|string|max:255',
            'date_livraison' => 'required|date',
            'lignes' => 'required|array',
            'lignes.*.designation' => 'required|string|max:255',
            'lignes.*.typemateriel_id' => 'required|exists:type_materiels,id',
            'lignes.*.marque_id' => 'required|exists:marques,id',
            'lignes.*.numero_serie' => 'required|string|max:255',
        ]);

        // 🔹 Récupération du BL existant
        $bon = Bondelivraison::findOrFail($id);
        $bon->update([
            'fournisseur_id' => $request->fournisseur_id,
            'bondelivraison' => $request->bondelivraison,
            'date_livraison' => $request->date_livraison,
        ]);

        $existingMaterielIds = $bon->lignes->pluck('materiel_id')->toArray();
        $newMaterielIds = [];

        foreach ($request->lignes as $ligneData) {
            // Vérifie si le matériel existe déjà par numéro de série
            $materiel = Materiel::where('numero_serie', $ligneData['numero_serie'])->first();

            if (!$materiel) {
                // Création d'un nouveau matériel
                $materiel = Materiel::create([
                    'designation' => $ligneData['designation'],
                    'typemateriel_id' => $ligneData['typemateriel_id'],
                    'marque_id' => $ligneData['marque_id'],
                    'numero_serie' => $ligneData['numero_serie'],
                    'etat' => 'non_deploye',
                ]);
            } else {
                // Optionnel : mise à jour du matériel existant
                $materiel->update([
                    'designation' => $ligneData['designation'],
                    'typemateriel_id' => $ligneData['typemateriel_id'],
                    'marque_id' => $ligneData['marque_id'],
                ]);
            }

            $newMaterielIds[] = $materiel->id;

            // Mise à jour ou création de la ligne de BL
            $bon->lignes()->updateOrCreate(
                ['materiel_id' => $materiel->id], // critère unique pour update
                ['bondelivraison_id' => $bon->id]
            );
        }

        // 🔹 Supprimer les lignes qui ne sont plus présentes dans le formulaire
        $materielsToDelete = array_diff($existingMaterielIds, $newMaterielIds);
        if (!empty($materielsToDelete)) {
            LigneBondelivraison::where('bondelivraison_id', $bon->id)
                ->whereIn('materiel_id', $materielsToDelete)
                ->delete();
        }

        return redirect()->route('bondelivraison.index')
            ->with('success', 'Bon de livraison mis à jour avec succès !');
    }
    // 🔹 Supprimer un bon
    /**
     * Supprimer un bon de livraison avec ses lignes et matériels optionnels
     */
    public function destroy($id)
    {
        // Récupérer le bon
        $bon = Bondelivraison::with('lignes.materiel')->findOrFail($id);

        // Supprimer les matériels associés si nécessaire
        foreach ($bon->lignes as $ligne) {
            // Optionnel : supprimer le matériel seulement si il n'est lié à aucun autre BL
            if ($ligne->materiel && $ligne->materiel->lignes()->count() <= 1) {
                $ligne->materiel->delete();
            }
        }

        // Supprimer toutes les lignes
        $bon->lignes()->delete();

        // Supprimer le bon lui-même
        $bon->delete();

        return redirect()->route('bondelivraison.index')
            ->with('success', 'Bon de livraison supprimé avec succès !');
    }
    public function changeStatut(Request $request, $id)
    {
        $request->validate([
            'etat' => 'required|in:non_deploye,deploye,annule',
        ]);

        $bon = Bondelivraison::findOrFail($id);
        $bon->update([
            'etat' => $request->etat
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Statut du bon de livraison mis à jour en '{$request->etat}'",
            'etat' => $bon->etat
        ]);
        $bondelivraison = Bondelivraison::create([
            // tes champs ici
        ]);

        // 🔔 ENVOI MAIL À L’UTILISATEUR
        if ($bondelivraison->user && $bondelivraison->user->email) {
            Mail::to($bondelivraison->user->email)
                ->send(new BonLivraisonCreatedMail($bondelivraison));
        }
    }
    public function asRead(Request $request, $id){
        $notification = Notification::findOrFail($id);
        $notification->read_at = now();
        $notification->save();
        return route('user.bondelivraison.show');

    }

}
