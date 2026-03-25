<style>
    .card-clean {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        max-width: 700px;
    }

    .card-clean-body {
        padding: 24px;
    }

    .form-label-clean {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .form-control-clean {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: 14px;
        color: #111827;
        background: #fff;
        outline: none;
        transition: 0.2s ease;
    }

    .form-control-clean:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .form-help {
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }

    .actions-bar {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn-primary-clean,
    .btn-secondary-clean {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
        cursor: pointer;
    }

    .btn-primary-clean {
        background: #2563eb;
        color: #fff;
    }

    .btn-primary-clean:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .btn-secondary-clean {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary-clean:hover {
        background: #d1d5db;
        color: #111827;
    }
</style>

<div class="card-clean">
    <div class="card-clean-body">
        <div class="mb-3">
            <label class="form-label-clean">Nom de la permission</label>
            <input
                type="text"
                name="name"
                class="form-control-clean"
                value="{{ old('name', $permission->name ?? '') }}"
                placeholder="Ex: fournisseurs.view"
                required
            >
            <div class="form-help">
                Utilise un format clair, par exemple : <strong>module.action</strong>
            </div>
        </div>

        <div class="actions-bar">
            <a href="{{ route('admin.permissions.index') }}" class="btn-secondary-clean">
                <i class="fa-solid fa-arrow-left"></i>
                Retour
            </a>

            <button type="submit" class="btn-primary-clean">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ isset($permission) ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </div>
</div>