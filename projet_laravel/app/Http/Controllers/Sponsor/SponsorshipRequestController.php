<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\SponsorEvent;
use App\Notifications\SponsorshipRequestStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SponsorshipRequestController extends Controller
{
    public function index()
    {
        $sponsor = Auth::user()->sponsor;
        if (!$sponsor) {
            return redirect()->route('sponsor.dashboard')->with('error', 'Aucun profil sponsor associé.');
        }

        $requests = SponsorEvent::with('event')
            ->where('sponsor_id', $sponsor->id)
            ->pending()
            ->latest()
            ->get();

        return view('sponsor.requests.index', compact('requests'));
    }

    public function accept(SponsorEvent $sponsorEvent): RedirectResponse
    {
        $sponsor = Auth::user()->sponsor;
        if (!$sponsor || $sponsorEvent->sponsor_id !== $sponsor->id) {
            return redirect()->route('sponsor.requests.index')->with('error', 'Action non autorisée.');
        }

        $sponsorEvent->update(['status' => 'active']);
        // notify organizer
        $organizer = $sponsorEvent->event?->user;
        if ($organizer) {
            $organizer->notify(new SponsorshipRequestStatusNotification($sponsorEvent));
        }

        return redirect()->route('sponsor.requests.index')->with('success', 'Demande acceptée.');
    }

    public function decline(SponsorEvent $sponsorEvent): RedirectResponse
    {
        $sponsor = Auth::user()->sponsor;
        if (!$sponsor || $sponsorEvent->sponsor_id !== $sponsor->id) {
            return redirect()->route('sponsor.requests.index')->with('error', 'Action non autorisée.');
        }

        $sponsorEvent->update(['status' => 'cancelled']);
        // notify organizer
        $organizer = $sponsorEvent->event?->user;
        if ($organizer) {
            $organizer->notify(new SponsorshipRequestStatusNotification($sponsorEvent));
        }

        return redirect()->route('sponsor.requests.index')->with('success', 'Demande refusée.');
    }
}
