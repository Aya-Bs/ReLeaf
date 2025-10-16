<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    /**
     * Display a listing of volunteers.
     */
    public function index(Request $request)
    {
        $query = Volunteer::with('user');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by experience level
        if ($request->has('experience_level') && $request->experience_level !== '') {
            $query->where('experience_level', $request->experience_level);
        }

        // Filter by region
        if ($request->has('region') && $request->region !== '') {
            $query->byRegion($request->region);
        }

        // Filter by skill
        if ($request->has('skill') && $request->skill !== '') {
            $query->bySkill($request->skill);
        }

        // Search by name
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        $volunteers = $query->paginate(15);

        return view('volunteers.index', compact('volunteers'));
    }

    /**
     * Show the form for creating a new volunteer profile.
     */
    public function create()
    {
        // Check if user already has a volunteer profile
        if (Auth::user()->isVolunteer()) {
            return redirect()->route('volunteers.show', Auth::user()->volunteer)
                ->with('info', 'Vous avez déjà un profil volontaire.');
        }

        return view('volunteers.create');
    }

    /**
     * Store a newly created volunteer profile.
     */
    public function store(Request $request)
    {
        // Check if user already has a volunteer profile
        if (Auth::user()->isVolunteer()) {
            return redirect()->route('volunteers.show', Auth::user()->volunteer)
                ->with('error', 'Vous avez déjà un profil volontaire.');
        }

        $validated = $request->validate([
            'skills' => 'required|array|min:1',
            'skills.*' => 'string|max:255',
            'availability' => 'required|array|min:1',
            'experience_level' => 'required|in:beginner,intermediate,advanced',
            'preferred_regions' => 'required|array|min:1',
            'preferred_regions.*' => 'string|max:255',
            'max_hours_per_week' => 'required|integer|min:1|max:168',
            'emergency_contact' => 'required|string|max:255',
            'medical_conditions' => 'nullable|string|max:1000',
            'bio' => 'required|string|max:1000',
            'motivation' => 'required|string|max:1000',
            'previous_volunteer_experience' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'active';

        $volunteer = Volunteer::create($validated);

        return redirect()->route('volunteers.show', $volunteer)
            ->with('success', 'Profil volontaire créé avec succès !');
    }

    /**
     * Display the specified volunteer.
     */
    public function show(Volunteer $volunteer)
    {
        $volunteer->load('user', 'assignments.assignable');

        $recentAssignments = $volunteer->assignments()
            ->with('assignable')
            ->latest()
            ->limit(5)
            ->get();

        $statistics = [
            'total_assignments' => $volunteer->assignments()->count(),
            'completed_assignments' => $volunteer->assignments()->where('status', 'completed')->count(),
            'total_hours_worked' => $volunteer->total_hours_worked,
            'average_rating' => $volunteer->rating,
        ];

        return view('volunteers.show', compact('volunteer', 'recentAssignments', 'statistics'));
    }

    /**
     * Show the form for editing the specified volunteer.
     */
    public function edit(Volunteer $volunteer)
    {
        // Check if user can edit this volunteer profile
        if (Auth::id() !== $volunteer->user_id && ! Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }

        return view('volunteers.edit', compact('volunteer'));
    }

    /**
     * Update the specified volunteer.
     */
    public function update(Request $request, Volunteer $volunteer)
    {
        // Check if user can edit this volunteer profile
        if (Auth::id() !== $volunteer->user_id && ! Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce profil.');
        }

        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
            'bio' => 'required|string|max:1000',
            'motivation' => 'required|string|max:1000',
            'max_hours_per_week' => 'required|integer|min:1|max:168',
        ]);

        $volunteer->update($validated);

        return redirect()->route('volunteers.show', $volunteer)
            ->with('success', 'Profil volontaire mis à jour avec succès !');
    }

    /**
     * Remove the specified volunteer.
     */
    public function destroy(Volunteer $volunteer)
    {
        // Check if user can delete this volunteer profile
        if (Auth::id() !== $volunteer->user_id && ! Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce profil.');
        }

        // Cancel all active assignments before deleting
        $activeAssignments = $volunteer->assignments()->whereIn('status', ['pending', 'approved'])->get();
        foreach ($activeAssignments as $assignment) {
            $assignment->update([
                'status' => 'cancelled',
                'notes' => ($assignment->notes ?? '')."\nMission annulée - Profil volontaire supprimé.",
            ]);
        }

        $volunteer->delete();

        return redirect()->route('volunteers.index')
            ->with('success', 'Profil volontaire supprimé avec succès.');
    }

    /**
     * Apply for an assignment.
     */
    public function apply(Request $request)
    {
        $validated = $request->validate([
            'assignable_type' => 'required|in:App\Models\Event,App\Models\Campaign',
            'assignable_id' => 'required|integer',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'hours_committed' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        $volunteer = Auth::user()->volunteer;

        if (! $volunteer) {
            return redirect()->route('volunteers.create')
                ->with('error', 'Vous devez d\'abord créer un profil volontaire.');
        }

        // Check if volunteer is available
        if (! $volunteer->isAvailableFor($validated['start_date'], $validated['end_date'])) {
            return redirect()->back()
                ->with('error', 'Vous n\'êtes pas disponible pour cette période.');
        }

        // Check if volunteer can take this assignment
        if (! $volunteer->canTakeAssignment($validated['hours_committed'])) {
            return redirect()->back()
                ->with('error', 'Vous avez atteint votre limite d\'heures hebdomadaires.');
        }

        $validated['volunteer_id'] = $volunteer->id;
        $validated['status'] = 'pending';

        Assignment::create($validated);

        return redirect()->back()
            ->with('success', 'Candidature soumise avec succès !');
    }

    /**
     * Get available assignments for a volunteer.
     */
    public function availableAssignments(Request $request)
    {
        $volunteer = Auth::user()->volunteer;

        if (! $volunteer) {
            return redirect()->route('volunteers.create')
                ->with('error', 'Vous devez d\'abord créer un profil volontaire.');
        }

        $query = Assignment::with('assignable')
            ->where('status', 'pending')
            ->where('start_date', '>=', now());

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('assignable_type', $request->type);
        }

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Filter by region
        if ($request->has('region') && $request->region !== '') {
            // This would need to be implemented based on your region logic
        }

        $assignments = $query->paginate(15);

        return view('volunteers.assignments', compact('assignments', 'volunteer'));
    }
}
