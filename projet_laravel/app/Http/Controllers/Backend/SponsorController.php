<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Models\Donation;
use App\Models\User;
use App\Mail\SponsorValidatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class SponsorController extends Controller
{
    /**
     * Afficher la liste des sponsors pour l'admin.
     */
    public function index(): View
    {
        $sponsors = Sponsor::with(['events'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_sponsors' => Sponsor::count(),
            'pending_sponsors' => Sponsor::pending()->count(),
            'validated_sponsors' => Sponsor::validated()->count(),
            'rejected_sponsors' => Sponsor::rejected()->count(),
        ];

        return view('backend.sponsors.index', compact('sponsors', 'stats'));
    }

    /**
     * Afficher les détails d'un sponsor.
     */
    public function show(Sponsor $sponsor): View
    {
        $sponsor->load(['events', 'donations']);

        return view('backend.sponsors.show', compact('sponsor'));
    }

    /**
     * Afficher les demandes de sponsoring en attente.
     */
    public function pending(): View
    {
        $pendingSponsors = Sponsor::pending()
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('backend.sponsors.pending', compact('pendingSponsors'));
    }

    /**
     * Valider un sponsor.
     */
    public function validate(Sponsor $sponsor)
    {
        if (!$sponsor->isPending()) {
            return redirect()->back()->with('error', 'Ce sponsor ne peut pas être validé.');
        }

        // Create a user account for the sponsor
        $password = Str::random(12);
        $user = User::create([
            'name' => $sponsor->company_name,
            'email' => $sponsor->contact_email,
            'password' => Hash::make($password),
            'role' => 'sponsor',
            'email_verified_at' => now(),
        ]);

        $sponsor->update([
            'status' => 'validated',
            'validated_at' => now(),
            'validated_by' => Auth::id(),
            'user_id' => $user->id,
        ]);

        // Send an email to the sponsor with their login credentials
        Mail::to($sponsor->contact_email)->send(new SponsorValidatedMail($sponsor, $password));

        return redirect()->route('backend.sponsors.index')
            ->with('success', 'Sponsor validé avec succès. Un compte utilisateur a été créé et un email de bienvenue a été envoyé.');
    }

    /**
     * Rejeter un sponsor.
     */
    public function reject(Request $request, Sponsor $sponsor)
    {
        if (!$sponsor->isPending()) {
            return redirect()->back()->with('error', 'Ce sponsor ne peut pas être rejeté.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $sponsor->update([
            'status' => 'rejected',
            'additional_info' => $sponsor->additional_info . "\n\nRaison du rejet: " . $request->rejection_reason,
        ]);

        return redirect()->route('backend.sponsors.index')
            ->with('success', 'Sponsor rejeté avec succès.');
    }

    /**
     * Supprimer un sponsor (soft delete).
     */
    public function destroy(Request $request, Sponsor $sponsor)
    {
        $request->validate([
            'deletion_reason' => 'required|string|min:10',
        ]);

        $sponsor->delete();

        return redirect()->route('backend.sponsors.index')
            ->with('success', 'Sponsor supprimé avec succès.');
    }

    /**
     * Restaurer un sponsor supprimé.
     */
    public function restore(Sponsor $sponsor)
    {
        if (!$sponsor->trashed()) {
            return redirect()->back()->with('error', 'Ce sponsor n\'est pas supprimé.');
        }

        $sponsor->restore();
        $sponsor->update([
            'deleted_at' => null,
            'deleted_by' => null,
            'deletion_reason' => null,
        ]);

        return redirect()->route('backend.sponsors.index')
            ->with('success', 'Sponsor restauré avec succès.');
    }

    /**
     * Afficher les sponsors supprimés.
     */
    public function trashed(): View
    {
        $sponsors = Sponsor::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('backend.sponsors.trashed', compact('sponsors'));
    }

    /**
     * List sponsors that requested deletion (admin review).
     */
    public function deletionRequested(): View
    {
        $sponsors = Sponsor::deletionRequested()
            ->orderByDesc('updated_at')
            ->paginate(15);
        return view('backend.sponsors.deletion_requested', compact('sponsors'));
    }

    // (Duplicate deletionRequested() method removed)

    // Self-edit removed: sponsor now reuses unified user profile pages.

    /**
     * Sponsor requests account deletion (flag only; admin will process)
     */
    public function requestDeletion(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'sponsor') {
            abort(403);
        }
        Log::info('Sponsor requestDeletion initiated', [
            'user_id' => $user?->id,
            'has_sponsor' => (bool)$user?->sponsor,
            'sponsor_status' => $user?->sponsor?->status,
        ]);
        $data = $request->validate([
            'reason' => 'required|string|min:10|max:500'
        ]);
        $sponsor = $user->sponsor;
        if (!$sponsor) {
            // No sponsor record yet -> nothing to flag; suggest immediate downgrade instead
            Log::warning('Sponsor deletion request without sponsor relation', ['user_id' => $user->id]);
            return redirect()->back()->with('error', "Aucun profil sponsor associé. Utilisez la suppression immédiate pour abandonner le statut.");
        }
        if ($sponsor->isDeletionRequested()) {
            Log::notice('Duplicate deletion request attempt', ['sponsor_id' => $sponsor->id]);
            return redirect()->back()->with('error', 'Une demande de suppression est déjà en attente.');
        }
        $sponsor->update([
            'status' => 'deletion_requested',
            'deletion_reason' => $data['reason'],
        ]);
        Log::info('Sponsor deletion request flagged', ['sponsor_id' => $sponsor->id]);
        return redirect()->route('sponsor.dashboard')->with('success', 'Demande de suppression envoyée à l\'administration.');
    }

    /**
     * Sponsor performs immediate self soft-delete (bypass admin). Optional feature.
     */
    public function selfDeleteNow(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'sponsor') {
            abort(403);
        }
        Log::info('Sponsor selfDeleteNow initiated', [
            'user_id' => $user?->id,
            'has_sponsor' => (bool)$user?->sponsor,
            'sponsor_status' => $user?->sponsor?->status,
        ]);
        $request->validate([
            'confirm' => 'required|in:DELETE'
        ], [
            'confirm.in' => 'Vous devez taper DELETE pour confirmer.'
        ]);
        $sponsor = $user->sponsor;
        if ($sponsor) {
            // Soft delete sponsor record and downgrade
            $sponsor->update([
                'deletion_reason' => $sponsor->deletion_reason ?: 'Suppression immédiate par le sponsor.',
                'status' => $sponsor->status === 'deletion_requested' ? $sponsor->status : 'deletion_requested'
            ]);
            $sponsor->delete();
            Log::info('Sponsor soft deleted via selfDeleteNow', ['sponsor_id' => $sponsor->id]);
        }
        Log::warning('selfDeleteNow called without sponsor relation', ['user_id' => $user->id]);

        // Optionally change user role (keep account so donations history is intact)
        // Downgrade role (avoid static analysis complaining about dynamic model methods)
        DB::table('users')->where('id', $user->id)->update(['role' => 'user', 'updated_at' => now()]);
        Log::info('User role downgraded after selfDeleteNow', ['user_id' => $user->id]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Votre profil sponsor a été supprimé.');
    }

    /**
     * Admin processes a deletion request (soft delete sponsor)
     */
    public function processDeletion(Request $request, Sponsor $sponsor)
    {
        if ($sponsor->status !== 'deletion_requested') {
            return redirect()->back()->with('error', 'Ce sponsor n\'a pas demandé de suppression.');
        }
        $sponsor->delete();
        return redirect()->route('backend.sponsors.index')->with('success', 'Sponsor supprimé suite à la demande.');
    }
}
