<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\User;
use App\Mail\VolunteerApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    /**
     * Display a listing of volunteers.
     */
    public function index(Request $request): View
    {
        $query = Volunteer::with('user');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $volunteers = $query->latest()->paginate(15);

        $stats = [
            'total' => Volunteer::count(),
            'active' => Volunteer::where('status', 'active')->count(),
            'inactive' => Volunteer::where('status', 'inactive')->count(),
            'pending' => Volunteer::where('approval_status', 'pending')->count(),
            'approved' => Volunteer::where('approval_status', 'approved')->count(),
            'rejected' => Volunteer::where('approval_status', 'rejected')->count(),
        ];

        return view('backend.volunteers.index', compact('volunteers', 'stats'));
    }

    /**
     * Display the specified volunteer.
     */
    public function show(Volunteer $volunteer): View
    {
        $volunteer->load(['user', 'assignments.assignable']);
        
        $recentAssignments = $volunteer->assignments()
            ->with('assignable')
            ->latest()
            ->limit(5)
            ->get();

        $statistics = [
            'total_assignments' => $volunteer->assignments()->count(),
            'completed_assignments' => $volunteer->assignments()->where('status', 'completed')->count(),
            'total_hours_worked' => $volunteer->assignments()->sum('hours_worked'),
        ];

        return view('backend.volunteers.show', compact('volunteer', 'recentAssignments', 'statistics'));
    }

    /**
     * Show the form for editing the specified volunteer.
     */
    public function edit(Volunteer $volunteer): View
    {
        return view('backend.volunteers.edit', compact('volunteer'));
    }

    /**
     * Update the specified volunteer.
     */
    public function update(Request $request, Volunteer $volunteer)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'bio' => 'nullable|string|max:1000',
            'motivation' => 'nullable|string|max:1000',
            'max_hours_per_week' => 'nullable|integer|min:1|max:168',
        ]);

        $volunteer->update($validated);

        return redirect()->route('backend.volunteers.show', $volunteer)
            ->with('success', 'Profil volontaire mis à jour avec succès !');
    }

    /**
     * Remove the specified volunteer.
     */
    public function destroy(Volunteer $volunteer)
    {
        // Annuler toutes les missions actives
        $activeAssignments = $volunteer->assignments()->whereIn('status', ['pending', 'approved'])->get();
        foreach ($activeAssignments as $assignment) {
            $assignment->update([
                'status' => 'cancelled',
                'notes' => ($assignment->notes ?? '') . "\nMission annulée - Profil volontaire supprimé par l'admin."
            ]);
        }

        $volunteer->delete();

        return redirect()->route('backend.volunteers.index')
            ->with('success', 'Profil volontaire supprimé avec succès.');
    }

    /**
     * Approve a volunteer.
     */
    public function approve(Volunteer $volunteer)
    {
        if ($volunteer->isApproved()) {
            return redirect()->back()
                ->with('warning', 'Ce volontaire est déjà approuvé.');
        }

        $volunteer->approve(Auth::id());

        // Envoyer l'email d'approbation
        Mail::to($volunteer->user->email)->send(new VolunteerApproved($volunteer));

        return redirect()->back()
            ->with('success', 'Volontaire approuvé avec succès. Un email de confirmation a été envoyé.');
    }

    /**
     * Reject a volunteer.
     */
    public function reject(Volunteer $volunteer)
    {
        if ($volunteer->isRejected()) {
            return redirect()->back()
                ->with('warning', 'Ce volontaire a déjà été rejeté.');
        }

        $volunteer->reject(Auth::id());

        return redirect()->back()
            ->with('success', 'Volontaire rejeté avec succès.');
    }

    /**
     * Reset volunteer approval status to pending.
     */
    public function reset(Volunteer $volunteer)
    {
        $volunteer->update([
            'approval_status' => 'pending',
            'approved_at' => null,
            'approved_by' => null,
            'status' => 'inactive'
        ]);

        return redirect()->back()
            ->with('success', 'Statut d\'approbation réinitialisé avec succès.');
    }
}
