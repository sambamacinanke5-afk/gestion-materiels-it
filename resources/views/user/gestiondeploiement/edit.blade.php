@extends('partials.admin.master')

@section('content')

<!-- Fonts & Styles -->
<link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
<link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

<div class="container-fluid mt-4">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Modifier le matériel</h1>
        <a href="{{ route('materiel.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Card Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Modifier les informations du matériel</h6>
        </div>

        <div class="card-body">
            <form action="{{ route('materiel.update', $materiel->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="row mb-3">
                    <!-- Désignation -->
                    <div class="col-md-6">
                        <label for="Designation" class="form-label">
                            Désignation <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="Designation" id="Designation"
                               class="form-control @error('Designation') is-invalid @enderror"
                               value="{{ old('Designation', $materiel->Designation) }}" required>
                        @error('Designation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Numéro de série -->
                    <div class="col-md-6">
                        <label for="Numerodeserie" class="form-label">
                            Numéro de série <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="Numerodeserie" id="Numerodeserie"
                               class="form-control @error('Numerodeserie') is-invalid @enderror"
                               value="{{ old('Numerodeserie', $materiel->Numerodeserie) }}" required>
                        @error('Numerodeserie')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Type de matériel -->
                    <div class="col-md-6">
                        <label for="typemateriel_id" class="form-label">
                            Type de matériel <span class="text-danger">*</span>
                        </label>
                        <select id="typemateriel_id" name="typemateriel_id"
                                class="form-control @error('typemateriel_id') is-invalid @enderror" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach ($typemateriels as $typemateriel)
                                <option value="{{ $typemateriel->id }}"
                                    {{ $materiel->typemateriel_id == $typemateriel->id ? 'selected' : '' }}>
                                    {{ $typemateriel->Designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('typemateriel_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Marque -->
                    <div class="col-md-6">
                        <label for="marque_id" class="form-label">
                            Marque <span class="text-danger">*</span>
                        </label>
                        <select id="marque_id" name="marque_id"
                                class="form-control @error('marque_id') is-invalid @enderror" required>
                            <option value="">Sélectionnez une marque</option>
                            @foreach ($marques as $marque)
                                <option value="{{ $marque->id }}"
                                    {{ $materiel->marque_id == $marque->id ? 'selected' : '' }}>
                                    {{ $marque->Designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('marque_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('materiel.index') }}" class="btn btn-warning">
                        <i class="fas fa-undo"></i> Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
