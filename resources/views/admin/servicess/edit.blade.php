@extends('partials.admin.master')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Modifier un service</h1>
        <a href="{{ route('service.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <!-- Card Form -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Modification du service</h6>
        </div>

        <div class="card-body">
            <form action="{{ route('service.update', $service->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="Designation" class="form-label">
                            Nom du service <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="Designation"
                            id="Designation"
                            class="form-control"
                            value="{{ old('Designation', $service->Designation) }}"
                            required
                        >
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
