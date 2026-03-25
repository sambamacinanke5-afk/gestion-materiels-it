<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:menus.view')->only(['index']);
        $this->middleware('permission:menus.create')->only(['create', 'store']);
        $this->middleware('permission:menus.update')->only(['edit', 'update']);
        $this->middleware('permission:menus.delete')->only(['destroy']);
    }
    
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('sort_order')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        $parents = Menu::whereNull('parent_id')->orderBy('title')->get();

        return view('admin.menus.create', compact('permissions', 'parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'permission_name' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu créé avec succès.');
    }

    public function edit(Menu $menu)
    {
        $permissions = Permission::orderBy('name')->get();
        $parents = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('title')
            ->get();

        return view('admin.menus.edit', compact('menu', 'permissions', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'permission_name' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu mis à jour avec succès.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu supprimé avec succès.');
    }
}