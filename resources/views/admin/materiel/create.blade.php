@extends('partials.admin.master')
@section('content')

<link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
<link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Ajouter un matériel</h1>
        <a href="{{ route('materiel.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Nouveau matériel</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('materiel.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                        <input type="text" name="Designation" id="Designation"
                               class="form-control @error('Designation') is-invalid @enderror"
                               placeholder="Entrez la désignation du matériel"
                               value="{{ old('Designation') }}" required>
                        @error('Designation')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="Numerodeserie" class="form-label">Numéro de série <span class="text-danger">*</span></label>
                        <input type="text" name="Numerodeserie" id="Numerodeserie"
                               class="form-control @error('Numerodeserie') is-invalid @enderror"
                               placeholder="Entrez le numéro de série"
                               value="{{ old('Numerodeserie') }}" required>
                        @error('Numerodeserie')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="typemateriel_id" class="form-label">Type de matériel <span class="text-danger">*</span></label>
                        <select name="typemateriel_id" id="typemateriel_id"
                                class="form-control @error('typemateriel_id') is-invalid @enderror" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach ($typemateriels as $typemateriel)
                                <option value="{{ $typemateriel->id }}" {{ old('typemateriel_id') == $typemateriel->id ? 'selected' : '' }}>
                                    {{ $typemateriel->Designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('typemateriel_id')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="marque_id" class="form-label">Marque <span class="text-danger">*</span></label>
                        <select name="marque_id" id="marque_id"
                                class="form-control @error('marque_id') is-invalid @enderror" required>
                            <option value="">Sélectionnez une marque</option>
                            @foreach ($marques as $marque)
                                <option value="{{ $marque->id }}" {{ old('marque_id') == $marque->id ? 'selected' : '' }}>
                                    {{ $marque->Designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('marque_id')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <button type="reset" class="btn btn-warning">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
