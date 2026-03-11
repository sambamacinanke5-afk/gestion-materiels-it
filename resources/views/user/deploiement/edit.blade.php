@extends('partials.user.master')
@section('content')
<div class="container mt-4">
    <h3 class="page-title"><strong>Modifier le Déploiement</strong></h3>

    <form action="" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- 🔹 Informations principales --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Date de création</strong></label>
                        <input type="date" name="Datecreation" class="form-control"
                               value="{{ $deploiement->Datecreation }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Direction</strong></label>
                        <input type="text" name="Direction" class="form-control"
                               value="{{ $deploiement->Direction }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Utilisateur</strong></label>
                        <input type="text" name="Utilisateur" class="form-control"
                               value="{{ $deploiement->Utilisateur }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Poste</strong></label>
                        <input type="text" name="Poste" class="form-control"
                               value="{{ $deploiement->Poste }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Système</strong></label>
                        <input type="text" name="Systeme" class="form-control"
                               value="{{ $deploiement->Systeme }}">
                    </div>
                    <div class="col-md-4">
                        <label><strong>RAM</strong></label>
                        <input type="text" name="Ram" class="form-control"
                               value="{{ $deploiement->Ram }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Disque</strong></label>
                        <input type="text" name="Disque" class="form-control"
                               value="{{ $deploiement->Disque }}">
                    </div>
                    <div class="col-md-4">
                        <label><strong>Nom ordinateur</strong></label>
                        <input type="text" name="Nomordinateur" class="form-control"
                               value="{{ $deploiement->Nomordinateur }}" required>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Bon de livraison</strong></label>
                        <select name="bondelivraison_id" id="bondelivraison_id"
                                class="form-control" required>
                            <option value="">-- Choisir un bon --</option>
                            @foreach ($bondelivraisons as $bon)
                                <option value="{{ $bon->id }}"
                                    @selected($bon->id == $deploiement->bondelivraison_id)>
                                    {{ $bon->bondelivraison }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- État --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>État du déploiement</strong></label>
                        <select name="etat" class="form-control" required>
                            @foreach ([
                                'non_deploye' => 'Non déployé',
                                'en_cours' => 'En cours',
                                'deployee' => 'Déployé',
                                'hors_service' => 'Hors service'
                            ] as $key => $label)
                                <option value="{{ $key }}"
                                    @selected($key == $deploiement->etat)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            {{-- 📦 Matériels --}}
            <h5 class="mb-3 px-3"><strong>Liste des matériels affectés</strong></h5>

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

            <div class="px-3">
                <button type="button" id="btnAddRow" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Ajouter une ligne
                </button>
            </div>

            <div class="mt-4 px-3 pb-3">
                <button type="submit" class="btn btn-success">Mettre à jour</button>
                <a href="{{ route('user.deploiement.index') }}" class="btn btn-secondary">Annuler</a>
            </div>

        </div>
    </form>
</div>

{{-- 🔹 JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const bonSelect = document.getElementById('bondelivraison_id');
    const tableBody = document.querySelector('#tableRepartition tbody');
    const lignesByBL = @json($ligneBondelivraisonsByBL);
    const preselected = @json($selectedLignes);

    let selected = {};
    preselected.forEach(id => selected[id] = 'row_1');

    function addRow(uid = null) {
        uid = uid ?? 'row_' + Math.random().toString(36).substr(2, 9);
        const tr = document.createElement('tr');
        tr.dataset.uuid = uid;

        tr.innerHTML = `
            <td id="cell_${uid}"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row"
                        data-id="${uid}">X</button>
            </td>
        `;

        tableBody.appendChild(tr);
        render();
    }

    function render() {
        const blId = bonSelect.value;

        document.querySelectorAll('#tableRepartition tbody tr').forEach(tr => {
            const uid = tr.dataset.uuid;
            const cell = document.getElementById(`cell_${uid}`);
            cell.innerHTML = '';

            if (!blId || !lignesByBL[blId]) {
                cell.innerHTML = '<em class="text-muted">Aucun matériel</em>';
                return;
            }

            lignesByBL[blId].forEach(ligne => {
                if (selected[ligne.id] && selected[ligne.id] !== uid) return;

                const checked = selected[ligne.id] === uid ? 'checked' : '';

                const div = document.createElement('div');
                div.innerHTML = `
                    <label>
                        <input type="checkbox"
                               name="lignes[${uid}][lignebondelivraison_id][]"
                               value="${ligne.id}"
                               data-uuid="${uid}"
                               ${checked}>
                        ${ligne.designation} - ${ligne.marque} - ${ligne.typemateriel}
                        ${ligne.numero_serie ? ' - N°S: ' + ligne.numero_serie : ''}
                    </label>
                `;
                cell.appendChild(div);
            });
        });
    }

    tableBody.addEventListener('change', e => {
        if (!e.target.matches('input[type=checkbox]')) return;
        const uid = e.target.dataset.uuid;
        const id = e.target.value;
        e.target.checked ? selected[id] = uid : delete selected[id];
        render();
    });

    tableBody.addEventListener('click', e => {
        if (!e.target.classList.contains('remove-row')) return;
        const uid = e.target.dataset.id;
        const tr = document.querySelector(`tr[data-uuid="${uid}"]`);
        tr.querySelectorAll('input:checked').forEach(i => delete selected[i.value]);
        tr.remove();
        render();
    });

    document.getElementById('btnAddRow').addEventListener('click', () => addRow());
    bonSelect.addEventListener('change', () => { selected = {}; render(); });

    addRow('row_1');
});
</script>
@endsection
