@extends('partials.user.master')
@section('content')

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Liste des Bons de Livraison</h1>
        {{-- <a href="{{ route('user.bondelivraison.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un Bon de Livraison
        </a> --}}
    </div>
    <p class="mb-4">Liste complète des bons de livraison enregistrés dans le système.</p>

    <!-- Tableau des BL -->
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Table des BL</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fournisseur</th>
                            <th>Nom du bon / Référence</th>
                            <th>Date de livraison</th>
                            {{-- <th>Désignation du matériel</th>
                            <th>Lignes du bon de livraison</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bondelivraisons as $bon)
                            <tr>
                                {{-- Fournisseur --}}
                                <td>{{ $bon->fournisseur->nom ?? 'Non défini' }}</td>

                                {{-- Référence du bon --}}
                                <td>{{ $bon->bondelivraison ?? 'Non défini' }}</td>

                                {{-- Date de livraison --}}
                                <td>{{ $bon->date_livraison ? \Carbon\Carbon::parse($bon->date_livraison)->format('d/m/Y') : 'Non définie' }}</td>

                                {{-- Désignation du matériel (liste compacte) --}}
                                {{-- <td>
                                    @forelse($bon->lignes as $ligne)
                                        {{ $ligne->materiel->designation ?? 'Matériel non défini' }}<br>
                                    @empty
                                        <span class="text-muted">Aucun matériel</span>
                                    @endforelse
                                </td> --}}

                                {{-- Lignes du BL détaillées --}}
                                {{-- <td>
                                    @forelse($bon->lignes as $ligne)
                                        Ligne n°{{ $loop->iteration }} —
                                        Matériel : {{ $ligne->materiel->designation ?? 'Non défini' }} —
                                        N° série : {{ $ligne->materiel->numero_serie ?? 'N/A' }}<br>
                                    @empty
                                        <span class="text-muted">Aucune ligne</span>
                                    @endforelse
                                </td> --}}

                                {{-- Actions --}}
                                <td class="text-center">
                                    <a href="{{ route('user.bondelivraison.show', $bon->id) }}" class="btn btn-info btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- <a href="{{ route('user.bondelivraison.edit', $bon->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a> --}}

                                    {{-- <form action="{{ route('user.bondelivraison.destroy', $bon->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce bon ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $bondelivraisons->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 2, "desc" ]], // Trie par date de livraison décroissante
            "pageLength": 10
        });
    });
</script>

@endsection
