<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->createProfileIfNotExists();

        return view('frontend.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->createProfileIfNotExists();

        return view('frontend.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(UserProfileRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
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

        // If sponsor, allow inline sponsor company updates (limited subset)
        if ($user->role === 'sponsor' && $user->sponsor) {
            $sponsorData = $request->only(['company_name', 'contact_email', 'contact_phone', 'website', 'address', 'city', 'country', 'motivation', 'additional_info']);
            $filtered = array_filter($sponsorData, function ($v) {
                return !is_null($v);
            });
            if (!empty($filtered)) {
                $user->sponsor->update($filtered);
            }
        }

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->profile->avatar);
            $user->profile->update(['avatar' => null]);
        }

        return redirect()->route('profile.edit')
            ->with('success', 'Avatar supprimé avec succès !');
    }
}
