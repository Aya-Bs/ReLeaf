<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\SponsorRewardService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\SponsorEvent;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the sponsor dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(SponsorRewardService $rewards): View
    {
        $events = Event::where('status', 'published')->latest()->take(5)->get();
        $user = Auth::user();
        $rewardStats = null;
        $stats = null;
        $sponsorName = $user?->sponsor?->company_name ?? ($user?->name ?? 'Sponsor');
        if ($user && $user->sponsor) {
            $sponsorId = $user->sponsor->id;
            $rewardStats = $rewards->getSponsorStats($sponsorId);

            $cutoff = Carbon::now()->subDays(90);
            $donationsQuery = Donation::confirmed()->where('type', 'sponsor')->where('sponsor_id', $sponsorId);
            $confirmedDonationsSum = (clone $donationsQuery)->sum('amount');
            $confirmedDonationsCount = (clone $donationsQuery)->count();
            $recent90 = (clone $donationsQuery)
                ->where(function ($q) use ($cutoff) {
                    $q->whereNotNull('donated_at')->where('donated_at', '>=', $cutoff)
                        ->orWhere(function ($qq) use ($cutoff) {
                            $qq->whereNull('donated_at')->where('created_at', '>=', $cutoff);
                        });
                })
                ->sum('amount');

            $eventsSupported = SponsorEvent::where('sponsor_id', $sponsorId)->active()->distinct('event_id')->count('event_id');
            $sponsorshipsSum = SponsorEvent::where('sponsor_id', $sponsorId)->active()->sum('amount');
            $totalSupport = (float)$confirmedDonationsSum + (float)$sponsorshipsSum;

            $stats = [
                'total_support' => $totalSupport,
                'donations_sum' => (float)$confirmedDonationsSum,
                'sponsorships_sum' => (float)$sponsorshipsSum,
                'donations_count' => (int)$confirmedDonationsCount,
                'events_supported' => (int)$eventsSupported,
                'recent_90d' => (float)$recent90,
            ];
        }
        return view('sponsor.dashboard', compact('events', 'rewardStats', 'stats', 'sponsorName'));
    }
}
