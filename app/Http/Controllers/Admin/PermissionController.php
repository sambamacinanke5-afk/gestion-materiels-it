<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissions.view')->only(['index']);
        $this->middleware('permission:permissions.create')->only(['create', 'store']);
        $this->middleware('permission:permissions.update')->only(['edit', 'update']);
        $this->middleware('permission:permissions.delete')->only(['destroy']);
    }       

    public function index()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ]);

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission créée avec succès.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permission->id],
        ]);

        $permission->update([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission mise à jour avec succès.');
    }

    public function destroy(Permission $permission)
    {
        $protected = [
            'dashboard.view',
        ];

        if (in_array($permission->name, $protected)) {
            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Cette permission est protégée et ne peut pas être supprimée.');
        }

        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission supprimée avec succès.');
    }
}