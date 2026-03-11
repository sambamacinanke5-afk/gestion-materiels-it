@extends('partials.admin.master')

@section('content')

<!-- Custom fonts for this template-->
<link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
<!-- Custom styles for this template-->
<link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Ajouter un materiel</h1>
        <a href="{{ route('marque.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Card Form -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Nouveau type materiel</h6>
        </div>
        <div class="card-body">
            <form action="{{ route("gestionmateriel.store") }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Designation" class="form-label">Designation Du Materiel <span class="text-danger">*</span></label>
                        <input type="text" name="Designation" id="Designation" class="form-control" placeholder="Entrez le type du materiel" required>
                    </div>
                    {{-- <div class="col-md-4">
                        <label style="font-size: 18px;" for="typemateriel_id">
                            <strong>Marque</strong> <span class="text-danger">*</span>
                        </label>
                        <select id="marque_id" name="marque_id"
                                class="form-control @error('marque_id') is-invalid @enderror" required>
                            <option value="">Veuillez choisir un fournisseur</option>
                            @foreach ($marques as $marque)
                                <option value="{{ $marque->id }}">{{ $marque->Designation }}</option>
                            @endforeach
                        </select>
                        @error('marque_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> --}}
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
