<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        // La validation est automatiquement effectuée par RegisterUserRequest
        
        try {
            $user = User::create([
        'name' => $request->name,
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'phone' => $request->phone,
        'birth_date' => $request->birth_date,
        'city' => $request->city,
        'country' => $request->country,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // USE THE ROLE FROM THE REQUEST
    ]);

            // Créer automatiquement le profil avec les informations de base
            $user->profile()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);

            event(new Registered($user));

            // Connecter l'utilisateur pour qu'il puisse voir la page de vérification
            Auth::login($user);

            return redirect()->route('verification.notice');
        } catch (\Exception $e) {
            // Log l'erreur pour debug
            \Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la création du compte.']);
        }
    }
}
