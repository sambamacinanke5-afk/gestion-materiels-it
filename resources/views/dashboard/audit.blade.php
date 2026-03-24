@extends('layouts.app')

@section('content')
<style>
    :root{
        --bg:#f3f5f9;
        --card:#ffffff;
        --text:#1f2a44;
        --muted:#6b7280;
        --line:#e9edf3;
        --blue:#2f6fed;
        --orange:#f5a623;
        --green:#24b36b;
        --purple:#7b61d9;
        --red:#ef4444;
        --shadow:0 4px 10px rgba(17, 24, 39, .04);
        --radius:16px;
    }

    .dashboard-page{
        max-width: 1280px;
        margin: 0 auto;
        height: calc(100vh - 20px);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dashboard-header{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:12px;
        flex: 0 0 auto;
    }

    .dashboard-title{
        margin:0;
        font-size:24px;
        line-height:1.05;
        color:var(--text);
        font-weight:800;
    }

    .dashboard-subtitle{
        margin:4px 0 0;
        color:var(--muted);
        font-size:12px;
    }

    .dashboard-tools{
        display:flex;
        gap:8px;
        align-items:center;
        flex-wrap:wrap;
    }

    .dashboard-tools form{
        margin:0;
    }

    .period-select{
        min-width:190px;
        height:42px;
        border:none;
        background:#fff;
        border-radius:12px;
        padding:0 12px;
        font-size:13px;
        color:#374151;
        box-shadow:var(--shadow);
        outline:none;
    }

    .refresh-btn{
        height:42px;
        border:none;
        border-radius:12px;
        background:var(--blue);
        color:#fff;
        font-size:13px;
        font-weight:700;
        padding:0 16px;
        cursor:pointer;
        box-shadow:var(--shadow);
    }

    .grid-3{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:12px;
        flex: 0 0 auto;
    }

    .grid-2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
        flex: 0 0 auto;
    }

    .card{
        background:var(--card);
        border-radius:var(--radius);
        box-shadow:var(--shadow);
        border:1px solid #eef2f7;
    }

    .card-body{
        padding:12px;
    }

    .card-title{
        margin:0 0 8px;
        font-size:14px;
        color:var(--text);
        font-weight:800;
    }

    .kpi-card{
        min-height:80px;
    }

    .kpi-content{
        display:flex;
        align-items:center;
        gap:10px;
        height:100%;
    }

    .kpi-icon{
        width:40px;
        height:40px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#fff;
        font-size:16px;
        flex-shrink:0;
    }

    .kpi-label{
        margin:0 0 3px;
        font-size:12px;
        line-height:1.15;
        color:var(--text);
        font-weight:800;
    }

    .kpi-value{
        margin:0;
        font-size:28px;
        line-height:1;
        color:var(--text);
        font-weight:800;
    }

    .bg-blue{ background:var(--blue); }
    .bg-orange{ background:var(--orange); }
    .bg-green{ background:var(--green); }
    .bg-purple{ background:var(--purple); }
    .bg-red{ background:var(--red); }

    .row-item{
        display:flex;
        align-items:flex-start;
        gap:10px;
        padding:6px 0;
        border-top:1px solid var(--line);
    }

    .row-item:first-child{
        border-top:none;
        padding-top:0;
    }

    .badge-round{
        width:22px;
        height:22px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#fff;
        font-size:11px;
        font-weight:800;
        flex-shrink:0;
    }

    .row-text{
        font-size:12px;
        color:#334155;
        line-height:1.3;
    }

    .row-text strong.red{ color:#dc2626; }
    .row-text strong.orange{ color:#f59e0b; }
    .row-text strong.green{ color:#16a34a; }

    .report-list .report-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        border-top:1px solid var(--line);
        padding:8px 0;
        font-size:13px;
    }

    .report-list .report-row:first-child{
        border-top:none;
        padding-top:0;
    }

    .report-label{
        color:#334155;
        font-weight:600;
    }

    .report-value{
        color:var(--text);
        font-weight:800;
    }

    .quick-actions{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .quick-btn{
        border:none;
        border-radius:10px;
        padding:8px 12px;
        color:#fff;
        font-size:12px;
        font-weight:800;
        min-width:150px;
        cursor:pointer;
        box-shadow:var(--shadow);
    }

    .quick-blue{ background:var(--blue); }
    .quick-green{ background:#16a34a; }
    .quick-orange{ background:var(--orange); }
    .quick-purple{ background:var(--purple); }

    .empty-state{
        color:#98a2b3;
        font-style:italic;
        font-size:12px;
    }

    @media (max-width: 1200px){
        .grid-3{ grid-template-columns:1fr; }
        .grid-2{ grid-template-columns:1fr; }
    }
</style>

<div class="dashboard-page">
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">{{ $pageTitle ?? 'Tableau de bord Audit & Reporting' }}</h1>
            <p class="dashboard-subtitle">Contrôle, traçabilité et rapports d’activité</p>
        </div>

        <div class="dashboard-tools">
            <form method="GET" action="{{ route('dashboard.audit') }}">
                <select name="period" class="period-select" onchange="this.form.submit()">
                    <option value="" {{ empty($period) ? 'selected' : '' }}>Période : Toutes</option>
                    <option value="today" {{ ($period ?? '') === 'today' ? 'selected' : '' }}>Période : Aujourd’hui</option>
                    <option value="7d" {{ ($period ?? '') === '7d' ? 'selected' : '' }}>Période : 7 jours</option>
                    <option value="30d" {{ ($period ?? '') === '30d' ? 'selected' : '' }}>Période : 30 jours</option>
                </select>
            </form>

            <button class="refresh-btn" type="button" onclick="window.location.reload();">
                Actualiser
            </button>
        </div>
    </div>

    <div class="grid-3">
        <div class="card kpi-card">
            <div class="card-body kpi-content">
                <div class="kpi-icon bg-blue"><i class="fa-solid fa-arrows-rotate"></i></div>
                <div>
                    <p class="kpi-label">Mouvements aujourd’hui</p>
                    <p class="kpi-value">{{ $kpis['mouvements_aujourdhui'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card kpi-card">
            <div class="card-body kpi-content">
                <div class="kpi-icon bg-red"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <p class="kpi-label">Alertes critiques</p>
                    <p class="kpi-value">{{ $kpis['alertes_critiques'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card kpi-card">
            <div class="card-body kpi-content">
                <div class="kpi-icon bg-purple"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div>
                    <p class="kpi-label">Historique total</p>
                    <p class="kpi-value">{{ $kpis['historique_total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Journal des mouvements</h3>

                @if(isset($journal_mouvements) && count($journal_mouvements))
                    @foreach($journal_mouvements as $mouvement)
                        <div class="row-item">
                            <div class="badge-round bg-blue">i</div>
                            <div class="row-text">
                                <strong>{{ ucfirst($mouvement->type_mouvement ?? 'mouvement') }}</strong>
                                @if(!empty($mouvement->materiel?->designation))
                                    : {{ $mouvement->materiel->designation }}
                                @elseif(!empty($mouvement->materiel?->numero_serie))
                                    : {{ $mouvement->materiel->numero_serie }}
                                @endif

                                @if(!empty($mouvement->nouveau_statut))
                                    — statut : {{ $mouvement->nouveau_statut }}
                                @endif

                                @if(!empty($mouvement->commentaire))
                                    — {{ $mouvement->commentaire }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">Aucun mouvement disponible.</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Alertes & anomalies</h3>

                @if(!empty($alertes_anomalies['materiels_en_panne']) && count($alertes_anomalies['materiels_en_panne']))
                    @foreach($alertes_anomalies['materiels_en_panne'] as $materiel)
                        <div class="row-item">
                            <div class="badge-round bg-red">!</div>
                            <div class="row-text">
                                <strong class="red">En panne</strong>
                                @if(!empty($materiel->designation))
                                    : {{ $materiel->designation }}
                                @elseif(!empty($materiel->numero_serie))
                                    : {{ $materiel->numero_serie }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                @if(!empty($alertes_anomalies['repartitions_non_livrees']) && count($alertes_anomalies['repartitions_non_livrees']))
                    @foreach($alertes_anomalies['repartitions_non_livrees'] as $repartition)
                        <div class="row-item">
                            <div class="badge-round bg-orange">!</div>
                            <div class="row-text">
                                <strong class="orange">Retard livraison</strong>
                                @if(!empty($repartition->siteDestination?->nom))
                                    : {{ $repartition->siteDestination->nom }}
                                @endif
                                @if(!empty($repartition->statut))
                                    — {{ $repartition->statut }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                @if(
                    (empty($alertes_anomalies['materiels_en_panne']) || count($alertes_anomalies['materiels_en_panne']) === 0)
                    &&
                    (empty($alertes_anomalies['repartitions_non_livrees']) || count($alertes_anomalies['repartitions_non_livrees']) === 0)
                )
                    <div class="empty-state">Aucune anomalie détectée.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Rapports détaillés</h3>

                <div class="report-list">
                    <div class="report-row">
                        <div class="report-label">Inventaire complet</div>
                        <div class="report-value">{{ $rapports['inventaire_complet'] ?? 0 }}</div>
                    </div>
                    <div class="report-row">
                        <div class="report-label">Historique des mouvements</div>
                        <div class="report-value">{{ $rapports['historique_mouvements'] ?? 0 }}</div>
                    </div>
                    <div class="report-row">
                        <div class="report-label">Audit utilisateurs</div>
                        <div class="report-value">{{ $rapports['audit_utilisateurs'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Actions</h3>

                <div class="quick-actions">
                    <button class="quick-btn quick-blue" type="button">Exporter CSV</button>
                    <button class="quick-btn quick-green" type="button">Générer rapport</button>
                    <button class="quick-btn quick-purple" type="button">Historique complet</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection