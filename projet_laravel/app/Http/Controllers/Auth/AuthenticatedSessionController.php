<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Rediriger vers la page d'accueil
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->route('backend.dashboard')
                ->with('success', 'Bienvenue sur votre tableau de bord administrateur.');
        }

        if ($user->role === 'sponsor') {
            return redirect()->route('sponsor.dashboard')
                ->with('success', 'Bienvenue sur votre tableau de bord sponsor.');
        }

        return redirect()->route('home')
            ->with('success', 'Connexion réussie ! Bienvenue sur EcoEvents.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
