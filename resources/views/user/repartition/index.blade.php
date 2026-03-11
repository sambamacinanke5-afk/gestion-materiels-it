@extends('partials.user.master')
@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <h1 class="h3 text-gray-800">Liste de Répartition des matériels informatiques</h1>
            <a href="{{ route('user.repartition.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une répartition
            </a>
        </div>

        <p class="mb-4">Liste complète des répartitions des matériels informatiques enregistrées dans le système.</p>

        <div class="card shadow mb-4 mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Table de répartition</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Fournisseur</th>
                                <th>Référence BL</th>
                                <th>Date de répartition</th>
                                {{-- <th>Matériels répartis</th> --}}
                                <th>Destinataire(s)</th>
                                <th>Quantité par ligne</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($repartitions as $repartition)
                                <tr>

                                    <!-- Fournisseur -->
                                    <td>{{ $repartition->bondelivraison->fournisseur->nom ?? 'N/A' }}</td>

                                    <!-- Référence BL -->
                                    <td>{{ $repartition->bondelivraison->bondelivraison ?? 'N/A' }}</td>

                                    <!-- Date de répartition -->
                                    <td>{{ \Carbon\Carbon::parse($repartition->date_repartition)->format('d/m/Y') }}</td>

                                    <!-- Matériels -->
                                    {{-- <td>
                                        @foreach ($repartition->lignes as $ligne)
                                            @php
                                                $materielIds = json_decode($ligne->lignebondelivraison_id, true);
                                                $materiels = \App\Models\Materiel::whereIn('id', $materielIds)->get();
                                            @endphp

                                            @foreach ($materiels as $mat)
                                                <span
                                                    class="badge badge-secondary">{{ $mat->designation ?? 'Non défini' }}</span>
                                            @endforeach
                                        @endforeach
                                    </td> --}}

                                    <!-- Destinataires -->
                                    <td>
                                        @foreach ($repartition->lignes as $ligne)
                                            <span class="badge badge-primary">{{ $ligne->destinataire ?? 'N/A' }}</span>
                                        @endforeach
                                    </td>

                                    <!-- Quantités -->
                                    <td>
                                        @foreach ($repartition->lignes as $ligne)
                                            <span class="badge badge-info">{{ $ligne->quantite ?? 0 }}</span>
                                        @endforeach
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <a href="{{ route('user.repartition.edit', $repartition->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="{{ route('user.repartition.show', $repartition->id) }}"
                                            class="btn btn-info btn-sm" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('user.repartition.pdf', $repartition->id) }}" target="_blank"
                                            class="btn btn-warning btn-sm" title="Imprimer le PDF">
                                            <i class="fas fa-file-pdf"></i>
                                         </a>

                                        <form action="{{ route('user.repartition.destroy', $repartition->id) }}"
                                            method="POST" style="display:inline-block;"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette répartition ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

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
                "order": [
                    [2, "desc"]
                ],
                "pageLength": 10
            });
        });
    </script>
@endsection
