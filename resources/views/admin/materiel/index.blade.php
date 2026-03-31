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

    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
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
        <h1 class="page-title">Liste des Matériels</h1>

        <a href="{{ route('materiels.create') }}" class="btn-primary-clean">
            + Ajouter un matériel
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
                        <th>Code inventaire</th>
                        <th>Numéro de série</th>
                        <th>Modèle</th>
                        <th>Type</th>
                        <th>Marque</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($materiels as $materiel)
                        <tr>
                            <td>{{ $materiel->code_inventaire }}</td>
                            <td>{{ $materiel->numero_serie ?? '-' }}</td>
                            <td>{{ $materiel->modele ?? '-' }}</td>
                            <td>{{ $materiel->typemateriel->nom ?? 'Non défini' }}</td>
                            <td>{{ $materiel->marque->nom ?? 'Non définie' }}</td>
                            <td>{{ $materiel->categorie->nom ?? 'Non définie' }}</td>
                            <td>
                                @php
                                    $statusClass = match($materiel->statut) {
                                        'recu' => 'badge-success',
                                        'affecte' => 'badge-primary',
                                        'en_panne' => 'badge-danger',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($materiel->statut) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('materiels.edit', $materiel->id) }}" class="btn-edit">
                                        Modifier
                                    </a>
                                    <form action="{{ route('materiels.destroy', $materiel->id) }}" method="POST" onsubmit="return confirm('Supprimer ce matériel ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty">
                                Aucun matériel trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
