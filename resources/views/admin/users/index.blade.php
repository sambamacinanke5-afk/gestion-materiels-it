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
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .page-subtitle {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .btn-primary-clean {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .btn-primary-clean:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .card-clean {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .card-clean-body {
        padding: 20px;
    }

    .table-wrapper {
        overflow-y: auto;
        max-height: calc(100vh - 220px);
        border-radius: 12px;
        border: 1px solid #f1f5f9;
    }

    .table-clean {
        width: 100%;
        border-collapse: collapse;
    }

    .table-clean thead th {
        position: sticky;
        top: 0;
        background: #f9fafb;
        z-index: 2;
        color: #374151;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-clean tbody td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #374151;
        vertical-align: top;
    }

    .table-clean tbody tr:hover {
        background: #f8fafc;
    }

    .user-name {
        font-weight: 700;
        color: #111827;
    }

    .user-email {
        color: #6b7280;
        font-size: 13px;
    }

    .role-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .badge-clean {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-info {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-delete {
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }
</style>

<div class="admin-page">
    <div class="page-header">
        <div>
            <h1 class="page-title">Gestion des utilisateurs</h1>
            <p class="page-subtitle">Crée les utilisateurs et attribue leurs rôles.</p>
        </div>

        @can('users.create')
            <a href="{{ route('admin.users.create') }}" class="btn-primary-clean">
                <i class="fa-solid fa-plus"></i>
                Ajouter
            </a>
        @endcan
    </div>

    <div class="card-clean">
        <div class="card-clean-body">
            <div class="table-wrapper">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôles</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </td>

                                <td>
                                    <div class="role-list">
                                        @forelse($user->roles as $role)
                                            <span class="badge-clean badge-info">{{ $role->name }}</span>
                                        @empty
                                            <span class="badge-clean badge-secondary">Aucun rôle</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    @if($user->is_suspended)
                                        <span class="badge-clean badge-secondary">Suspendu</span>
                                    @else
                                        <span class="badge-clean badge-info">Actif</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="actions">
                                        @can('users.reset-password')
                                            <form action="{{ route('admin.users.send-password-reset', $user) }}" method="POST">
                                                @csrf
                                                <button class="btn-edit" onclick="return confirm('Envoyer un email de réinitialisation à cet utilisateur ?')">
                                                    Réinitialiser MDP
                                                </button>
                                            </form>
                                        @endcan

                                        @can('users.suspend')
                                            @if(!$user->is_suspended && auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn-delete" onclick="return confirm('Suspendre cet utilisateur ?')">
                                                        Suspendre
                                                    </button>
                                                </form>
                                            @elseif($user->is_suspended)
                                                <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn-edit" onclick="return confirm('Réactiver cet utilisateur ?')">
                                                        Réactiver
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan



                                        @can('users.update')
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-edit">
                                                Modifier
                                            </a>
                                        @endcan

                                        @can('users.delete')
                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn-delete" onclick="return confirm('Supprimer cet utilisateur ?')">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">Aucun utilisateur disponible</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection