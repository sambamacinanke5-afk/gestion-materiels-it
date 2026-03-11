<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use App\Models\Materiel;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\Bondelivraison;
use App\Models\LigneBondelivraison;
use App\Http\Controllers\Controller;
use App\Mail\BonLivraisonCreatedMail;
use App\Models\Marque;
use App\Models\TypeMateriel;
use App\Models\User;
use App\Notifications\BonDeLivraisonNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\BonLivraisonCreatedMail as MailBonLivraisonCreatedMail;


class BondelivraisonController extends Controller
{
    public function index()
    {
        $bondelivraisons = Bondelivraison::with(['fournisseur', 'lignes.materiel'])
            ->orderByDesc('date_livraison')
            ->paginate(10);

        return view('admin.bondelivraison.index', compact('bondelivraisons'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $typemateriels = TypeMateriel::orderBy('Designation')->get();
        $marques = Marque::orderBy('Designation')->get();

        return view('admin.bondelivraison.create', compact('fournisseurs', 'typemateriels', 'marques'));
    }

    public function store(Request $request)
    {
        // Validation des champs principaux et lignes
        $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'bondelivraison' => 'required|unique:bondelivraisons,bondelivraison',
            'date_livraison' => 'required|date',
            'lignes' => 'required|array|min:1',
            'lignes.*.designation' => 'required|string|max:255',
            'lignes.*.typemateriel_id' => 'required|exists:type_materiels,id',
            'lignes.*.marque_id' => 'required|exists:marques,id',
            'lignes.*.numero_serie' => 'required|string|min:10',
        ]);

        // Vérifier doublons dans le formulaire lui-même
        $serials = array_column($request->lignes, 'numero_serie');
        if (count($serials) !== count(array_unique($serials))) {
            return redirect()->back()->withInput()
                ->withErrors(['numero_serie' => 'Il y a des doublons dans les numéros de série du formulaire.']);
        }

        try {
            DB::transaction(function () use ($request) {

                // Création du BL
                $bon = Bondelivraison::create($request->only('fournisseur_id', 'bondelivraison', 'date_livraison'));

                foreach ($request->lignes as $ligne) {
                    // Vérifier doublons en base
                    $existing = Materiel::where('numero_serie', $ligne['numero_serie'])->first();
                    if ($existing) {
                        throw new \Exception("Le numéro de série {$ligne['numero_serie']} existe déjà en base.");
                    }

                    $materiel = Materiel::create([
                        'designation' => $ligne['designation'],
                        'typemateriel_id' => $ligne['typemateriel_id'],
                        'marque_id' => $ligne['marque_id'],
                        'numero_serie' => $ligne['numero_serie'],
                        'etat' => 'non_deploye',
                    ]);

                    LigneBondelivraison::create([
                        'bondelivraison_id' => $bon->id,
                        'materiel_id' => $materiel->id,
                    ]);
                }

                // Notifications internes + emails
                $users = User::where('role_id', 2)->get();
                foreach ($users as $user) {
                    $user->notify(new BonDeLivraisonNotification($bon));
                    Mail::to($user->email)->send(new BonLivraisonCreatedMail($bon));
                }

            });

            return redirect()->route('bondelivraison.index')
                ->with('success', 'Bon de livraison créé avec succès, notifications et emails envoyés !');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function show($id)
    {
        $bondelivraison = Bondelivraison::with(['lignes.materiel', 'fournisseur'])->findOrFail($id);
        return view('admin.bondelivraison.show', compact('bondelivraison'));
    }

    public function edit($id)
    {
        $bon = Bondelivraison::with('lignes.materiel')->findOrFail($id);
        $fournisseurs = Fournisseur::all();
        $typemateriels = TypeMateriel::all();
        $marques = Marque::all();

        return view('admin.bondelivraison.edit', compact('bon', 'fournisseurs', 'typemateriels', 'marques'));
    }



    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $request->validate([
                'fournisseur_id' => 'required|exists:fournisseurs,id',
                'date_livraison' => 'required|date',
                'lignes' => 'required|array|min:1',
                'lignes.*.designation' => 'required|string',
                'lignes.*.typemateriel_id' => 'required|exists:type_materiels,id',
                'lignes.*.marque_id' => 'required|exists:marques,id',
                'lignes.*.numero_serie' => 'required|string',
            ]);

            $bon = Bondelivraison::with('lignes.materiel')->findOrFail($id);

            // 🔹 Update bon
            $bon->update([
                'fournisseur_id' => $request->fournisseur_id,
                'date_livraison' => $request->date_livraison,
            ]);

            // 🔹 Numéros de série envoyés
            $numerosSeriesEnvoyes = collect($request->lignes)
                ->pluck('numero_serie')
                ->toArray();

            /**
             * 🔥 SUPPRESSION DES LIGNES + MATERIELS SUPPRIMÉS DU FORMULAIRE
             */
            $lignesASupprimer = $bon->lignes()
                ->whereHas('materiel', function ($q) use ($numerosSeriesEnvoyes) {
                    $q->whereNotIn('numero_serie', $numerosSeriesEnvoyes);
                })
                ->get();

            foreach ($lignesASupprimer as $ligne) {
                // supprimer le matériel
                $ligne->materiel()->delete();
                // supprimer la ligne
                $ligne->delete();
            }

            /**
             * 🔁 UPDATE / CREATE DES LIGNES RESTANTES
             */
            foreach ($request->lignes as $ligneData) {

                // chercher le matériel par numéro de série
                $materiel = Materiel::where('numero_serie', $ligneData['numero_serie'])->first();

                if ($materiel) {
                    // 🔄 UPDATE
                    $materiel->update([
                        'designation' => $ligneData['designation'],
                        'typemateriel_id' => $ligneData['typemateriel_id'],
                        'marque_id' => $ligneData['marque_id'],
                    ]);
                } else {
                    // ➕ CREATE
                    $materiel = Materiel::create([
                        'designation' => $ligneData['designation'],
                        'typemateriel_id' => $ligneData['typemateriel_id'],
                        'marque_id' => $ligneData['marque_id'],
                        'numero_serie' => $ligneData['numero_serie'],
                        'etat' => 'non_deploye',
                    ]);
                }

                // 🔗 lier au bon si pas encore lié
                $bon->lignes()->firstOrCreate([
                    'materiel_id' => $materiel->id,
                ]);
            }
        });

        return redirect()
            ->route('bondelivraison.index')
            ->with('success', 'Bon de livraison mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $bon = Bondelivraison::with('lignes.materiel')->findOrFail($id);

        foreach ($bon->lignes as $ligne) {
            if ($ligne->materiel && $ligne->materiel->lignes()->count() <= 1) {
                $ligne->materiel->delete();
            }
        }

        $bon->lignes()->delete();
        $bon->delete();

        return redirect()->route('bondelivraison.index')
            ->with('success', 'Bon de livraison et ses lignes supprimés avec succès.');
    }
}
