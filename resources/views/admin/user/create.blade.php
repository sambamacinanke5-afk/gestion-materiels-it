@extends('partials.admin.master')

@section('content')
    <div class="container-fluid">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div>
                <h1 class="h3 text-gray-800">➕ Ajouter un utilisateur</h1>
                <p class="text-muted mb-0">Création d’un compte utilisateur</p>
            </div>

            <a href="{{ route('user.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- FORM CARD -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">
                    Formulaire utilisateur
                </h6>
            </div>

            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <!-- NOM -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Nom <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <!-- EMAIL -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <!-- MOT DE PASSE -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Mot de passe <span class="text-danger">*</span>
                            </label>
                            <input class="form-control" type="password" name="password" value="!217ms@B" readonly>
                        </div>

                        <!-- ROLE -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                Rôle <span class="text-danger">*</span>
                            </label>

                            <select name="role_id" class="form-control" required>
                                <option value="">Sélectionner un rôle</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ strtoupper($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <!-- ACTIONS -->
                    <div class="text-center mt-4">
                        <a href="{{ route('user.index') }}" class="btn btn-outline-danger me-2">
                            Annuler
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Ajouter
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection
