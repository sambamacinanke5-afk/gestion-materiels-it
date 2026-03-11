@extends('partials.admin.master')
@section('content')

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><strong>Modifier un Bon de Livraison</strong></h3>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <form action="{{ route('bondelivraison.update', $bon->id) }}" method="POST">
                @csrf
                @method('POST') {{-- ou PUT si ta route accepte PUT --}}
                <div class="card">
                    <div class="card-body">

                        <h3 class="mb-4">Informations du Bon de Livraison</h3>

                        {{-- Informations principales --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label"><strong>Fournisseur</strong> <span class="text-danger">*</span></label>
                                <select name="fournisseur_id" class="form-control" required>
                                    <option value="">-- Choisir un fournisseur --</option>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}"
                                            {{ $bon->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
                                            {{ $fournisseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><strong>Nom du Bon / Référence</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="bondelivraison" class="form-control"
                                    value="{{ $bon->bondelivraison }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><strong>Date de Livraison</strong> <span class="text-danger">*</span></label>
                                <input type="date" name="date_livraison" class="form-control"
                                    value="{{ $bon->date_livraison }}" required>
                            </div>
                        </div>

                        {{-- Tableau dynamique des lignes --}}
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
                                    @foreach($bon->lignes as $index => $ligne)
                                        <tr>
                                            <td>
                                                <input type="text" name="lignes[{{ $index }}][designation]"
                                                    class="form-control"
                                                    value="{{ $ligne->materiel->designation ?? '' }}" required>
                                            </td>
                                            <td>
                                                <select name="lignes[{{ $index }}][typemateriel_id]"
                                                    class="form-control" required>
                                                    <option value="">-- Choisir un type --</option>
                                                    @foreach($typemateriels as $type)
                                                        <option value="{{ $type->id }}"
                                                            {{ $ligne->materiel->typemateriel_id == $type->id ? 'selected' : '' }}>
                                                            {{ $type->Designation }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="lignes[{{ $index }}][marque_id]" class="form-control"
                                                    required>
                                                    <option value="">-- Choisir une marque --</option>
                                                    @foreach($marques as $marque)
                                                        <option value="{{ $marque->id }}"
                                                            {{ $ligne->materiel->marque_id == $marque->id ? 'selected' : '' }}>
                                                            {{ $marque->Designation }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="lignes[{{ $index }}][numero_serie]"
                                                    class="form-control"
                                                    value="{{ $ligne->materiel->numero_serie ?? '' }}" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-success"
                                                    onclick="addRow()"><i class="fa fa-plus"></i></button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                    {{-- Boutons --}}
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('bondelivraison.index') }}" class="btn btn-danger">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script pour ajouter / supprimer lignes dynamiques --}}
<script>
    let ligneIndex = {{ count($bon->lignes) }};
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
                    <option value="">-- Choisir un type --</option>
                    ${buildOptions(types)}
                </select>
            </td>
            <td>
                <select name="lignes[${ligneIndex}][marque_id]" class="form-control" required>
                    <option value="">-- Choisir une marque --</option>
                    ${buildOptions(marques)}
                </select>
            </td>
            <td><input type="text" name="lignes[${ligneIndex}][numero_serie]" class="form-control" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-success" onclick="addRow()"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
        ligneIndex++;
    }

    function removeRow(button) {
        const row = button.closest('tr');
        const tbody = row.closest('tbody');
        if (tbody.rows.length > 1) row.remove();
    }
</script>

@endsection
