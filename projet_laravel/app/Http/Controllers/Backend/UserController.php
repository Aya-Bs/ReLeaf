<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users with their profiles.
     */
    public function index(Request $request): View
    {
        $query = User::with('profile')->where('role', '!=', 'admin');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($profile) use ($search) {
                        $profile->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by eco ambassador
        if ($request->filled('eco_ambassador')) {
            $query->whereHas('profile', function ($profile) {
                $profile->where('is_eco_ambassador', true);
            });
        }

        $users = $query->paginate(15);

        return view('backend.users.index', compact('users'));
    }

    /**
     * Display the specified user with profile details.
     */
    public function show(User $user): View
    {
        $user->load('profile');

        return view('backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $user->load('profile');
        $user->createProfileIfNotExists();

        return view('backend.users.edit', compact('user'));
    }

    /**
     * Update the specified user and profile.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'is_eco_ambassador' => 'boolean',
        ]);

        $user->update($request->only(['name', 'email']));

        if ($user->profile) {
            $user->profile->update([
                'is_eco_ambassador' => $request->boolean('is_eco_ambassador'),
            ]);
        }

        return redirect()->route('backend.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès !');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('backend.users.index')
            ->with('success', 'Utilisateur supprimé avec succès !');
    }

    /**
     * Toggle eco ambassador status.
     */
    public function toggleEcoAmbassador(User $user): RedirectResponse
    {
        $user->createProfileIfNotExists();

        $user->profile->update([
            'is_eco_ambassador' => ! $user->profile->is_eco_ambassador,
        ]);

        $status = $user->profile->is_eco_ambassador ? 'activé' : 'désactivé';

        return redirect()->back()
            ->with('success', "Statut d'ambassadeur écologique {$status} pour {$user->name}");
    }
}
