@extends('partials.user.master')

@section('content')
<div class="container mt-4">
    <h3 class="page-title"><strong>Ajouter un Déploiement</strong></h3>

    <form action="{{ route('user.deploiement.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm">
            {{-- Informations principales --}}
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Date de création</strong></label>
                        <input type="date" name="Datecreation" class="form-control" value="{{ old('Datecreation') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Direction</strong></label>
                        <input type="text" name="Direction" class="form-control" value="{{ old('Direction') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Utilisateur</strong></label>
                        <input type="text" name="Utilisateur" class="form-control" value="{{ old('Utilisateur') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Poste</strong></label>
                        <input type="text" name="Poste" class="form-control" value="{{ old('Poste') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Système</strong></label>
                        <input type="text" name="Systeme" class="form-control" value="{{ old('Systeme') }}">
                    </div>
                    <div class="col-md-4">
                        <label><strong>RAM</strong></label>
                        <input type="text" name="Ram" class="form-control" value="{{ old('Ram') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Disque</strong></label>
                        <input type="text" name="Disque" class="form-control" value="{{ old('Disque') }}">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Nom ordinateur</strong></label>
                        <input type="text" name="Nomordinateur" class="form-control" value="{{ old('Nomordinateur') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Bon de livraison</strong></label>
                        <select name="bondelivraison_id" id="bondelivraison_id" class="form-control" required>
                            <option value="">-- Choisir un bon --</option>
                            @foreach ($bondelivraisons as $bon)
                                <option value="{{ $bon->id }}" {{ old('bondelivraison_id') == $bon->id ? 'selected' : '' }}>
                                    {{ $bon->bondelivraison }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>État du déploiement</strong></label>
                        <select name="etat" class="form-control" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="non_deploye" {{ old('etat') == 'non_deploye' ? 'selected' : '' }}>Non déployé</option>
                            <option value="en_cours" {{ old('etat') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="deployee" {{ old('etat') == 'deployee' ? 'selected' : '' }}>Déployé</option>
                            <option value="hors_service" {{ old('etat') == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                        </select>
                    </div>
                </div>

            </div>

            {{-- Matériels --}}
            <h5 class="mb-3 px-3"><strong>Liste des matériels à affecter</strong></h5>

            <div class="table-responsive px-3">
                <table class="table table-bordered" id="tableRepartition">
                    <thead class="table-light">
                        <tr>
                            <th>Matériel (Bon de livraison)</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="px-3 mb-3">
                <button type="button" id="btnAddRow" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Ajouter une ligne
                </button>
            </div>

            <div class="px-3 pb-3">
                <button type="submit" class="btn btn-success">Créer le déploiement</button>
                <a href="{{ route('user.deploiement.index') }}" class="btn btn-secondary">Annuler</a>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const bonSelect  = document.getElementById('bondelivraison_id');
        const tableBody  = document.querySelector('#tableRepartition tbody');
        const lignesByBL = @json($ligneBondelivraisonsByBL);

        // Contient : ligneBonLivraison_id → row_uid
        let selected = {};

        function addRow() {
            const uid = 'row_' + Math.random().toString(36).substr(2, 9);

            const tr = document.createElement('tr');
            tr.dataset.uuid = uid;

            tr.innerHTML = `
                <td id="cell_${uid}">
                    <em class="text-muted">Sélectionnez un bon de livraison</em>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row" data-id="${uid}">
                        X
                    </button>
                </td>
            `;

            tableBody.appendChild(tr);

            render();
        }

        function render() {
            const blId = bonSelect.value;

            document.querySelectorAll('#tableRepartition tbody tr').forEach(tr => {

                const uid  = tr.dataset.uuid;
                const cell = document.getElementById(`cell_${uid}`);

                cell.innerHTML = '';

                if (!blId || !lignesByBL[blId]) {
                    cell.innerHTML = '<em class="text-muted">Aucun matériel disponible</em>';
                    return;
                }

                lignesByBL[blId].forEach(ligne => {

                    // On ne réaffiche pas le matériel déjà pris ailleurs
                    if (selected[ligne.id] && selected[ligne.id] !== uid) return;

                    const checked = selected[ligne.id] === uid ? 'checked' : '';

                    const div = document.createElement('div');

                    div.innerHTML = `
                        <label class="d-block">
                            <input type="checkbox"
                                   name="lignes[${uid}][lignebondelivraison_id][]"
                                   value="${ligne.id}"
                                   data-uuid="${uid}"
                                   class="materiel-checkbox"
                                   ${checked} >
                            ${ligne.designation} - ${ligne.marque} - ${ligne.typemateriel}
                            ${ligne.numero_serie ? " - NºS: " + ligne.numero_serie : ""}
                        </label>
                    `;

                    cell.appendChild(div);
                });
            });
        }

        tableBody.addEventListener('change', e => {

            if (!e.target.matches('.materiel-checkbox')) return;

            const uid = e.target.dataset.uuid;
            const id  = e.target.value;

            // 👉 un seul matériel par ligne : décocher le reste
            document.querySelectorAll(`tr[data-uuid="${uid}"] .materiel-checkbox`)
                .forEach(box => {
                    if (box !== e.target) box.checked = false;
                });

            // 👉 nettoyer les anciennes valeurs
            Object.keys(selected).forEach(key => {
                if (selected[key] === uid) delete selected[key];
            });

            // 👉 si coché → enregistrer, sinon enlever
            if (e.target.checked) selected[id] = uid;
            else delete selected[id];

            render();
        });

        tableBody.addEventListener('click', e => {

            if (!e.target.classList.contains('remove-row')) return;

            const uid = e.target.dataset.id;

            // nettoyer selected
            Object.keys(selected).forEach(key => {
                if (selected[key] === uid) delete selected[key];
            });

            document.querySelector(`tr[data-uuid="${uid}"]`).remove();

            render();
        });

        bonSelect.addEventListener('change', () => {
            selected = {};
            render();
        });

        document.getElementById('btnAddRow').addEventListener('click', () => {
            if (!bonSelect.value) {
                alert("Veuillez d'abord sélectionner un bon de livraison.");
                return;
            }
            addRow();
        });

        addRow();
    });
    </script>

@endsection
