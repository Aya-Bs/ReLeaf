<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Sponsor;
use App\Mail\DonationReceivedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DonationController extends Controller
{
    /**
     * List current user's donations (for sponsors & regular users)
     */
    public function index()
    {
        $query = Donation::query()->with(['sponsor', 'event']);
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'sponsor' && $user->sponsor) {
                $query->where(function ($q) use ($user) {
                    $q->where('sponsor_id', $user->sponsor->id)->orWhere('user_id', $user->id);
                });
            } else {
                $query->where('user_id', $user->id);
            }
        } else {
            abort(403);
        }

        $donations = $query->latest()->paginate(15);
        return view('donations.index', compact('donations'));
    }
    /**
     * Display the donation form for a specific event.
     */
    public function create(Event $event): View
    {
        if (!$event->isPublished()) {
            abort(404);
        }

        $sponsors = Sponsor::validated()->get();

        return view('donations.create', compact('event', 'sponsors'));
    }

    /**
     * Store a new donation.
     */
    public function store(StoreDonationRequest $request, Event $event)
    {
        if (!$event->isPublished()) {
            return redirect()->back()->with('error', 'Cet événement n\'est pas disponible pour les dons.');
        }

        $sponsorId = null;

        // Handle sponsor logic based on donation type
        if ($request->input('type') === 'sponsor') {
            // Case: logged-in sponsor user with existing sponsor relation
            if (Auth::check() && Auth::user()->role === 'sponsor') {
                if (Auth::user()->sponsor) {
                    $sponsor = Auth::user()->sponsor;
                    if (!$sponsor->isValidated()) {
                        return redirect()->back()->with('error', 'Votre compte sponsor n\'est pas encore validé.');
                    }
                    $sponsorId = $sponsor->id;
                }
                // If sponsor user has no sponsor relation yet, we still record type='sponsor' with null sponsor_id
            } elseif ($request->filled('sponsor_name')) {
                // Non-sponsor role providing sponsor company name
                $sponsor = Sponsor::firstOrCreate(
                    ['company_name' => $request->sponsor_name],
                    [
                        'contact_email' => $request->donor_email,
                        'status' => 'pending',
                    ]
                );
                $sponsorId = $sponsor->id;
            } else {
                return redirect()->back()->withErrors(['sponsor_name' => 'Le nom du sponsor est requis pour un don de type sponsor.'])->withInput();
            }
        }

        // Create the donation record
        $donation = Donation::create([
            'event_id' => $event->id,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'sponsor_id' => $sponsorId,
            'user_id' => Auth::id(), // Can be null for guests
            'amount' => $request->amount,
            'currency' => $request->currency,
            'type' => $request->type,
            'status' => 'pending', // All donations start as pending until confirmed
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'donated_at' => now(),
        ]);

        // Send confirmation email
        try {
            Mail::to($donation->donor_email)->queue(new DonationReceivedMail($donation));
        } catch (\Throwable $e) {
            // Fail silently (could log later)
        }

        // Redirect to the success page with the newly created donation
        return redirect()->route('donations.success', $donation)
            ->with('success', 'Votre don a été enregistré avec succès. Merci pour votre soutien!');
    }

    /**
     * Display the success page after a donation.
     */
    public function success(Donation $donation): View
    {
        return view('donations.success', compact('donation'));
    }

    /**
     * Edit form for a donation (user/sponsor within 24h & pending)
     */
    public function edit(Donation $donation)
    {
        $user = Auth::user();
        if (!$user || !$donation->canBeModifiedBy($user)) {
            return redirect()->back()->with('error', 'Ce don ne peut plus être modifié.');
        }
        return view('donations.edit', compact('donation'));
    }

    /**
     * Update donation (limited fields)
     */
    public function update(Request $request, Donation $donation)
    {
        $user = Auth::user();
        if (!$user || !$donation->canBeModifiedBy($user)) {
            return redirect()->back()->with('error', 'Modification non autorisée.');
        }

        $data = $request->validate([
            'amount' => 'required|numeric|min:1|max:100000',
            'currency' => 'required|string|in:EUR,USD,TND',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $donation->update($data);
        return redirect()->route('donations.success', $donation)->with('success', 'Don mis à jour.');
    }

    /**
     * Delete donation within 24h & pending.
     */
    public function destroy(Donation $donation)
    {
        $user = Auth::user();
        if (!$user || !$donation->canBeDeletedBy($user)) {
            return redirect()->back()->with('error', 'Suppression non autorisée.');
        }
        $donation->delete();
        return redirect()->route('donations.list')->with('success', 'Don supprimé.');
    }

    /**
     * Display donations for a specific event (for event organizers and admins).
     */
    public function eventDonations(Event $event): View
    {
        if ($event->user_id !== Auth::id() && (!Auth::user() || Auth::user()->role !== 'admin')) {
            abort(403);
        }

        $donations = $event->donations() // Corrected from $donation->donations()
            ->with(['sponsor', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_donations' => $donations->total(),
            'total_amount' => $donations->sum('amount'),
            'confirmed_amount' => $donations->where('status', 'confirmed')->sum('amount'),
            'pending_amount' => $donations->where('status', 'pending')->sum('amount'),
        ];

        return view('donations.event-donations', compact('event', 'donations', 'stats'));
    }

    /**
     * Confirm a donation (admins only).
     */
    public function confirm(Donation $donation)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (!$donation->isPending()) {
            return redirect()->back()->with('error', 'Ce don ne peut pas être confirmé.');
        }

        $donation->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Don confirmé avec succès.');
    }

    /**
     * Cancel a donation (admins only).
     */
    public function cancel(Request $request, Donation $donation)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'cancellation_reason' => 'required|string|min:10',
        ]);

        $donation->update([
            'status' => 'cancelled',
            'notes' => $donation->notes . "\n\nRaison d'annulation: " . $request->cancellation_reason,
        ]);

        return redirect()->back()->with('success', 'Don annulé avec succès.');
    }

    /**
     * Display all donations (admins only).
     */
    public function adminIndex(): View
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $donations = Donation::with(['event', 'sponsor', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_donations' => Donation::count(),
            'total_amount' => Donation::sum('amount'),
            'confirmed_amount' => Donation::where('status', 'confirmed')->sum('amount'),
            'pending_amount' => Donation::where('status', 'pending')->sum('amount'),
        ];

        return view('backend.donations.index', compact('donations', 'stats'));
    }
}
