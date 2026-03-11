@extends('partials.admin.master')

@section('content')
    <div class="container-fluid">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <div>
                <h1 class="h3 text-gray-800">✏️ Modifier l’utilisateur</h1>
                <p class="text-muted mb-0">Édition du compte utilisateur</p>
            </div>

            <a href="{{ route('user.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- FORM CARD -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">
                    Formulaire de modification
                </h6>
            </div>

            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Nom -->
                        <div class="col-md-6 mb-3">
                            <label>Nom</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <!-- Rôle -->
                        <div class="col-md-6 mb-3">
                            <label>Rôle</label>
                            <select name="role_id" class="form-control">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mot de passe -->
                        <div class="col-md-6 mb-3">
                            <label>Mot de passe (laisser vide pour ne pas changer)</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                        </div>

                        <!-- Bouton -->
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>


            </div>
        </div>

    </div>
@endsection
