@extends('partials.admin.master')
    @section('content')
        <!-- Custom fonts for this template-->

        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                <h1 class="h3 text-gray-800">Liste des Marques</h1>
                <a href="{{ route('marque.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter une Marque
                </a>
            </div>
            <p class="mb-4">Liste complète des marques enregistrés dans le système.</p>
            <!-- DataTables Example -->
            <div class="card shadow mb-4 mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Table des marques</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Designation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marques as $marque)
                                    <tr>
                                        <td>{{ $marque->Designation }}</td>

                                        <td> <!-- Bouton Modifier -->
                                            <a href="{{ route('marque.edit', $marque->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <!-- Bouton Supprimer -->
                                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#delete_fournisseur{{ $marque->id }}">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Modal de confirmation -->
                                    <div class="modal fade" id="delete_fournisseur{{ $marque->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="deleteModalLabel{{ $marque->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $marque->id }}">
                                                        Confirmation de suppression
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"
                                                        aria-label="Fermer">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer une Marque
                                                    <strong>{{ $marque->Designation }}</strong> ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('marque.destroy', $marque->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Oui,
                                                            supprimer</button>
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
        </div>
        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

        <!-- Page level plugins -->
        <script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Page level custom scripts -->
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });
        </script>
    @endsection
