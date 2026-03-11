@extends('partials.admin.master')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Modification d'un fournisseur</h1>
        <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- End Flash Messages -->

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card Form -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Modifier un fournisseur</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('fournisseurs.update', $fournisseur->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- 🔹 IMPORTANT pour Laravel Update -->

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom du fournisseur <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Entrez le nom du fournisseur" required value="{{ old('nom', $fournisseur->nom) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="Adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                        <input type="text" name="Adresse" id="Adresse" class="form-control" placeholder="Adresse du fournisseur" required value="{{ old('Adresse', $fournisseur->Adresse) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Contact" class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" name="Contact" id="Contact" class="form-control" placeholder="Ex : +223 76 00 00 00" required value="{{ old('Contact', $fournisseur->Contact) }}">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Modifier
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
