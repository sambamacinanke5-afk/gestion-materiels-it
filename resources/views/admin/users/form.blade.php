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
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-group-full {
        grid-column: 1 / -1;
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
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
    }

    .section-title {
        margin: 24px 0 12px;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
    }

    .roles-wrapper {
        max-height: 40vh;
        overflow-y: auto;
        padding-right: 6px;
        border-radius: 14px;
    }

    .roles-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px 16px;
    }

    .role-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 12px;
    }

    .role-item label {
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

    @media (max-width: 900px) {
        .form-grid,
        .roles-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .form-grid,
        .roles-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card-clean">
    <div class="card-clean-body">
        <div class="form-grid">
            <div>
                <label class="form-label-clean">Nom</label>
                <input
                    type="text"
                    name="name"
                    class="form-control-clean"
                    value="{{ old('name', $user->name ?? '') }}"
                    required
                >
            </div>

            <div>
                <label class="form-label-clean">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control-clean"
                    value="{{ old('email', $user->email ?? '') }}"
                    required
                >
            </div>

            <div>
                <label class="form-label-clean">Mot de passe</label>
                <input
                    type="password"
                    name="password"
                    class="form-control-clean"
                    {{ isset($user) ? '' : 'required' }}
                >
                <div class="form-help">
                    {{ isset($user) ? 'Laisse vide pour ne pas modifier le mot de passe.' : 'Minimum 8 caractères.' }}
                </div>
            </div>

            <div>
                <label class="form-label-clean">Confirmation mot de passe</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control-clean"
                    {{ isset($user) ? '' : 'required' }}
                >
            </div>
        </div>

        <div class="section-title">Rôles</div>

        @php
            $selectedRoles = old('roles', $userRoles ?? []);
        @endphp

        <div class="roles-wrapper">
            <div class="roles-grid">
                @foreach($roles as $role)
                    <div class="role-item">
                        <label for="role_{{ $role->id }}">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                id="role_{{ $role->id }}"
                                {{ in_array($role->name, $selectedRoles) ? 'checked' : '' }}
                            >
                            <span>{{ $role->name }}</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="actions-bar">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary-clean">
                <i class="fa-solid fa-arrow-left"></i>
                Retour
            </a>

            <button type="submit" class="btn-primary-clean">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ isset($user) ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </div>
</div>