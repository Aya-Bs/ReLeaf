<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
    public function store(Request $request): RedirectResponse
    {
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'name' => ['required', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
        'birth_date' => ['nullable', 'date', 'before:today'],
        'city' => ['nullable', 'string', 'max:255'],
        'country' => ['nullable', 'string', 'max:2'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'in:user,organizer'], // ADD THIS LINE
        'terms' => ['required', 'accepted'],
    ], [
        'first_name.required' => 'Le prénom est obligatoire.',
        'last_name.required' => 'Le nom est obligatoire.',
        'phone.regex' => 'Le format du numéro de téléphone n\'est pas valide.',
        'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
        'birth_date.date' => 'La date de naissance n\'est pas valide.',
        'country.max' => 'Le code pays ne doit pas dépasser 2 caractères.',
        'role.required' => 'Le type de compte est obligatoire.', // ADD THIS LINE
        'role.in' => 'Le type de compte sélectionné n\'est pas valide.', // ADD THIS LINE
        'terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
        'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
    ]);

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
    }
}
