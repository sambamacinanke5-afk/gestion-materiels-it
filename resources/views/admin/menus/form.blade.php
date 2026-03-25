<div class="form-grid">

    {{-- TITRE --}}
    <div>
        <label class="form-label-clean">Titre</label>
        <input
            type="text"
            name="title"
            class="form-control-clean"
            value="{{ old('title', $menu->title ?? '') }}"
            placeholder="Ex: Tableau de Bord"
        >
    </div>

    {{-- ROUTE --}}
    <div>
        <label class="form-label-clean">Route</label>
        <input
            type="text"
            name="route"
            class="form-control-clean"
            value="{{ old('route', $menu->route ?? '') }}"
            placeholder="Ex: dashboard"
        >
        <div class="form-help">Nom exact de la route Laravel</div>
    </div>

    {{-- ICONE --}}
    <div>
        <label class="form-label-clean">Icône</label>
        <input
            type="text"
            name="icon"
            class="form-control-clean"
            value="{{ old('icon', $menu->icon ?? '') }}"
            placeholder="fa-solid fa-chart-line"
        >
    </div>

    {{-- PERMISSION --}}
    <div>
        <label class="form-label-clean">Permission</label>
        <select name="permission_name" class="form-select-clean">
            <option value="">-- Choisir --</option>
            @foreach($permissions as $permission)
                <option value="{{ $permission->name }}"
                    {{ old('permission_name', $menu->permission_name ?? '') === $permission->name ? 'selected' : '' }}>
                    {{ $permission->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- PARENT --}}
    <div>
        <label class="form-label-clean">Parent</label>
        <select name="parent_id" class="form-select-clean">
            <option value="">-- Aucun --</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}"
                    {{ (string) old('parent_id', $menu->parent_id ?? '') === (string) $parent->id ? 'selected' : '' }}>
                    {{ $parent->title }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- ORDRE --}}
    <div>
        <label class="form-label-clean">Ordre</label>
        <input
            type="number"
            name="sort_order"
            class="form-control-clean"
            value="{{ old('sort_order', $menu->sort_order ?? 0) }}"
            min="0"
        >
    </div>

    {{-- STATUT --}}
    <div class="form-group-full">
        <label class="form-label-clean">Statut</label>
        <select name="is_active" class="form-select-clean">
            <option value="1" {{ old('is_active', $menu->is_active ?? 1) == 1 ? 'selected' : '' }}>Actif</option>
            <option value="0" {{ old('is_active', $menu->is_active ?? 1) == 0 ? 'selected' : '' }}>Inactif</option>
        </select>
    </div>

</div>