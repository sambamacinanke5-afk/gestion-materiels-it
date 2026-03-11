<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Tableau de bord Admin
     */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Tableau de bord Utilisateur
     */


    /**
     * Connexion
     */
    public function login(Request $request)
    {
        // Validation des champs
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Récupération de l'utilisateur
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email incorrect'])->withInput();
        }

        // Vérification si suspendu
        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Votre compte est suspendu. Contactez un administrateur.']);
        }

        // Vérification sécurisée du mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect'])->withInput();
        }

        // Connexion
        Auth::login($user);

        // Redirection selon rôle
        switch ($user->role_id) {
            case 1:
                return redirect()->route('admin.dashboard');
            case 2:
                return redirect()->route('user.dashboard');
            default:
                Auth::logout();
                return back()->withErrors(['role' => 'Rôle invalide']);
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
