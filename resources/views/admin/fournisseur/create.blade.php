@extends('layouts.app')

@section('content')
<style>
    .admin-page { padding: 24px; background: #f8fafc; min-height: 100vh; }
    .page-title { font-size: 28px; font-weight: 700; color: #1f2937; }
    .card-clean { background: #fff; border-radius: 18px; box-shadow: 0 8px 24px rgba(0,0,0,0.05); max-width: 900px; }
    .card-clean-body { padding: 24px; }
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
    .form-group-full { grid-column: 1 / -1; }
    .form-label-clean { font-weight: 600; margin-bottom: 6px; display: block; }
    .form-control-clean { width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #ddd; }
    .form-control-clean:focus { border-color: #2563eb; box-shadow: 0 0 5px rgba(37,99,235,0.3); }
    .actions-bar { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-primary-clean { background: #2563eb; color: #fff; padding: 10px 16px; border-radius: 10px; }
    .btn-secondary-clean { background: #e5e7eb; padding: 10px 16px; border-radius: 10px; }
    .alert-clean { background: #fee2e2; padding: 12px; border-radius: 10px; margin-bottom: 15px; }
</style>

<div class="admin-page">

    <h1 class="page-title mb-3">Ajouter un fournisseur</h1>

    {{-- ERREURS --}}
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

            <form action="{{ route('fournisseurs.store') }}" method="POST">
                @csrf

                <div class="form-grid">

                    <!-- Nom -->
                    <div>
                        <label class="form-label-clean">Nom *</label>
                        <input type="text" name="nom" class="form-control-clean"
                            value="{{ old('nom') }}" required>
                    </div>

                    <!-- Contact Nom -->
                    <div>
                        <label class="form-label-clean">Nom du contact</label>
                        <input type="text" name="contact_nom" class="form-control-clean"
                            value="{{ old('contact_nom') }}">
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="form-label-clean">Téléphone</label>
                        <input type="text" name="telephone" class="form-control-clean"
                            value="{{ old('telephone') }}">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="form-label-clean">Email</label>
                        <input type="email" name="email" class="form-control-clean"
                            value="{{ old('email') }}">
                    </div>

                    <!-- Adresse -->
                    <div class="form-group-full">
                        <label class="form-label-clean">Adresse</label>
                        <input type="text" name="adresse" class="form-control-clean"
                            value="{{ old('adresse') }}">
                    </div>

                    <!-- Actif -->
                    <div class="form-group-full">
                        <label>
                            <input type="checkbox" name="actif" value="1" checked>
                            Fournisseur actif
                        </label>
                    </div>

                </div>

                <div class="actions-bar">
                    <a href="{{ route('fournisseurs.index') }}" class="btn-secondary-clean">
                        Annuler
                    </a>

                    <button type="submit" class="btn-primary-clean">
                        Enregistrer
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
