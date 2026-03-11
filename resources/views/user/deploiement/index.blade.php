@extends('partials.user.master')
@section('content')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Liste des déploiements</h1>
        <a href="{{ route('user.deploiement.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter des déploiements
        </a>
    </div>
    <p class="mb-4">Liste complète des déploiements enregistrés dans le système.</p>

    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Table des déploiements</h6>
        </div>
        <div class="card-body">
            @if($deploiements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="deploiementsTable">
                        <thead class="table-light">
                            <tr>
                                <th>RÉFÉRENCE BON DE LIVRAISON</th>
                                <th>DATE DE CREATION</th>
                                <th>UTILISATEUR</th>
                                <th>ÉTAT</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deploiements as $deploiement)
                                <tr>
                                    <td>{{ $deploiement->bondelivraison->bondelivraison ?? 'Non défini' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($deploiement->Datecreation)->format('d/m/Y') }}</td>
                                    <td>{{ $deploiement->Utilisateur }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $deploiement->etat)) }}</td>
                                    <td>
                                        <a href="{{ route('user.deploiement.show', $deploiement->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('user.deploiement.edit', $deploiement->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('user.deploiement.destroy', $deploiement->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce déploiement ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $deploiements->links() }}
                </div>
            @else
                <div class="alert alert-warning text-center">
                    Aucun déploiement trouvé.
                </div>
            @endif
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
    $(document).ready(function() {
        $('#deploiementsTable').DataTable({
            "order": [[1, "desc"]], // trier par date de création
            "pageLength": 10
        });
    });
</script>

@endsection
