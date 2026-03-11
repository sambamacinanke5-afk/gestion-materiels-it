@extends('partials.admin.master')

@section('content')
<style>
body { background: #f2f4f8; }
.dashboard-wrapper { padding: 25px; font-family: 'Segoe UI', sans-serif; }

/* Cartes principales */
.cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-bottom: 30px; }
.card-box { border-radius: 18px; padding: 25px; color: #fff; text-align: center; box-shadow: 0 15px 35px rgba(0,0,0,.15); }
.card-box .icon-circle { width: 70px; height: 70px; background: rgba(255,255,255,.9); border-radius: 50%; margin: auto; display:flex; justify-content:center; align-items:center; }
.card-box .icon-circle i { font-size: 32px; color: #333; }
.card-title { margin-top: 15px; font-size: 18px; font-weight: 600; }
.card-value { font-size: 40px; font-weight: bold; margin: 10px 0; }
.card-sub { font-size: 14px; opacity:.9; }

.blue { background: linear-gradient(160deg, #1e5aa3, #3b82f6); }
.orange { background: linear-gradient(160deg, #f97316, #f59e0b); }
.green { background: linear-gradient(160deg, #10b981, #06b6d4); }
.red { background: linear-gradient(160deg, #ef4444, #f87171); }
.yellow { background: linear-gradient(160deg, #facc15, #fde047); }
.teal { background: linear-gradient(160deg, #14b8a6, #2dd4bf); }

/* Résumé des matériels */
.summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px; margin-bottom: 30px; }
.summary-card { border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,.08); color: #fff; }
.summary-card .icon-circle { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,.8); display:flex; align-items:center; justify-content:center; margin: 0 auto 8px; }
.summary-card .icon-circle i { font-size: 20px; color: #333; }
.summary-card .card-title { font-size: 14px; margin-bottom: 5px; }
.summary-card .card-value { font-size: 28px; font-weight: bold; }
.summary-card .card-sub { font-size: 12px; opacity: .85; }

/* Table BL en instance */
.table-hover tbody tr:hover { background: rgba(59, 130, 246, 0.1); }
.progress { height: 15px; border-radius: 10px; }
</style>

<div class="dashboard-wrapper">

    <!-- Cartes principales -->
    <div class="cards-grid">
        <div class="card-box blue">
            <div class="icon-circle"><i class='bx bx-clipboard'></i></div>
            <div class="card-title">Bons de Livraisons NON Répartis</div>
            <div class="card-value">{{ $totalBL ?? 0 }}</div>
            <div class="card-sub">En attente</div>
        </div>

        <div class="card-box orange">
            <div class="icon-circle"><i class='bx bx-cog'></i></div>
            <div class="card-title">Répartitions En Instance</div>
            <div class="card-value">{{ $repartitionsEnInstance ?? 0 }}</div>
            <div class="card-sub">Partiellement déployées</div>
        </div>

        <div class="card-box green">
            <div class="icon-circle"><i class='bx bx-desktop'></i></div>
            <div class="card-title">Matériels NON Déployés</div>
            <div class="card-value">{{ $totalMachinesDeployees ?? 0 }}</div>
            <div class="card-sub">Non installés</div>
        </div>
    </div>

    <!-- Résumé des matériels -->
    <h4 class="mb-3">Résumé des matériels</h4>
    <div class="summary-grid">
        <div class="summary-card blue">
            <div class="icon-circle"><i class='bx bx-laptop'></i></div>
            <div class="card-title">Ordinateurs déployés</div>
            <div class="card-value">{{ $totaux['ordinateur_deploye'] }}</div>
            <div class="card-sub">Installés</div>
        </div>
        <div class="summary-card red">
            <div class="icon-circle"><i class='bx bx-laptop'></i></div>
            <div class="card-title">Ordinateurs non déployés</div>
            <div class="card-value">{{ $totaux['ordinateur_non_deploye'] }}</div>
            <div class="card-sub">Non installés</div>
        </div>

        <div class="summary-card green">
            <div class="icon-circle"><i class='bx bx-printer'></i></div>
            <div class="card-title">Imprimantes déployées</div>
            <div class="card-value">{{ $totaux['imprimante_deploye'] }}</div>
            <div class="card-sub">Installées</div>
        </div>
        <div class="summary-card yellow">
            <div class="icon-circle"><i class='bx bx-printer'></i></div>
            <div class="card-title">Imprimantes non déployées</div>
            <div class="card-value">{{ $totaux['imprimante_non_deploye'] }}</div>
            <div class="card-sub">Non installées</div>
        </div>

        <div class="summary-card teal">
            <div class="icon-circle"><i class='bx bx-barcode'></i></div>
            <div class="card-title">Scanners déployés</div>
            <div class="card-value">{{ $totaux['scanner_deploye'] }}</div>
            <div class="card-sub">Installés</div>
        </div>
        <div class="summary-card orange">
            <div class="icon-circle"><i class='bx bx-barcode'></i></div>
            <div class="card-title">Scanners non déployés</div>
            <div class="card-value">{{ $totaux['scanner_non_deploye'] }}</div>
            <div class="card-sub">Non installés</div>
        </div>
    </div>

    <!-- Total général non déployé -->
    <div class="mt-4 mb-4">
        <div class="card shadow text-center bg-secondary text-white">
            <div class="card-body py-2">
                <strong>Total matériels NON déployés :</strong>
                <span class="fs-4">{{ $totaux['non_deploye_total'] }}</span>
            </div>
        </div>
    </div>

    <!-- Liste BL en instance -->
    <div class="mt-5">
        <h4 class="mb-3">
            Bons de Livraison en instance
            <span class="badge bg-warning text-dark">{{ $blEnInstance->count() ?? 0 }}</span>
        </h4>

        @if($blEnInstance->isEmpty())
            <div class="alert alert-success">
                ✅ Aucun bon en attente de déploiement
            </div>
        @else
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>BL</th>
                        <th>Total matériels</th>
                        <th>Déployés</th>
                        <th>Restants</th>
                        <th>Progression</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blEnInstance as $bl)
                        @php
                            $progress = $bl->total_materiels > 0 ? round(($bl->materiels_deployes / $bl->total_materiels) * 100) : 0;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('deploiement.create', ['bl_id' => $bl->id]) }}">
                                    <strong>{{ $bl->numero_bl }}</strong>
                                </a>
                            </td>
                            <td>{{ $bl->total_materiels }}</td>
                            <td class="text-success">{{ $bl->materiels_deployes }}</td>
                            <td class="text-danger">{{ $bl->total_materiels - $bl->materiels_deployes }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ $progress }}%</div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
