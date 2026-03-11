@extends('partials.user.master')

@section('content')
<div class="container mt-4">
    <h3 class="page-title"><strong>Créer une répartition des matériels informatiques</strong></h3>

    @if (session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    <form action="{{ route('repartition.store') }}" method="POST">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Informations principales --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label><strong>Bon de livraison</strong> <span class="text-danger">*</span></label>
                        <select name="bondelivraison_id" id="bondelivraison_id" class="form-control" required>
                            <option value="">-- Choisir un bon --</option>
                            @foreach ($bondelivraisons as $bon)
                                <option value="{{ $bon->id }}">{{ $bon->bondelivraison }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">La sélection du BL affichera les matériels disponibles.</small>
                    </div>

                    <div class="col-md-4">
                        <label><strong>Date de répartition</strong> <span class="text-danger">*</span></label>
                        <input type="date" name="date_repartition" class="form-control" required>
                    </div>
                </div>

                {{-- Quantité globale --}}
                <h5 class="mb-2"><strong>Quantité globale par matériel</strong></h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Ordinateur complet</label>
                        <input type="number" name="ordinateur_complets" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-3">
                        <label>Ordinateur portable</label>
                        <input type="number" name="ordinateur_portables" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-3">
                        <label>Imprimantes</label>
                        <input type="number" name="imprimantes" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-3">
                        <label>Scanners</label>
                        <input type="number" name="scanners" class="form-control" value="0" min="0">
                    </div>
                </div>

                {{-- Lignes de répartition --}}
                <h5 class="mb-3"><strong>Liste des matériels à répartir (par BL)</strong></h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableRepartition">
                        <thead class="table-light">
                            <tr>
                                <th>Numérotation</th>
                                <th>Service</th>
                                <th>Destinataire</th>
                                <th>Matériels (BL)</th>
                                <th>Quantité</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <button type="button" id="btnAddRow" class="btn btn-primary btn-sm mt-2">
                    <i class="fa fa-plus"></i> Ajouter une ligne
                </button>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Créer la répartition</button>
                    <a href="{{ route('repartition.index') }}" class="btn btn-secondary">Annuler</a>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bonSelect = document.getElementById('bondelivraison_id');
    const tableBody = document.querySelector('#tableRepartition tbody');
    const materielsByBL = @json($materielsByBL);
    let selectedMateriels = {};
    let lineCounter = 1;

    function createRow() {
        const uid = 'row_' + Math.random().toString(36).substring(2, 9);

        const row = document.createElement('tr');
        row.dataset.uuid = uid;
        row.innerHTML = `
<td>
    <input type="text" name="lignes[${uid}][num_ligne]" value="${lineCounter}" class="form-control" readonly>
</td>
<td>
    <select name="lignes[${uid}][service_id]" class="form-control" required>
        <option value="">-- Choisir un service --</option>
        @foreach ($services as $s)
            <option value="{{ $s->id }}">{{ $s->Designation }}</option>
        @endforeach
    </select>
</td>
<td>
    <input type="text" name="lignes[${uid}][destinataire]" class="form-control" required placeholder="Nom du destinataire">
</td>
<td id="materiels_${uid}">
    <em class="text-muted">Sélectionnez un bon de livraison</em>
</td>
<td>
    <input type="number" name="lignes[${uid}][quantite]" value="0" class="form-control" readonly>
</td>
<td class="text-center">
    <button type="button" class="btn btn-danger btn-sm remove-row" data-target="${uid}">X</button>
</td>`;
        tableBody.appendChild(row);
        lineCounter++;
        updateMateriels();
    }

    function updateMateriels() {
        const bonId = bonSelect.value;
        document.querySelectorAll('tr[data-uuid]').forEach(tr => {
            const uuid = tr.dataset.uuid;
            const container = document.getElementById(`materiels_${uuid}`);
            container.innerHTML = '';

            if (!bonId || !materielsByBL[bonId]) {
                container.innerHTML = '<em class="text-muted">Aucun matériel disponible</em>';
                return;
            }

            materielsByBL[bonId].forEach(mat => {
                if (selectedMateriels[mat.id] && selectedMateriels[mat.id] !== uuid) return;
                const isChecked = selectedMateriels[mat.id] === uuid ? 'checked' : '';

                container.innerHTML += `
<div>
    <label>
        <input type="checkbox" class="materiel-checkbox" data-uuid="${uuid}"
               name="lignes[${uuid}][materiel][]" value="${mat.id}" ${isChecked}>
        ${mat.designation ?? 'Non défini'} - ${mat.marque ?? ''} - ${mat.typemateriel ?? ''}
    </label>
</div>`;
            });

            tr.querySelector(`input[name="lignes[${uuid}][quantite]"]`).value =
                tr.querySelectorAll('.materiel-checkbox:checked').length;
        });
    }

    tableBody.addEventListener('change', function(e) {
        if (!e.target.classList.contains('materiel-checkbox')) return;
        const uuid = e.target.dataset.uuid;
        const matId = e.target.value;
        if (e.target.checked) selectedMateriels[matId] = uuid;
        else delete selectedMateriels[matId];
        updateMateriels();
    });

    document.getElementById('btnAddRow').addEventListener('click', createRow);

    tableBody.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-row')) return;
        const uuid = e.target.dataset.target;
        const row = document.querySelector(`tr[data-uuid="${uuid}"]`);
        row.querySelectorAll('.materiel-checkbox:checked').forEach(cb => delete selectedMateriels[cb.value]);
        row.remove();
        updateMateriels();
    });

    bonSelect.addEventListener('change', function() {
        selectedMateriels = {};
        updateMateriels();
    });

    createRow(); // ligne initiale
});
</script>
@endsection
