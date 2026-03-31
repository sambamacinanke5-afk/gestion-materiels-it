@extends('layouts.app')
@section('content')

<style>
.admin-page {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 20px;
}

.card-clean {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    max-width: 900px;
}

.card-clean-body {
    padding: 24px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 20px;
}

.form-label-clean {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #374151;
}

.form-control-clean {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ddd;
}

.form-control-clean:focus {
    border-color: #2563eb;
    box-shadow: 0 0 5px rgba(37,99,235,0.3);
}

.actions-bar {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-primary-clean {
    background: #16a34a;
    color: #fff;
    padding: 10px 16px;
    border-radius: 10px;
    border: none;
}

.btn-secondary-clean {
    background: #e5e7eb;
    padding: 10px 16px;
    border-radius: 10px;
}
</style>

<div class="admin-page">

    <h1 class="page-title">Ajouter un matériel</h1>

    {{-- ERREURS --}}
    @if ($errors->any())
        <div class="alert-clean" style="background:#fee2e2;padding:12px;border-radius:10px;margin-bottom:15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-clean">
        <div class="card-clean-body">

            <form action="{{ route('materiels.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div>
                        <label for="code_inventaire" class="form-label-clean">Code inventaire <span class="text-danger">*</span></label>
                        <input type="text" name="code_inventaire" id="code_inventaire"
                               class="form-control-clean @error('code_inventaire') is-invalid @enderror"
                               value="{{ old('code_inventaire') }}" required>
                        @error('code_inventaire')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div>
                        <label for="numero_serie" class="form-label-clean">Numéro de série</label>
                        <input type="text" name="numero_serie" id="numero_serie"
                               class="form-control-clean @error('numero_serie') is-invalid @enderror"
                               value="{{ old('numero_serie') }}">
                        @error('numero_serie')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div>
                        <label for="typemateriel_id" class="form-label-clean">Type de matériel <span class="text-danger">*</span></label>
                        <select name="typemateriel_id" id="typemateriel_id"
                                class="form-control-clean @error('typemateriel_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionnez --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{ old('typemateriel_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('typemateriel_id')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div>
                        <label for="marque_id" class="form-label-clean">Marque <span class="text-danger">*</span></label>
                        <select name="marque_id" id="marque_id"
                                class="form-control-clean @error('marque_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionnez --</option>
                            @foreach ($marques as $marque)
                                <option value="{{ $marque->id }}" {{ old('marque_id') == $marque->id ? 'selected' : '' }}>
                                    {{ $marque->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('marque_id')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="actions-bar">
                    <a href="{{ route('materiels.index') }}" class="btn-secondary-clean">Annuler</a>
                    <button type="submit" class="btn-primary-clean">Enregistrer</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
