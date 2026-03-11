@extends('partials.admin.master')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div>
                <h1 class="h3 text-gray-800">👥 Liste des utilisateurs</h1>
                <p class="text-muted mb-0">Gestion complète des comptes utilisateurs</p>
            </div>
            <a href="{{ route('user.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus"></i> Ajouter un utilisateur
            </a>
        </div>

        {{-- CARD --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">
                    Tableau des utilisateurs
                </h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td class="text-muted">{{ $user->email }}</td>
                                    <td>
                                        <span
                                            class="badge role-badge
                                    {{ $user->role->name === 'admin' ? 'role-admin' : 'role-user' }}">
                                            {{ strtoupper($user->role->name) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($user->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Suspendu</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{-- Éditer --}}
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Suspendre / Activer via POST --}}
                                        @if ($user->role->name !== 'admin')
                                            <form action="{{ route('user.toggleStatus', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $user->is_active ? 'Suspendre' : 'Activer' }}
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Supprimer --}}
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Supprimer cet utilisateur ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= STYLES ================= --}}
    <style>
        .role-badge {
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
            color: white;
            letter-spacing: 0.5px;
        }

        .role-admin {
            background: linear-gradient(45deg, #4e73df, #224abe);
            box-shadow: 0 2px 5px rgba(78, 115, 223, 0.4);
        }

        .role-user {
            background: linear-gradient(45deg, #1cc88a, #13855c);
            box-shadow: 0 2px 5px rgba(28, 200, 138, 0.4);
        }

        table tbody tr:hover {
            background-color: #f8f9fc;
        }
    </style>

    {{-- ================= SCRIPTS ================= --}}
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 10,
                dom: 'lfrtip',
                language: {
                    lengthMenu: "Afficher _MENU_ entrées",
                    search: "🔍 Rechercher :",
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ utilisateurs",
                    zeroRecords: "Aucun utilisateur trouvé",
                    paginate: {
                        previous: "Précédent",
                        next: "Suivant"
                    }
                }
            });
        });
    </script>
@endsection
