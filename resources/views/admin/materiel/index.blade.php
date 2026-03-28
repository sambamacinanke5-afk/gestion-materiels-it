@extends('layouts.app')
@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Liste des Matériels</h1>
        <a href="{{ route('materiels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un matériel
        </a>
    </div>

    <p class="mb-4">Liste complète des matériels enregistrés dans le système.</p>

    <!-- Table -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Table des matériels</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Code inventaire</th>
                            <th>Numéro de série</th>
                            <th>Modèle</th>
                            <th>Type</th>
                            <th>Marque</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($materiels as $materiel)
                            <tr>
                                <td>{{ $materiel->code_inventaire }}</td>
                                <td>{{ $materiel->numero_serie ?? '-' }}</td>
                                <td>{{ $materiel->modele ?? '-' }}</td>

                                <td>{{ $materiel->typemateriel->nom ?? 'Non défini' }}</td>
                                <td>{{ $materiel->marque->nom ?? 'Non définie' }}</td>
                                <td>{{ $materiel->categorie->nom ?? 'Non définie' }}</td>

                                <td>
                                    <span class="badge
                                        @if($materiel->statut == 'recu') badge-success
                                        @elseif($materiel->statut == 'affecte') badge-primary
                                        @elseif($materiel->statut == 'en_panne') badge-danger
                                        @else badge-secondary
                                        @endif">
                                        {{ ucfirst($materiel->statut) }}
                                    </span>
                                </td>

                                <td>
                                    <!-- Modifier -->
                                    <a href="{{ route('materiels.edit', $materiel->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Supprimer -->
                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                       data-target="#delete_materiel{{ $materiel->id }}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal suppression -->
                            <div class="modal fade" id="delete_materiel{{ $materiel->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Confirmation</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                &times;
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            Supprimer le matériel :
                                            <strong>{{ $materiel->code_inventaire }}</strong> ?
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>

                                            <form action="{{ route('materiels.destroy', $materiel->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });
</script>

@endsection
