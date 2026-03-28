@extends('layouts.app')

@section('content')
<style>
    .admin-page {
        padding: 24px;
        background: #f8fafc;
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .btn-primary-clean {
        background: #2563eb;
        color: #fff;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
    }

    .card-clean {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    }

    .card-clean-body {
        padding: 20px;
    }

    .table-clean {
        width: 100%;
        border-collapse: collapse;
    }

    .table-clean th {
        text-align: left;
        font-size: 13px;
        color: #6b7280;
    }

    .table-clean th,
    .table-clean td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .badge-success {
        background: #dcfce7;
        color: #166534;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-warning {
        background: #fef9c3;
        color: #92400e;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-secondary {
        background: #e5e7eb;
        color: #374151;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .btn-show {
        background: #dcfce7;
        color: #166534;
        padding: 6px 10px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-edit {
        background: #e0f2fe;
        color: #0369a1;
        padding: 6px 10px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
        border: none;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
    }

    .empty {
        text-align: center;
        color: #6b7280;
        padding: 20px;
    }
</style>

<div class="admin-page">

    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">Déploiements</h1>

        <a href="{{ route('deploiement.create') }}" class="btn-primary-clean">
            + Ajouter
        </a>
    </div>

    <!-- Message -->
    @if(session('success'))
        <div style="margin-bottom:15px;color:green;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Card -->
    <div class="card-clean">
        <div class="card-clean-body">

            <table class="table-clean">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>BL</th>
                        <th>Fournisseur</th>
                        <th>Matériel</th>
                        <th>Bénéficiaire</th>
                        <th>Technicien</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($deploiements as $deploiement)
                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $deploiement->bondelivraison->numero_bl ?? '-' }}</td>

                            <td>{{ $deploiement->bondelivraison->fournisseur->nom ?? '-' }}</td>

                            <td>
                                {{ $deploiement->materiel->designation ?? '-' }} <br>
                                <small style="color:#6b7280;">
                                    {{ $deploiement->materiel->numero_serie ?? '' }}
                                </small>
                            </td>

                            <td>{{ $deploiement->beneficiaire->name ?? '-' }}</td>

                            <td>{{ $deploiement->technicien->name ?? '-' }}</td>

                            <td>
                                {{ $deploiement->date_deploiement
                                    ? \Carbon\Carbon::parse($deploiement->date_deploiement)->format('d/m/Y')
                                    : '-' }}
                            </td>

                            <td>
                                @if($deploiement->statut == 'effectue')
                                    <span class="badge-success">Effectué</span>
                                @elseif($deploiement->statut == 'planifie')
                                    <span class="badge-warning">Planifié</span>
                                @else
                                    <span class="badge-secondary">{{ ucfirst($deploiement->statut) }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="actions">

                                    <a href="{{ route('deploiement.show', $deploiement->id) }}" class="btn-show">
                                        Voir
                                    </a>

                                    <a href="{{ route('deploiement.edit', $deploiement->id) }}" class="btn-edit">
                                        Modifier
                                    </a>

                                    <form action="{{ route('deploiement.destroy', $deploiement->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ce déploiement ?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn-delete">
                                            Supprimer
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty">
                                Aucun déploiement trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div style="margin-top:15px;">
                {{ $deploiements->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
