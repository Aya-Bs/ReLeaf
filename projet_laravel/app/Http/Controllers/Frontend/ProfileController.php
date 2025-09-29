<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(): View
    {
        $user = auth()->user();
        $user->createProfileIfNotExists();

        // Charger les certificats de l'utilisateur
        $certifications = $user->certifications()
            ->with(['reservation.event', 'issuedBy'])
            ->orderBy('date_awarded', 'desc')
            ->get();

        // Statistiques
        $stats = [
            'events_created' => 0, // À implémenter si nécessaire
            'participations' => $user->reservations()->confirmed()->count(),
            'certificates_earned' => $certifications->count(),
            'days_on_platform' => $user->created_at->diffInDays()
        ];

        return view('frontend.profile.show', compact('user', 'certifications', 'stats'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit(): View
    {
        $user = auth()->user();
        $user->createProfileIfNotExists();

        return view('frontend.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(UserProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->createProfileIfNotExists();

        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->profile->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->profile->avatar);
            }

            $avatarName = time() . '_' . $user->id . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('avatars', $avatarName, 'public');
            $data['avatar'] = $avatarName;
        }

        $user->profile->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->profile->avatar);
            $user->profile->update(['avatar' => null]);
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Avatar supprimé avec succès !');
    }
}
