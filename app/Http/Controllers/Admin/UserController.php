<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.user.index', compact('users'));
    }
    public function create()
    {
        //
        $users = User::all();
        // $medecins = Medecin::all();
        $roles = role::all();


        return view('admin.user.create', compact("users", "roles"));
    }
    // Méthode pour stocker le nouvel utilisateur
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        // Création de l'utilisateur
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('user.index')->with('success', 'Utilisateur créé avec succès.');
    }
    public function edit($id)
    {
        // Récupérer l'utilisateur par son ID
        $user = User::findOrFail($id);

        // Récupérer tous les rôles pour le select
        $roles = \App\Models\Role::all();

        // Retourner la vue edit avec les données
        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Mise à jour
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;

        // Si un nouveau mot de passe est fourni, on le hash
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }


    public function destroy($id)
    {
        // Récupérer l'utilisateur par son ID
        $user = User::findOrFail($id);

        // Supprimer l'utilisateur
        $user->delete();

        // Rediriger vers la liste avec un message de succès
        return redirect()->route('user.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Inverse l'état
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'suspendu';

        return redirect()->route('user.index')->with('success', "Utilisateur $status avec succès.");
    }
}
