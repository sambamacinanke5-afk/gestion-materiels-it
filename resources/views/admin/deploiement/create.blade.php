@extends('partials.admin.master')

@section('content')
    <div class="container mt-4">
        <h3 class="page-title mb-4"><strong>Créer un déploiement</strong></h3>

        <form action="{{ route('deploiement.store') }}" method="POST">
            @csrf

            {{-- Informations principales --}}
            <h5 class="mb-3"><strong>Informations principales</strong></h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><strong>Bon de livraison</strong></label>
                    <select name="bondelivraison_id" id="bondelivraison_id" class="form-control" required>
                        <option value="">-- Choisir un bon --</option>
                        @foreach ($bondelivraisons as $bon)
                            <option value="{{ $bon->id }}">{{ $bon->bondelivraison }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><strong>Date création</strong></label>
                    <input type="date" name="Datecreation" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><strong>État</strong></label>
                    <select name="etat" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        {{-- <option value="non_deploye">Non déployé</option>
                    <option value="en_cours">En cours</option> --}}
                        <option value="deployee">Déployé</option>
                        {{-- <option value="hors_service">Hors service</option> --}}
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <label class="form-label"><strong>Direction</strong></label>
                    <input type="text" name="Direction" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><strong>Poste</strong></label>
                    <input type="text" name="Poste" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><strong>Utilisateur</strong></label>
                    <input type="text" name="Utilisateur" class="form-control" required>
                </div>
            </div>

            {{-- Caractéristiques machine --}}
            <hr>
            <h5 class="mb-3"><strong>Caractéristiques machine</strong></h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><strong>Nom ordinateur</strong></label>
                    <input type="text" name="Nomordinateur" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><strong>Système</strong></label>
                    <input type="text" name="Systeme" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><strong>RAM</strong></label>
                    <input type="text" name="Ram" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><strong>Disque</strong></label>
                    <input type="text" name="Disque" class="form-control">
                </div>
            </div>

            {{-- Matériel --}}
            <hr>
            <h5 class="mb-3"><strong>Matériel à affecter</strong></h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Matériel (issu du bon de livraison)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="materielsCell">
                                <em class="text-muted">Sélectionnez un bon de livraison</em>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Boutons --}}
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Créer</button>
                <a href="{{ route('deploiement.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    {{-- <select name="bondelivraison_id" id="bondelivraison_id" class="form-control" required>
        <option value="">-- Choisir un bon --</option>
        @foreach ($bondelivraisons as $bon)
            <option value="{{ $bon->id }}">
                {{ $bon->numero_bl ?? 'Bon #' . $bon->id }}
            </option>
        @endforeach
    </select> --}}



    {{-- JS dynamique --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bonSelect = document.getElementById('bondelivraison_id');
            const cell = document.getElementById('materielsCell');
            const lignesByBL = @json($materielsByBL);

            function renderMateriels() {
                const blId = bonSelect.value;

                if (!blId || !lignesByBL[blId] || lignesByBL[blId].length === 0) {
                    cell.innerHTML = '<em class="text-danger">Aucun matériel disponible</em>';
                    return;
                }

                cell.innerHTML = '';
                lignesByBL[blId].forEach(ligne => {
                    cell.innerHTML += `
   <label class="d-block">
    <input type="radio"
           name="lignebondelivraison_id"
           value="${ligne.ligne_bon_livraison_id}"
           required>
    ${ligne.designation} - ${ligne.marque} - ${ligne.typemateriel}
    ${ligne.numero_serie !== 'N/A' ? '(SN: ' + ligne.numero_serie + ')' : ''}
</label>

`;
                });
            }

            bonSelect.addEventListener('change', renderMateriels);
        });
    </script>
@endsection
