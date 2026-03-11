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
        <h1 class="h3 text-gray-800">Ajouter un fournisseur</h1>
        <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Card Form -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Nouveau fournisseur</h6>
        </div>
        <div class="card-body">
            <form action="{{ route("fournisseurs.store") }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom du fournisseur <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Entrez le nom du fournisseur" required>
                    </div>
                    <div class="col-md-6">
                        <label for="texte" class="form-label">Adresse<span class="text-danger">*</span></label>
                        <input type="texte" name="Adresse" id="Adresse" class="form-control" placeholder="Adresse du fournisseur">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" name="Contact" id="Contact" class="form-control" placeholder="Ex : +223 76 00 00 00" required>
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
