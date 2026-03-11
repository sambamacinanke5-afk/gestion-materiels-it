@extends('partials.admin.master')

@section('content')
<div class="container-fluid">

    <!-- Titre -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 text-gray-800">Liste des machines (déployées / non déployées)</h1>
        {{-- <a href="#" class="btn btn-primary">
            <i class="fas fa-plus"></i>
        </a> --}}
    </div>

    <p class="mb-4">
        Liste complète des machines enregistrées dans le système, qu’elles soient déployées ou non.
    </p>

    <!-- Table des machines -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Table des machines</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Matériel</th>
                            <th>Numéro de série</th>
                            <th>Type</th>
                            <th>Marque</th>
                            <th>Bon de livraison</th>
                            <th>État</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materiels as $materiel)
                            <tr>
                                <td>{{ $materiel->designation }}</td>
                                <td>{{ $materiel->numero_serie }}</td>
                                <td>{{ $materiel->type }}</td>
                                <td>{{ $materiel->marque }}</td>
                                <td>{{ $materiel->bondelivraison ?? '-' }}</td>
                                <td>
                                    @if($materiel->etat == 'Déployée')
                                        <span class="badge badge-success">{{ $materiel->etat }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $materiel->etat }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Résumé des totaux -->
                <div class="mt-4">
                    <h4>Résumé :</h4>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="card text-white bg-danger shadow">
                                <div class="card-body">
                                    <strong>Total matériels NON déployés :</strong>
                                    <span class="badge badge-light float-right">{{ $totaux['non_deploye_total'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4 mb-2">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <strong>Ordinateurs déployés :</strong>
                                    <span class="badge badge-light float-right">{{ $totaux['ordinateur_deploye'] }}</span>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-4 mb-2">
                            <div class="card text-white bg-warning shadow">
                                <div class="card-body">
                                    <strong>Ordinateurs non déployés :</strong>
                                    <span class="badge badge-light float-right">{{ $totaux['ordinateur_non_deploye'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4 mb-2">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <strong>Imprimantes déployées :</strong>
                                    <span class="badge badge-light float-right">{{ $totaux['imprimante_deploye'] }}</span>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-4 mb-2">
                            <div class="card text-white bg-warning shadow">
                                <div class="card-body">
                                    <strong>Imprimantes non déployées :</strong>
                                    <span class="badge badge-light float-right">{{ $totaux['imprimante_non_deploye'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4 mb-2">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <strong>Scanners déployés :</strong>
                                    <span class="badge badge-light float-right">{{ $totaux['scanner_deploye'] }}</span>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-4 mb-2">
                            <div class="card text-white bg-warning shadow">
                                <div class="card-body">
                                    <strong>Scanners non déployés :</strong>
                                    <span class="badge badge-light float-right">{{ $totaux['scanner_non_deploye'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin résumé -->

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json"
            }
        });
    });
</script>
@endsection
