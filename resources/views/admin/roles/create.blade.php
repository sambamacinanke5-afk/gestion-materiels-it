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

    .alert-clean {
        max-width: 1000px;
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
</style>

<div class="admin-page">
    <div class="page-header">
        <h1 class="page-title">Créer un rôle</h1>
        <p class="page-subtitle">Ajoute un nouveau rôle et ses permissions.</p>
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

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        @include('admin.roles.form')
    </form>
</div>
@endsection