<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\Profile;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Log;
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
        // Ensure sponsor placeholder exists if user has sponsor role but no relation yet
        if ($user->role === 'sponsor' && !$user->sponsor) {
            $placeholder = [
                'user_id' => $user->id,
                'company_name' => $user->name ?: ('Sponsor ' . $user->id),
                'contact_email' => $user->email,
                'motivation' => 'Profil sponsor à compléter.',
                'sponsorship_type' => 'argent',
                'status' => 'pending'
            ];
            try {
                $s = Sponsor::create($placeholder);
                Log::info('Auto-created sponsor placeholder in ProfileController@show', ['user_id' => $user->id, 'sponsor_id' => $s->id]);
            } catch (\Throwable $e) {
                Log::error('Failed creating sponsor placeholder', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
            // Refresh to load new relation
            $user->refresh();
        }

        // Load user certifications (if relation exists) with related reservation.event & issuer
        $certifications = method_exists($user, 'certifications')
            ? $user->certifications()
            ->with(['reservation.event', 'issuedBy'])
            ->orderBy('date_awarded', 'desc')
            ->get()
            : collect();

        // Basic profile statistics
        $stats = [
            'events_created' => 0, // TODO: implement actual count if/when events ownership is added
            'participations' => (method_exists($user, 'reservations') && method_exists($user->reservations(), 'confirmed'))
                ? $user->reservations()->confirmed()->count()
                : 0,
            'certificates_earned' => $certifications->count(),
            'days_on_platform' => $user->created_at?->diffInDays() ?? 0,
        ];

        return view('frontend.profile.show', compact('user', 'certifications', 'stats'));
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

        // Guarantee sponsor relation (consistent update path)
        if ($user->role === 'sponsor' && !$user->sponsor) {
            try {
                $user->sponsor()->create([
                    'company_name' => $user->name ?: ('Sponsor ' . $user->id),
                    'contact_email' => $user->email,
                    'motivation' => 'Profil sponsor à compléter.',
                    'sponsorship_type' => 'argent',
                    'status' => 'pending'
                ]);
                $user->refresh();
                Log::info('Sponsor placeholder auto-created in update()', ['user_id' => $user->id]);
            } catch (\Throwable $e) {
                Log::error('Failed to auto-create sponsor in update()', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

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

        // If sponsor, update sponsor fields (include empty strings -> convert to null for nullable fields)
        if ($user->role === 'sponsor' && $user->sponsor) {
            $sponsorFields = ['company_name', 'contact_email', 'contact_phone', 'website', 'address', 'city', 'country', 'motivation', 'additional_info'];
            $payload = [];
            foreach ($sponsorFields as $field) {
                if ($request->has($field)) {
                    $val = $request->input($field);
                    // Normalize empty string to null for nullable columns
                    $payload[$field] = ($val === '') ? null : $val;
                }
            }
            if (!empty($payload)) {
                $before = $user->sponsor->only(array_keys($payload));
                $user->sponsor->fill($payload);
                if ($user->sponsor->isDirty()) {
                    $user->sponsor->save();
                    Log::info('Sponsor fields updated from profile.update', [
                        'user_id' => $user->id,
                        'changes' => $user->sponsor->getChanges(),
                        'before' => $before
                    ]);
                } else {
                    Log::info('Sponsor update called but no changes detected', ['user_id' => $user->id]);
                }
            }
        }

        $redirectRoute = $user->role === 'sponsor' ? 'sponsor.profile' : 'profile.show';
        return redirect()->route($redirectRoute)
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
