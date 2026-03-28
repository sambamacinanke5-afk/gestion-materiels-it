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

        .btn-primary-clean:hover {
            background: #1d4ed8;
        }

        .card-clean {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
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

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
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
            <h1 class="page-title">Fournisseurs</h1>

            <a href="{{ route('fournisseurs.create') }}" class="btn-primary-clean">
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
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>email</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($fournisseurs as $fournisseur)
                            <tr>

                                <td>{{ $fournisseur->nom }}</td>

                                <!-- ✅ UN SEUL CHAMP TELEPHONE -->
                                <td>{{ $fournisseur->telephone ?? '-' }}</td>

                                <td>{{ $fournisseur->adresse ?? '-' }}</td>
                                <td>{{ $fournisseur->email ?? '-' }}</td>


                                <td>
                                    @if($fournisseur->actif)
                                        <span class="badge-success">Actif</span>
                                    @else
                                        <span class="badge-danger">Inactif</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="actions">
                                        <!-- Modifier -->
                                        <a href="{{ route('fournisseurs.edit', $fournisseur->id) }}" class="btn-edit">
                                            Modifier
                                        </a>

                                        <!-- Supprimer -->
                                        <form action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" method="POST"
                                            onsubmit="return confirm('Supprimer ce fournisseur ?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-delete">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty">
                                    Aucun fournisseur trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
