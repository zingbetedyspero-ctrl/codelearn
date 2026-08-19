<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(Request $request)
    {
        if ($request->filled('redirect')) {
            session(['url.intended' => $request->query('redirect')]);
        }

        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Identifiants incorrects.'])
                ->onlyInput('email');
        }

        // Vérification du statut du compte (RG implicite)
        if (Auth::user()->statut_compte !== 'actif') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Votre compte est désactivé. Contactez un administrateur.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Connexion réussie.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }
}