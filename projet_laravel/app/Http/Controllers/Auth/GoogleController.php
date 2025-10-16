<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        try {
            \Log::info('Début redirectToGoogle');
            $redirectUrl = Socialite::driver('google')->redirect();
            \Log::info('URL de redirection Google générée avec succès');

            return $redirectUrl;
        } catch (\Exception $e) {
            \Log::error('Erreur redirectToGoogle: '.$e->getMessage());
            \Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->route('login')
                ->with('error', 'Erreur de configuration Google: '.$e->getMessage());
        }
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            \Log::info('Début handleGoogleCallback');
            \Log::info('Tentative de récupération de l\'utilisateur Google');
            $googleUser = Socialite::driver('google')->stateless()->user();
            \Log::info('Utilisateur Google récupéré avec succès');

            // Check if user already exists with this email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user);
                session(['2fa_verified' => true]);

                if ($user->role === 'admin') {
                    return redirect()->route('backend.dashboard')
                        ->with('success', 'Bienvenue sur votre tableau de bord administrateur.');
                }

                return redirect()->route('home')
                    ->with('success', 'Connexion réussie avec Google !');
            } else {
                // Create new user
                $nameParts = explode(' ', $googleUser->name, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $user = User::create([
                    'name' => $googleUser->name,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user',
                    'email_verified_at' => now(), // Google emails are pre-verified
                ]);

                // Create profile
                $user->profile()->create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'bio' => 'Utilisateur connecté avec Google',
                ]);

                Auth::login($user);
                session(['2fa_verified' => true]);

                if ($user->role === 'admin') {
                    return redirect()->route('backend.dashboard')
                        ->with('success', 'Bienvenue sur votre tableau de bord administrateur.');
                }

                return redirect()->route('home')
                    ->with('success', 'Compte créé et connecté avec succès via Google !');
            }
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: '.$e->getMessage());
            \Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->route('login')
                ->with('error', 'Erreur lors de la connexion avec Google: '.$e->getMessage());
        }
    }
}
