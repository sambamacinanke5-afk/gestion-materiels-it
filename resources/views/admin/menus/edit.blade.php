@extends('layouts.app')

@section('content')
<style>
    .admin-page {
        padding: 24px;
        background: #f8fafc;
        min-height: 100vh;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .card-clean {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        max-width: 900px;
    }

    .card-clean-body {
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .form-label-clean {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .form-control-clean,
    .form-select-clean {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        color: #111827;
        background: #fff;
        outline: none;
        transition: 0.2s ease;
    }

    .form-control-clean:focus,
    .form-select-clean:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .form-help {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
    }

    .actions-bar {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn-primary-clean,
    .btn-secondary-clean {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
        cursor: pointer;
    }

    .btn-primary-clean {
        background: #2563eb;
        color: #fff;
    }

    .btn-primary-clean:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .btn-secondary-clean {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary-clean:hover {
        background: #d1d5db;
        color: #111827;
    }

    .alert-clean {
        max-width: 900px;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 18px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    .alert-clean ul {
        margin: 0;
        padding-left: 18px;
    }

    .menu-meta {
        max-width: 900px;
        margin-bottom: 16px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .menu-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        color: #374151;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
    }

    .menu-chip i {
        color: #2563eb;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-page">
    <div class="page-header">
        <h1 class="page-title">Modifier un menu</h1>
        <p class="page-subtitle">Mets à jour les informations du menu sélectionné.</p>
    </div>

    <div class="menu-meta">
        <span class="menu-chip">
            <i class="fa-solid fa-hashtag"></i>
            ID : {{ $menu->id }}
        </span>

        <span class="menu-chip">
            <i class="fa-solid fa-arrow-down-1-9"></i>
            Ordre actuel : {{ $menu->sort_order }}
        </span>

        <span class="menu-chip">
            <i class="fa-solid fa-circle-check"></i>
            Statut : {{ $menu->is_active ? 'Actif' : 'Inactif' }}
        </span>
    </div>

    @if($errors->any())
        <div class="alert-clean">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-clean">
        <div class="card-clean-body">
            <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.menus.form')

                <div class="actions-bar">
                    <a href="{{ route('admin.menus.index') }}" class="btn-secondary-clean">
                        <i class="fa-solid fa-arrow-left"></i>
                        Retour
                    </a>

                    <button type="submit" class="btn-primary-clean">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection