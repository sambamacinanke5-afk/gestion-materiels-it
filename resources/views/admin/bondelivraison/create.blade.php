@extends('partials.admin.master')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><strong>Ajouter un Bon de Livraison</strong></h3>
        </div>
    </div>

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

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('bondelivraison.store') }}" method="POST" id="bonForm">
                @csrf
                <div class="card">
                    <div class="card-body">

                        <h3 class="mb-4">Informations du Bon de Livraison</h3>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label><strong>Fournisseur</strong> <span class="text-danger">*</span></label>
                                <select name="fournisseur_id" class="form-control" required>
                                    <option value="">-- Choisir un fournisseur --</option>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><strong>Nom du Bon / Référence</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="bondelivraison" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label><strong>Date de Livraison</strong> <span class="text-danger">*</span></label>
                                <input type="date" name="date_livraison" class="form-control" required>
                            </div>
                        </div>

                        <h4 class="mb-3">Matériels livrés</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="ligneTable">
                                <thead>
                                    <tr>
                                        <th>Désignation</th>
                                        <th>Type de matériel</th>
                                        <th>Marque</th>
                                        <th>Numéro de série</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="lignes[0][designation]" class="form-control" required></td>
                                        <td>
                                            <select name="lignes[0][typemateriel_id]" class="form-control" required>
                                                <option value="">-- Choisir un type --</option>
                                                @foreach($typemateriels as $type)
                                                    <option value="{{ $type->id }}">{{ $type->Designation }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="lignes[0][marque_id]" class="form-control" required>
                                                <option value="">-- Choisir une marque --</option>
                                                @foreach($marques as $marque)
                                                    <option value="{{ $marque->id }}">{{ $marque->Designation }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="lignes[0][numero_serie]" class="form-control numero-serie" minlength="10" required oninput="checkSerials()">
                                            <small class="text-danger error-serial"></small>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-success" onclick="addRow()"><i class="fa fa-plus"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Enregistrer</button>
                        <a href="{{ route('bondelivraison.index') }}" class="btn btn-danger">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let ligneIndex = 1;
const types = @json($typemateriels);
const marques = @json($marques);

function buildOptions(list) {
    return list.map(item => `<option value="${item.id}">${item.Designation}</option>`).join('');
}

function addRow() {
    const tbody = document.querySelector("#ligneTable tbody");
    const tr = document.createElement("tr");
    tr.innerHTML = `
        <td><input type="text" name="lignes[${ligneIndex}][designation]" class="form-control" required></td>
        <td>
            <select name="lignes[${ligneIndex}][typemateriel_id]" class="form-control" required>
                <option value="">-- Choisir un type --</option>${buildOptions(types)}
            </select>
        </td>
        <td>
            <select name="lignes[${ligneIndex}][marque_id]" class="form-control" required>
                <option value="">-- Choisir une marque --</option>${buildOptions(marques)}
            </select>
        </td>
        <td>
            <input type="text" name="lignes[${ligneIndex}][numero_serie]" class="form-control numero-serie" minlength="10" required oninput="checkSerials()">
            <small class="text-danger error-serial"></small>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-success" onclick="addRow()"><i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
        </td>
    `;
    tbody.appendChild(tr);
    ligneIndex++;
    checkSerials();
}

function removeRow(button) {
    const row = button.closest('tr');
    const tbody = row.closest('tbody');
    if(tbody.rows.length > 1) row.remove();
    checkSerials();
}

function checkSerials() {
    const inputs = document.querySelectorAll('.numero-serie');
    const values = [];
    let hasError = false;

    inputs.forEach(input => {
        const error = input.nextElementSibling;
        const value = input.value.trim();
        error.textContent = '';
        input.classList.remove('is-invalid');

        if(value.length > 0 && value.length < 10) {
            error.textContent = 'Le numéro de série doit contenir au moins 10 caractères.';
            input.classList.add('is-invalid');
            hasError = true;
        }

        if(values.includes(value) && value !== '') {
            error.textContent = 'Ce numéro de série est déjà ajouté dans le formulaire.';
            input.classList.add('is-invalid');
            hasError = true;
        }

        if(value !== '') values.push(value);
    });

    document.getElementById('submitBtn').disabled = hasError;
}
</script>

@endsection
