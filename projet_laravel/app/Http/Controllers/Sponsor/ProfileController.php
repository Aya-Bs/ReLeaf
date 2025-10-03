<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Donation;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the dedicated sponsor profile page.
     */
    public function show(): View
    {
        $user = Auth::user();
        // Touch relations to ensure they are loaded (simple lazy load)
        if ($user) {
            $user->sponsor; // lazy load
            $user->profile; // lazy load
        }

        // PATH 1: Auto-create a minimal sponsor record if user role is sponsor but relation missing
        if ($user && $user->role === 'sponsor' && !$user->sponsor) {
            $placeholderData = [
                'user_id' => $user->id,
                'company_name' => $user->name ?: ('Sponsor ' . $user->id),
                'contact_email' => $user->email,
                'motivation' => 'Profil généré automatiquement. Merci de compléter les informations.',
                'sponsorship_type' => 'argent',
                'status' => 'pending', // keep pending until admin validates
            ];
            $sponsor = Sponsor::create($placeholderData);
            Log::info('Auto-created placeholder sponsor profile for user without sponsor relation', [
                'user_id' => $user->id,
                'sponsor_id' => $sponsor->id,
                'status' => $sponsor->status,
            ]);
            // Reload relation for view consistency
            // Access again to ensure it's available
            $user->sponsor;
        }

        $sponsor = $user->sponsor; // can be null if not yet validated

        // Aggregate donation stats (include donations as plain user OR as sponsor entity)
        $donationsQuery = Donation::query()->where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->sponsor) {
                $q->orWhere('sponsor_id', $user->sponsor->id);
            }
        });

        $totalDonations = (float) $donationsQuery->sum('amount');
        $donationsCount = (int) (clone $donationsQuery)->count();

        $recentDonations = (clone $donationsQuery)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        $eventsSponsoredCount = $sponsor ? $sponsor->events()->count() : 0;
        $validatedAt = $sponsor?->validated_at;
        $joinedSinceDays = $validatedAt ? now()->diffInDays($validatedAt) : $user->created_at->diffInDays();

        return view('sponsor.profile', compact(
            'user',
            'sponsor',
            'totalDonations',
            'donationsCount',
            'recentDonations',
            'eventsSponsoredCount',
            'validatedAt',
            'joinedSinceDays'
        ));
    }
}
