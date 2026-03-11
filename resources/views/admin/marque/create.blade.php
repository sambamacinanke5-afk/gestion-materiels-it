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
        <h1 class="h3 text-gray-800">Ajouter une marque</h1>
        <a href="{{ route('marque.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Card Form -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Nouveau marque</h6>
        </div>
        <div class="card-body">
            <form action="{{ route("marque.store") }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Designation" class="form-label">Nom du Marque <span class="text-danger">*</span></label>
                        <input type="text" name="Designation" id="Designation" class="form-control" placeholder="Entrez le nom du marque" required>
                    </div>

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
