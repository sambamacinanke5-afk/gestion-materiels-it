<style>
    .card-clean {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        max-width: 1000px;
    }

    .card-clean-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
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

    .section-title {
        margin: 8px 0 0;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
    }

    .permissions-wrapper {
        max-height: 45vh;
        overflow-y: auto;
        padding-right: 6px;
        border-radius: 14px;
    }

    .permissions-wrapper::-webkit-scrollbar {
        width: 8px;
    }

    .permissions-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .permissions-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .permissions-wrapper {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px 16px;
    }

    .permission-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 12px;
    }

    .permission-item label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #374151;
        cursor: pointer;
    }

    .actions-bar {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 8px;
        flex-wrap: wrap;
        position: sticky;
        bottom: 0;
        background: #ffffff;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
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

    @media (max-width: 900px) {
        .permissions-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .permissions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card-clean">
    <div class="card-clean-body">
        <div>
            <label class="form-label-clean">Nom du rôle</label>
            <input
                type="text"
                name="name"
                class="form-control-clean"
                value="{{ old('name', $role->name ?? '') }}"
                placeholder="Ex: magasinier"
                required
            >
        </div>

        <div class="section-title">Permissions</div>

        @php
            $selectedPermissions = old('permissions', $rolePermissions ?? []);
        @endphp

        <div class="permissions-wrapper">
            <div class="permissions-grid">
                @foreach($permissions as $permission)
                    <div class="permission-item">
                        <label for="perm_{{ $permission->id }}">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                id="perm_{{ $permission->id }}"
                                {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}
                            >
                            <span>{{ $permission->name }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="actions-bar">
            <a href="{{ route('admin.roles.index') }}" class="btn-secondary-clean">
                <i class="fa-solid fa-arrow-left"></i>
                Retour
            </a>

            <button type="submit" class="btn-primary-clean">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ isset($role) ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </div>
</div>