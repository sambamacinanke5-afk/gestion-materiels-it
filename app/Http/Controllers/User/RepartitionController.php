<?php

namespace App\Http\Controllers\User;

use App\Models\Service;
use App\Models\Repartition;
use App\Models\Bondelivraison;
use App\Models\LigneRepartition;
use App\Models\LigneBondelivraison;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\RepartitionCreatedMail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RepartitionEffectueeNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RepartitionController extends Controller
{
   public function index()
{
    $repartitions = Repartition::with(['bondelivraison', 'lignes.service'])
        ->orderByDesc('date_repartition')
        ->paginate(10); // ← Pagination 10 par page

    return view('user.repartition.index', compact('repartitions'));
}

   public function create()
{
    // Récupère les bons de livraison qui n'ont pas encore été utilisés pour une répartition
    $bondelivraisons = Bondelivraison::whereNotIn('id', function ($query) {
        $query->select('bondelivraison_id')->from('repartitions');
    })->orderByDesc('id')->get();

    // Tous les services disponibles
    $services = Service::all();

    // Préparer les matériels disponibles pour chaque bon de livraison
    $materielsByBL = [];
    foreach ($bondelivraisons as $bon) {
        $lignesBL = LigneBondelivraison::where('bondelivraison_id', $bon->id)
            ->with(['materiel', 'materiel.marque', 'materiel.typemateriel'])
            ->get();

        $materielsByBL[$bon->id] = $lignesBL->map(function ($ligne) {
            $materiel = $ligne->materiel;

            return [
                'ligneBL_id'    => $ligne->id,
                'id'            => $materiel->id,
                'designation'   => $materiel->designation ?? 'N/A',
                'marque'        => $materiel->marque->Designation ?? 'N/A',
                'typemateriel'  => $materiel->typemateriel->Designation ?? 'N/A',
                'numero_serie'  => $materiel->numero_serie ?? 'N/A',
                'quantite'      => $ligne->quantite ?? 1,
            ];
        });
    }

    // Retourner la vue create avec toutes les données nécessaires
    return view('user.repartition.create', compact('bondelivraisons', 'services', 'materielsByBL'));
}

    public function store(Request $request)
    {
        if (Auth::user()->role_id != 2) {
            abort(403, 'Action non autorisée');
        }

        $request->validate([
            'bondelivraison_id' => 'required|exists:bondelivraisons,id',
            'date_repartition' => 'required|date',
            'lignes' => 'required|array|min:1',
            'lignes.*.service_id' => 'required|exists:services,id',
            'lignes.*.destinataire' => 'required|string',
            'lignes.*.materiel_ids' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $repartition = Repartition::create([
                'bondelivraison_id' => (int) $request->bondelivraison_id,
                'date_repartition' => $request->date_repartition,
                'ordinateur_complets' => (int) ($request->ordinateur_complets ?? 0),
                'ordinateur_portables' => (int) ($request->ordinateur_portables ?? 0),
                'imprimantes' => (int) ($request->imprimantes ?? 0),
                'scanners' => (int) ($request->scanners ?? 0),
                'user_id' => Auth::id(),
            ]);

            $num = 1;
            foreach ($request->lignes as $ligne) {
                $materielsIds = array_map('intval', $ligne['materiel_ids']);

                LigneRepartition::create([
                    'repartition_id' => $repartition->id,
                    'num_ligne' => $num++,
                    'service_id' => (int) $ligne['service_id'],
                    'destinataire' => $ligne['destinataire'],
                    'quantite' => count($materielsIds),
                    'lignebondelivraison_id' => json_encode($materielsIds),
                ]);
            }

            // Notifications + emails pour les admins
            $admins = User::where('role_id', 1)->get();
            foreach ($admins as $admin) {
                $admin->notify(new RepartitionEffectueeNotification($repartition));
                Mail::to($admin->email)->queue(new RepartitionCreatedMail($repartition));
            }

            DB::commit();

            return redirect()
                ->route('user.repartition.index')
                ->with('success', 'Répartition créée avec succès. Notifications envoyées aux admins.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création répartition : ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        // 1️⃣ Chargement principal de la répartition
        $repartition = Repartition::with([
            'bondelivraison.fournisseur',
            'lignes.service',
        ])->findOrFail($id);

        // 2️⃣ Préparation des matériels pour chaque ligne
        foreach ($repartition->lignes as $ligne) {

            // Décodage sécurisé du JSON
            $lbIds = [];

            if (!empty($ligne->lignebondelivraison_id)) {
                $decoded = json_decode($ligne->lignebondelivraison_id, true);

                if (is_array($decoded)) {
                    $lbIds = $decoded;
                }
            }

            // Récupération des lignes BL + matériels
            $ligne->ligneBLs = LigneBondelivraison::with([
                'materiel.marque',
                'materiel.typemateriel',
            ])
                ->whereIn('id', $lbIds)
                ->get();
        }

        // 3️⃣ Retour vers la vue
        return view('user.repartition.show', compact('repartition'));
    }


    public function destroy($id)
    {
        $repartition = Repartition::findOrFail($id);
        $repartition->lignes()->delete();
        $repartition->delete();

        return redirect()->route('user.repartition.index')
            ->with('success', 'Répartition supprimée avec succès.');
    }

    public function pdf($id)
    {
        $repartition = Repartition::with([
            'bondelivraison.fournisseur',
            'lignes.service',
        ])->findOrFail($id);

        foreach ($repartition->lignes as $ligne) {
            $lbIds = json_decode($ligne->lignebondelivraison_id, true) ?? [];
            $ligne->materielsBL = LigneBondelivraison::with([
                'materiel',
                'materiel.marque',
                'materiel.typemateriel'
            ])->whereIn('id', $lbIds)->get();
        }

        $pdf = Pdf::loadView('pdf.repartition', compact('repartition'))
            ->setPaper('A4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans');

        return $pdf->stream('repartition_materiels.pdf');
    }

    public function edit($id)
    {
        $repartition = Repartition::with(['lignes', 'bondelivraison.fournisseur'])->findOrFail($id);
        $bondelivraisons = Bondelivraison::orderByDesc('id')->get();
        $services = Service::all();

        $materielsByBL = [];
        foreach ($bondelivraisons as $bon) {
            $lignesBL = LigneBondelivraison::where('bondelivraison_id', $bon->id)
                ->with(['materiel', 'materiel.marque', 'materiel.typemateriel'])
                ->get();

            $materielsByBL[$bon->id] = $lignesBL->map(function ($ligne) {
                $m = $ligne->materiel;
                return [
                    'ligneBL_id' => $ligne->id,
                    'id' => $m->id,
                    'designation' => $m->designation ?? 'N/A',
                    'marque' => $m->marque->Designation ?? 'N/A',
                    'typemateriel' => $m->typemateriel->Designation ?? 'N/A',
                    'numero_serie' => $m->numero_serie ?? 'N/A',
                    'quantite' => $ligne->quantite ?? 1,
                ];
            });
        }

        return view('user.repartition.edit', compact('repartition', 'bondelivraisons', 'services', 'materielsByBL'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'bondelivraison_id' => 'required|exists:bondelivraisons,id',
                'date_repartition' => 'required|date',
                'lignes' => 'required|array|min:1',
            ]);

            $repartition = Repartition::findOrFail($id);

            $repartition->update([
                'bondelivraison_id' => $request->bondelivraison_id,
                'date_repartition' => $request->date_repartition,
                'ordinateur_complets' => $request->ordinateur_complets ?? 0,
                'ordinateur_portables' => $request->ordinateur_portables ?? 0,
                'imprimantes' => $request->imprimantes ?? 0,
                'scanners' => $request->scanners ?? 0,
            ]);

            LigneRepartition::where('repartition_id', $repartition->id)->delete();

            foreach ($request->lignes as $ligne) {
                if (empty($ligne['materiel']))
                    continue;

                LigneRepartition::create([
                    'repartition_id' => $repartition->id,
                    'num_ligne' => $ligne['num_ligne'],
                    'service_id' => $ligne['service_id'] ?? null,
                    'destinataire' => $ligne['destinataire'] ?? null,
                    'lignebondelivraison_id' => json_encode($ligne['materiel']),
                    'quantite' => count($ligne['materiel']),
                ]);
            }

            DB::commit();

            return redirect()->route('user.repartition.index')
                ->with('success', 'Répartition mise à jour avec succès.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erreur update répartition', ['message' => $e->getMessage(), 'line' => $e->getLine()]);

            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour : ' . $e->getMessage());
        }
    }
}
