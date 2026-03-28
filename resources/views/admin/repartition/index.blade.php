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
        <h1 class="page-title">Répartitions</h1>
        <a href="{{ route('repartition.create') }}" class="btn-primary-clean">
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

                        <th>Numéro Répartition</th>
                        <th>Site Destination</th>
                        <th>Service Destination</th>
                        <th>Date de répartition</th>
                        <th>Lignes de matériel</th>
                        <th>Quantité totale</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($repartitions as $repartition)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $repartition->numero }}</td>
                        <td>{{ $repartition->siteDestination->name ?? '-' }}</td>
                        <td>{{ $repartition->serviceDestination->Designation ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($repartition->date_repartition)->format('d/m/Y') }}</td>

                        <!-- Lignes de matériel -->
                        <td>
                            @foreach ($repartition->lignes as $ligne)
                                <span class="badge badge-secondary">
                                    {{ $ligne->materiel->designation ?? 'N/A' }}
                                </span>
                            @endforeach
                        </td>

                        <!-- Quantité totale -->
                        <td>{{ $repartition->lignes->sum('quantite') }}</td>

                        <!-- Statut -->
                        <td>
                            @if($repartition->statut == 'brouillon')
                                <span class="badge-secondary">Brouillon</span>
                            @elseif($repartition->statut == 'en_cours')
                                <span class="badge-warning">En cours</span>
                            @elseif($repartition->statut == 'terminee')
                                <span class="badge-success">Terminée</span>
                            @else
                                <span class="badge-secondary">{{ ucfirst($repartition->statut) }}</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="actions">
                                <a href="{{ route('repartition.show', $repartition->id) }}" class="btn-show">Voir</a>
                                <a href="{{ route('repartition.edit', $repartition->id) }}" class="btn-edit">Modifier</a>

                                <form action="{{ route('repartition.destroy', $repartition->id) }}" method="POST" onsubmit="return confirm('Supprimer cette répartition ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-delete">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="empty">Aucune répartition trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            {{-- <div style="margin-top:15px;">
                {{ $repartitions->links() }}
            </div> --}}

        </div>
    </div>

</div>
@endsection
