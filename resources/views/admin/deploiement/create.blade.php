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
        max-width: 1100px;
    }

    .card-clean-body { padding: 24px; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

    .actions-bar {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-primary-clean {
        background: #16a34a;
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

    .alert-clean {
        background: #fee2e2;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
</style>

<div class="admin-page">

    <h1 class="page-title">Créer un déploiement</h1>

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

            <form action="{{ route('deploiement.store') }}" method="POST">
                @csrf

                <!-- INFOS PRINCIPALES -->
                <h4>Informations principales</h4>
                <div class="form-grid">

                    <div>
                        <label class="form-label-clean">Bon de livraison *</label>
                        <select name="bondelivraison_id" id="bondelivraison_id" class="form-control-clean" required>
                            <option value="">-- Choisir --</option>
                            @foreach ($bondelivraisons as $bon)
                                <option value="{{ $bon->id }}">{{ $bon->numero_bl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label-clean">Date *</label>
                        <input type="date" name="Datecreation" class="form-control-clean" required>
                    </div>

                    <div>
                        <label class="form-label-clean">État *</label>
                        <select name="etat" class="form-control-clean" required>
                            <option value="">-- Choisir --</option>
                            <option value="deployee">Déployé</option>
                        </select>
                    </div>

                </div>

                <div class="form-grid">

                    <div>
                        <label class="form-label-clean">Direction</label>
                        <input type="text" name="Direction" class="form-control-clean" required>
                    </div>

                    <div>
                        <label class="form-label-clean">Poste</label>
                        <input type="text" name="Poste" class="form-control-clean" required>
                    </div>

                    <div>
                        <label class="form-label-clean">Utilisateur</label>
                        <input type="text" name="Utilisateur" class="form-control-clean" required>
                    </div>

                </div>

                <!-- MACHINE -->
                <h4>Caractéristiques machine</h4>
                <div class="form-grid">

                    <div>
                        <label class="form-label-clean">Nom ordinateur</label>
                        <input type="text" name="Nomordinateur" class="form-control-clean">
                    </div>

                    <div>
                        <label class="form-label-clean">Système</label>
                        <input type="text" name="Systeme" class="form-control-clean">
                    </div>

                    <div>
                        <label class="form-label-clean">RAM</label>
                        <input type="text" name="Ram" class="form-control-clean">
                    </div>

                    <div>
                        <label class="form-label-clean">Disque</label>
                        <input type="text" name="Disque" class="form-control-clean">
                    </div>

                </div>

                <!-- MATERIEL -->
                <h4>Matériel à affecter</h4>

                <table class="table-clean">
                    <tbody>
                        <tr>
                            <td id="materielsCell">
                                <em style="color:#6b7280;">Sélectionnez un bon de livraison</em>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- ACTIONS -->
                <div class="actions-bar">
                    <a href="{{ route('deploiement.index') }}" class="btn-secondary-clean">
                        Annuler
                    </a>

                    <button type="submit" class="btn-primary-clean">
                        Créer
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- JS dynamique -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const bonSelect = document.getElementById('bondelivraison_id');
    const cell = document.getElementById('materielsCell');
    const lignesByBL = @json($materielsByBL);

    function renderMateriels() {
        const blId = bonSelect.value;

        if (!blId || !lignesByBL[blId] || lignesByBL[blId].length === 0) {
            cell.innerHTML = '<em style="color:red;">Aucun matériel disponible</em>';
            return;
        }

        cell.innerHTML = '';

        lignesByBL[blId].forEach(ligne => {
            cell.innerHTML += `
                <label style="display:block;margin-bottom:5px;">
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
