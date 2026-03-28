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
        <h1 class="page-title">Marques</h1>

        <a href="{{ route('marque.create') }}" class="btn-primary-clean">
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
                        {{-- <th>#</th> --}}
                        <th>Désignation</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($marques as $marque)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $marque->Designation }}</td>

                            <td>
                                <div class="actions">

                                    <!-- Modifier -->
                                    <a href="{{ route('marque.edit', $marque->id) }}" class="btn-edit">
                                        Modifier
                                    </a>

                                    <!-- Supprimer -->
                                    <form action="{{ route('marque.destroy', $marque->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer cette marque ?')">
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
                            <td colspan="3" class="empty">
                                Aucune marque trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
