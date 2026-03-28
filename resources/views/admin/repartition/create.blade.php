@extends('layouts.app')

@section('content')
<style>
    .admin-page { padding: 24px; background: #f8fafc; min-height: 100vh; }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .card-clean {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        max-width: 1200px;
    }

    .card-clean-body { padding: 24px; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
        margin-bottom: 20px;
    }

    .form-label-clean {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    .form-control-clean {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .form-control-clean:focus {
        border-color: #2563eb;
        box-shadow: 0 0 5px rgba(37,99,235,0.3);
    }

    .table-clean {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .table-clean th,
    .table-clean td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .btn-primary-clean {
        background: #2563eb;
        color: #fff;
        padding: 10px 14px;
        border-radius: 10px;
        border: none;
    }

    .btn-success-clean {
        background: #16a34a;
        color: #fff;
        padding: 10px 14px;
        border-radius: 10px;
        border: none;
    }

    .btn-danger-clean {
        background: #ef4444;
        color: #fff;
        padding: 6px 10px;
        border-radius: 8px;
        border: none;
    }

    .btn-secondary-clean {
        background: #e5e7eb;
        padding: 10px 14px;
        border-radius: 10px;
    }

    .badge-clean {
        display: inline-block;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 8px;
        margin: 2px 0;
        font-size: 12px;
    }

    .actions-bar {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .alert-clean {
        background: #fee2e2;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
</style>

<div class="admin-page">

    <h1 class="page-title">Créer une répartition</h1>

    @if(session('error'))
        <div class="alert-clean">{{ session('error') }}</div>
    @endif

    <div class="card-clean">
        <div class="card-clean-body">

            <form action="{{ route('repartition.store') }}" method="POST">
                @csrf

                <!-- Infos principales -->
                <div class="form-grid">
                    <div>
                        <label class="form-label-clean">Bon de livraison *</label>
                        <select name="bondelivraison_id" id="bondelivraison_id" class="form-control-clean" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($bondelivraisons as $bon)
                                <option value="{{ $bon->id }}">{{ $bon->numero_bl }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Sélection du BL affichera les matériels disponibles</small>
                    </div>

                    <div>
                        <label class="form-label-clean">Date de répartition *</label>
                        <input type="date" name="date_repartition" class="form-control-clean" required>
                    </div>
                </div>

                <!-- Quantité globale -->
                <h4>Quantité globale</h4>
                <div class="form-grid">
                    <input type="number" name="ordinateur_complets" class="form-control-clean" placeholder="Ordinateur complet" value="0" min="0">
                    <input type="number" name="ordinateur_portables" class="form-control-clean" placeholder="Ordinateur portable" value="0" min="0">
                    <input type="number" name="imprimantes" class="form-control-clean" placeholder="Imprimantes" value="0" min="0">
                    <input type="number" name="scanners" class="form-control-clean" placeholder="Scanners" value="0" min="0">
                </div>

                <!-- Lignes dynamiques -->
                <h4>Répartition par service</h4>
                <table class="table-clean" id="tableRepartition">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service</th>
                            <th>Destinataire</th>
                            <th>Matériels</th>
                            <th>Qté</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <button type="button" id="btnAddRow" class="btn-primary-clean">
                    + Ajouter une ligne
                </button>

                <!-- Actions -->
                <div class="actions-bar">
                    <a href="{{ route('repartition.index') }}" class="btn-secondary-clean">
                        Annuler
                    </a>

                    <button type="submit" class="btn-success-clean">
                        Enregistrer
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

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
<td>${lineCounter}</td>

<td>
<select name="lignes[${uid}][service_id]" class="form-control-clean" required>
<option value="">-- Service --</option>
@foreach ($services as $s)
<option value="{{ $s->id }}">{{ $s->Designation }}</option>
@endforeach
</select>
</td>

<td>
<input type="text" name="lignes[${uid}][destinataire]" class="form-control-clean" required>
</td>

<td id="materiels_${uid}">
<em class="text-muted">Sélectionnez un BL</em>
</td>

<td>
<input type="number" name="lignes[${uid}][quantite]" class="form-control-clean" readonly value="0">
</td>

<td>
<button type="button" class="btn-danger-clean remove-row" data-target="${uid}">X</button>
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
                container.innerHTML = '<em>Aucun matériel disponible</em>';
                return;
            }

            materielsByBL[bonId].forEach(mat => {
                if (selectedMateriels[mat.id] && selectedMateriels[mat.id] !== uuid) return;
                const checked = selectedMateriels[mat.id] === uuid ? 'checked' : '';

                container.innerHTML += `
<div class="badge-clean">
<label>
<input type="checkbox" class="materiel-checkbox" data-uuid="${uuid}" value="${mat.id}" ${checked}>
${mat.designation} - ${mat.marque} - ${mat.typemateriel}
</label>
</div>`;
            });

            tr.querySelector(`[name="lignes[${uuid}][quantite]"]`).value =
                tr.querySelectorAll('.materiel-checkbox:checked').length;
        });
    }

    tableBody.addEventListener('change', function(e) {
        if (!e.target.classList.contains('materiel-checkbox')) return;

        const uuid = e.target.dataset.uuid;
        const id = e.target.value;

        if (e.target.checked) selectedMateriels[id] = uuid;
        else delete selectedMateriels[id];

        updateMateriels();
    });

    document.getElementById('btnAddRow').addEventListener('click', createRow);

    tableBody.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-row')) return;

        const uuid = e.target.dataset.target;
        const row = document.querySelector(`tr[data-uuid="${uuid}"]`);

        row.querySelectorAll('.materiel-checkbox:checked')
            .forEach(cb => delete selectedMateriels[cb.value]);

        row.remove();
        updateMateriels();
    });

    bonSelect.addEventListener('change', () => {
        selectedMateriels = {};
        updateMateriels();
    });

    createRow(); // ligne initiale
});
</script>

@endsection
