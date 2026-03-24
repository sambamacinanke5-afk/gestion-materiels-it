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

    .grid-4{
        display:grid;
        grid-template-columns:repeat(4, 1fr);
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
        border: 1px solid #eef2f7;
        transition: 0.2s;
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

    .kpi-sub{
        display:block;
        font-size:10px;
        color:#94a3b8;
        margin-top:-2px;
        margin-bottom:3px;
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

    .alert-row,
    .activity-row,
    .site-row{
        display:flex;
        align-items:center;
        gap:10px;
        padding:6px 0;
        border-top:1px solid var(--line);
    }

    .alert-row:first-child,
    .activity-row:first-child,
    .site-row:first-child{
        border-top:none;
        padding-top:0;
    }

    .alert-badge{
        width:24px;
        height:24px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#fff;
        font-size:12px;
        font-weight:800;
        flex-shrink:0;
    }

    .alert-text,
    .activity-text,
    .site-text{
        font-size:13px;
        color:#334155;
        line-height:1.25;
    }

    .alert-text strong.red{ color:#dc2626; }
    .alert-text strong.orange{ color:#f59e0b; }

    .flow-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .flow-step {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eef2f7;
        text-align: center;
        min-width: 90px;
    }

    .flow-head {
        padding: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        text-align: center;
        line-height: 1.15;
        min-height: 34px;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .flow-value {
        padding: 8px;
        font-size: 16px;
        font-weight: 800;
        color: #1f2a44;
    }

    .activity-badge{
        width:20px;
        height:20px;
        border-radius:50%;
        background:#eaf8ef;
        color:#16a34a;
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
        font-weight:800;
        font-size:10px;
    }

    .empty-state{
        color:#98a2b3;
        font-style:italic;
        font-size:12px;
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

    .count-strong{
        margin-left:auto;
        font-weight:800;
        color:var(--text);
    }

    @media (max-width: 1200px){
        .grid-4{ grid-template-columns:repeat(2, 1fr); }
    }

    @media (max-width: 900px){
        .dashboard-header,
        .grid-2{
            grid-template-columns:1fr;
            display:grid;
        }

        .grid-4,
        .flow-grid{
            grid-template-columns:1fr;
        }

        .dashboard-title{
            font-size:22px;
        }
    }
</style>

<div class="dashboard-page">
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">{{ $pageTitle ?? 'Tableau de bord Logistique' }}</h1>
            <p class="dashboard-subtitle">Suivi logistique des répartitions et livraisons</p>
        </div>

        <div class="dashboard-tools">
            <form method="GET" action="{{ route('dashboard.mg') }}">
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

    <div class="grid-4">
        <div class="card kpi-card">
            <div class="card-body kpi-content">
                <div class="kpi-icon bg-blue"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <p class="kpi-label">Validés par IT</p>
                    <p class="kpi-value">{{ $kpis['materiels_valides_it'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card kpi-card">
            <div class="card-body kpi-content">
                <div class="kpi-icon bg-green"><i class="fa-solid fa-truck-ramp-box"></i></div>
                <div>
                    <p class="kpi-label">À répartir MG</p>
                    <p class="kpi-value">{{ $kpis['a_repartir_mg'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card kpi-card">
            <div class="card-body kpi-content">
                <div class="kpi-icon bg-orange"><i class="fa-solid fa-route"></i></div>
                <div>
                    <p class="kpi-label">Répartitions en cours</p>
                    <p class="kpi-value">{{ $kpis['repartitions_en_cours'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="card kpi-card">
            <div class="card-body kpi-content">
                <div class="kpi-icon bg-purple"><i class="fa-solid fa-clipboard-check"></i></div>
                <div>
                    <p class="kpi-label">Répartitions livrées</p>
                    <p class="kpi-value">{{ $kpis['repartitions_livrees'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Actions urgentes</h3>

                <div class="alert-row">
                    <div class="alert-badge bg-red">!</div>
                    <div class="alert-text">
                        <strong class="red">{{ $actions_urgentes['non_repartis'] ?? 0 }}</strong>
                        matériels non répartis
                    </div>
                </div>

                <div class="alert-row">
                    <div class="alert-badge bg-orange">!</div>
                    <div class="alert-text">
                        <strong class="orange">{{ $actions_urgentes['attente_livraison'] ?? 0 }}</strong>
                        répartitions en attente de livraison
                    </div>
                </div>

                <div class="alert-row">
                    <div class="alert-badge bg-red">!</div>
                    <div class="alert-text">
                        <strong class="red">{{ $actions_urgentes['livraisons_en_retard'] ?? 0 }}</strong>
                        livraisons en retard
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Flux logistique</h3>

                <div class="flow-grid">
                    <div class="flow-step">
                        <div class="flow-head bg-blue">Validation IT</div>
                        <div class="flow-value">{{ $flux['validation_it'] ?? 0 }}</div>
                    </div>

                    <div class="flow-step">
                        <div class="flow-head bg-green">Répartition</div>
                        <div class="flow-value">{{ $flux['repartition'] ?? 0 }}</div>
                    </div>

                    <div class="flow-step">
                        <div class="flow-head bg-purple">Livraison</div>
                        <div class="flow-value">{{ $flux['livraison'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Matériels à répartir</h3>

                @if(isset($materiels_a_repartir) && count($materiels_a_repartir))
                    @foreach($materiels_a_repartir as $item)
                        <div class="site-row">
                            <div class="site-text">
                                {{ $item->categorie->nom ?? 'Catégorie' }}
                            </div>
                            <div class="count-strong">{{ $item->total ?? 0 }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">Aucun matériel en attente de répartition.</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Répartition par site</h3>

                @if(isset($repartition_par_site) && count($repartition_par_site))
                    @foreach($repartition_par_site as $site)
                        <div class="site-row">
                            <div class="site-text">{{ $site->nom ?? 'Site' }}</div>
                            <div class="count-strong">{{ $site->total ?? 0 }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">Aucune donnée de répartition par site.</div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Activité récente</h3>

                @if(isset($activites_recentes) && count($activites_recentes))
                    @foreach($activites_recentes as $activite)
                        <div class="activity-row">
                            <div class="activity-badge">✓</div>
                            <div class="activity-text">
                                <strong>{{ ucfirst($activite->type_mouvement ?? 'activité') }}</strong>
                                @if(!empty($activite->materiel?->designation))
                                    : {{ $activite->materiel->designation }}
                                @endif
                                @if(!empty($activite->commentaire))
                                    — {{ $activite->commentaire }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">Aucune activité récente.</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Actions rapides</h3>

                <div class="quick-actions">
                    <button class="quick-btn quick-blue" type="button">Nouvelle Répartition</button>
                    <button class="quick-btn quick-green" type="button">Voir à répartir</button>
                    <button class="quick-btn quick-orange" type="button">Confirmer livraison</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection