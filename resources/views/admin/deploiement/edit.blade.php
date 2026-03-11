@extends('partials.admin.master')

@section('content')
<div class="container mt-4">
    <h3 class="page-title mb-4"><strong>Modifier le déploiement</strong></h3>

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('deploiement.update', $deploiement->id) }}" method="POST">
        @csrf
        @method('POST')


        {{-- Informations principales --}}
        <h5 class="mb-3"><strong>Informations principales</strong></h5>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label"><strong>Bon de livraison</strong></label>
                <select name="bondelivraison_id" id="bondelivraison_id" class="form-control" required>
                    <option value="">-- Choisir un bon --</option>
                    @foreach ($bondelivraisons as $bon)
                        <option value="{{ $bon->id }}"
                            @selected($bon->id == $deploiement->bondelivraison_id)>
                            {{ $bon->bondelivraison }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label"><strong>Date création</strong></label>
                <input type="date" name="Datecreation" class="form-control"
                       value="{{ $deploiement->Datecreation }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label"><strong>État</strong></label>
                <select name="etat" class="form-control" required>
                    <option value="deployee" @selected($deploiement->etat === 'deployee')>
                        Déployé
                    </option>
                </select>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <label class="form-label"><strong>Direction</strong></label>
                <input type="text" name="Direction" class="form-control"
                       value="{{ $deploiement->Direction }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label"><strong>Poste</strong></label>
                <input type="text" name="Poste" class="form-control"
                       value="{{ $deploiement->Poste }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label"><strong>Utilisateur</strong></label>
                <input type="text" name="Utilisateur" class="form-control"
                       value="{{ $deploiement->Utilisateur }}" required>
            </div>
        </div>

        {{-- Caractéristiques machine (FACULTATIF) --}}
        <hr>
        <h5 class="mb-3"><strong>Caractéristiques machine</strong></h5>

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label"><strong>Nom ordinateur</strong></label>
                <input type="text" name="Nomordinateur" class="form-control"
                       value="{{ $deploiement->Nomordinateur }}">
            </div>

            <div class="col-md-3">
                <label class="form-label"><strong>Système</strong></label>
                <input type="text" name="Systeme" class="form-control"
                       value="{{ $deploiement->Systeme }}">
            </div>

            <div class="col-md-3">
                <label class="form-label"><strong>RAM</strong></label>
                <input type="text" name="Ram" class="form-control"
                       value="{{ $deploiement->Ram }}">
            </div>

            <div class="col-md-3">
                <label class="form-label"><strong>Disque</strong></label>
                <input type="text" name="Disque" class="form-control"
                       value="{{ $deploiement->Disque }}">
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
            <button type="submit" class="btn btn-success">Mettre à jour</button>
            <a href="{{ route('deploiement.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

{{-- JS dynamique --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bonSelect = document.getElementById('bondelivraison_id');
    const cell = document.getElementById('materielsCell');

    const lignesByBL = @json($materielsByBL);
    const selectedLigne = @json($selectedLigne);

    function renderMateriels() {
        const blId = bonSelect.value;

        if (!blId || !lignesByBL[blId] || lignesByBL[blId].length === 0) {
            cell.innerHTML = '<em class="text-danger">Aucun matériel disponible</em>';
            return;
        }

        cell.innerHTML = '';

        lignesByBL[blId].forEach(ligne => {
            const checked = ligne.ligne_bon_livraison_id == selectedLigne ? 'checked' : '';

            cell.innerHTML += `
                <label class="d-block">
                    <input type="radio"
                           name="lignebondelivraison_id"
                           value="${ligne.ligne_bon_livraison_id}"
                           ${checked}
                           required>
                    ${ligne.designation} - ${ligne.marque} - ${ligne.typemateriel}
                    ${ligne.numero_serie !== 'N/A' ? ' (SN: ' + ligne.numero_serie + ')' : ''}
                </label>
            `;
        });
    }

    bonSelect.addEventListener('change', renderMateriels);
    renderMateriels();
});
</script>
@endsection
