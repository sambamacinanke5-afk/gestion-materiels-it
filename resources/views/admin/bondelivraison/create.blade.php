@extends('layouts.app')

@section('content')
<style>
    .admin-page { padding: 24px; background: #f8fafc; min-height: 100vh; }

    .page-title { font-size: 28px; font-weight: 700; color: #1f2937; }

    .card-clean {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        max-width: 1100px;
    }

    .card-clean-body { padding: 24px; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 20px;
    }

    .form-group-full { grid-column: 1 / -1; }

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
    }

    .table-clean th,
    .table-clean td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .actions-bar {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-primary-clean {
        background: #2563eb;
        color: #fff;
        padding: 10px 16px;
        border-radius: 10px;
        border: none;
    }

    .btn-secondary-clean {
        background: #e5e7eb;
        padding: 10px 16px;
        border-radius: 10px;
    }

    .btn-success-sm {
        background: #22c55e;
        color: #fff;
        border: none;
        padding: 5px 8px;
        border-radius: 6px;
    }

    .btn-danger-sm {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 5px 8px;
        border-radius: 6px;
    }

    .alert-clean {
        background: #fee2e2;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
</style>

<div class="admin-page">

    <h1 class="page-title mb-3">Ajouter un Bon de Livraison</h1>

    {{-- ERREURS --}}
    @if ($errors->any())
        <div class="alert-clean">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-clean">
        <div class="card-clean-body">

            <form action="{{ route('bondelivraison.store') }}" method="POST" id="bonForm">
                @csrf

                <!-- Infos principales -->
                <div class="form-grid">

                    <div>
                        <label class="form-label-clean">Fournisseur *</label>
                        <select name="fournisseur_id" class="form-control-clean" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($fournisseurs as $f)
                                <option value="{{ $f->id }}">{{ $f->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label-clean">Référence BL *</label>
                        <input type="text" name="numero_bl" class="form-control-clean" required>
                    </div>

                    <div>
                        <label class="form-label-clean">Date *</label>
                        <input type="date" name="date_livraison" class="form-control-clean" required>
                    </div>

                </div>

                <!-- Table lignes -->
                <h4 class="mb-2">Matériels livrés</h4>

                <table class="table-clean" id="ligneTable">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th>Type</th>
                            <th>Marque</th>
                            <th>Numéro de série</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="lignes[0][designation]" class="form-control-clean" required></td>

                            <td>
                                <select name="lignes[0][typemateriel_id]" class="form-control-clean" required>
                                    <option value="">-- Type --</option>
                                    @foreach($typemateriels as $t)
                                        <option value="{{ $t->id }}">{{ $t->Designation }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <select name="lignes[0][marque_id]" class="form-control-clean" required>
                                    <option value="">-- Marque --</option>
                                    @foreach($marques as $m)
                                        <option value="{{ $m->id }}">{{ $m->Designation }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" name="lignes[0][numero_serie]" class="form-control-clean numero-serie" minlength="10" required oninput="checkSerials()">
                                <small class="text-danger error-serial"></small>
                            </td>

                            <td>
                                <button type="button" class="btn-success-sm" onclick="addRow()">+</button>
                                <button type="button" class="btn-danger-sm" onclick="removeRow(this)">-</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Actions -->
                <div class="actions-bar">
                    <a href="{{ route('bondelivraison.index') }}" class="btn-secondary-clean">
                        Annuler
                    </a>

                    <button type="submit" class="btn-primary-clean" id="submitBtn">
                        Enregistrer
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
