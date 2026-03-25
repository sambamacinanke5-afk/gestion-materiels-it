<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;

class UserController extends Controller
{
   public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.update')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
        $this->middleware('permission:users.suspend')->only(['suspend', 'activate']);
        $this->middleware('permission:users.reset-password')->only(['sendPasswordReset']);
    }

    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles($validated['roles'] ?? []);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function suspend(User $user)
{
    if (auth()->id() === $user->id) {
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Vous ne pouvez pas suspendre votre propre compte.');
    }

    $user->update([
        'is_suspended' => true,
        'suspended_at' => now(),
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'Utilisateur suspendu avec succès.');
}

public function activate(User $user)
{
    $user->update([
        'is_suspended' => false,
        'suspended_at' => null,
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'Utilisateur réactivé avec succès.');
}

public function sendPasswordReset(User $user)
{
    $status = Password::sendResetLink([
        'email' => $user->email,
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', __($status));
}
}