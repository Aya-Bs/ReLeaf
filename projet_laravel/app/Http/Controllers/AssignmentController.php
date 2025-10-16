<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments.
     */
    public function index(Request $request)
    {
        $query = Assignment::with(['volunteer.user', 'assignable']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('assignable_type', $request->type);
        }

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->where('end_date', '<=', $request->end_date);
        }

        $assignments = $query->latest()->paginate(15);

        return view('assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(Request $request)
    {
        $assignableType = $request->get('type');
        $assignableId = $request->get('id');

        $assignable = null;
        if ($assignableType && $assignableId) {
            if ($assignableType === 'Event') {
                $assignable = Event::findOrFail($assignableId);
            } elseif ($assignableType === 'Campaign') {
                $assignable = Campaign::findOrFail($assignableId);
            }
        }

        $volunteers = Volunteer::active()->with('user')->get();

        return view('assignments.create', compact('assignable', 'volunteers'));
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'volunteer_id' => 'required|exists:volunteers,id',
            'assignable_type' => 'required|in:App\Models\Event,App\Models\Campaign',
            'assignable_id' => 'required|integer',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'hours_committed' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if volunteer is available
        $volunteer = Volunteer::findOrFail($validated['volunteer_id']);

        if (! $volunteer->isAvailableFor($validated['start_date'], $validated['end_date'])) {
            return redirect()->back()
                ->with('error', 'Le volontaire n\'est pas disponible pour cette période.');
        }

        if (! $volunteer->canTakeAssignment($validated['hours_committed'])) {
            return redirect()->back()
                ->with('error', 'Le volontaire a atteint sa limite d\'heures hebdomadaires.');
        }

        $validated['status'] = 'pending';
        $validated['assigned_at'] = now();

        Assignment::create($validated);

        return redirect()->route('assignments.index')
            ->with('success', 'Mission créée avec succès !');
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment)
    {
        $assignment->load(['volunteer.user', 'assignable', 'assignedBy']);

        return view('assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Assignment $assignment)
    {
        if (! $assignment->canBeEdited()) {
            return redirect()->route('assignments.show', $assignment)
                ->with('error', 'Cette mission ne peut pas être modifiée.');
        }

        $volunteers = Volunteer::active()->with('user')->get();

        return view('assignments.edit', compact('assignment', 'volunteers'));
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, Assignment $assignment)
    {
        if (! $assignment->canBeEdited()) {
            return redirect()->route('assignments.show', $assignment)
                ->with('error', 'Cette mission ne peut pas être modifiée.');
        }

        $validated = $request->validate([
            'volunteer_id' => 'required|exists:volunteers,id',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'hours_committed' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        $assignment->update($validated);

        return redirect()->route('assignments.show', $assignment)
            ->with('success', 'Mission mise à jour avec succès !');
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(Assignment $assignment)
    {
        if (! $assignment->canBeCancelled()) {
            return redirect()->route('assignments.show', $assignment)
                ->with('error', 'Cette mission ne peut pas être supprimée.');
        }

        $assignment->delete();

        return redirect()->route('assignments.index')
            ->with('success', 'Mission supprimée avec succès.');
    }

    /**
     * Approve an assignment.
     */
    public function approve(Assignment $assignment)
    {
        if ($assignment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cette mission ne peut pas être approuvée.');
        }

        $assignment->approve(Auth::user());

        return redirect()->back()
            ->with('success', 'Mission approuvée avec succès !');
    }

    /**
     * Reject an assignment.
     */
    public function reject(Request $request, Assignment $assignment)
    {
        if ($assignment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cette mission ne peut pas être rejetée.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $assignment->reject(Auth::user(), $validated['reason'] ?? null);

        return redirect()->back()
            ->with('success', 'Mission rejetée.');
    }

    /**
     * Complete an assignment.
     */
    public function complete(Request $request, Assignment $assignment)
    {
        if (! $assignment->canBeCompleted()) {
            return redirect()->back()
                ->with('error', 'Cette mission ne peut pas être terminée.');
        }

        $validated = $request->validate([
            'hours_worked' => 'required|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $assignment->complete(
            $validated['hours_worked'],
            $validated['rating'] ?? null,
            $validated['feedback'] ?? null
        );

        return redirect()->back()
            ->with('success', 'Mission terminée avec succès !');
    }

    /**
     * Cancel an assignment.
     */
    public function cancel(Request $request, Assignment $assignment)
    {
        if (! $assignment->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Cette mission ne peut pas être annulée.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $assignment->cancel($validated['reason'] ?? null);

        return redirect()->back()
            ->with('success', 'Mission annulée.');
    }

    /**
     * Update hours worked for an assignment.
     */
    public function updateHours(Request $request, Assignment $assignment)
    {
        if ($assignment->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Cette mission n\'est pas active.');
        }

        $validated = $request->validate([
            'hours_worked' => 'required|integer|min:0',
        ]);

        $assignment->updateHoursWorked($validated['hours_worked']);

        return redirect()->back()
            ->with('success', 'Heures travaillées mises à jour !');
    }

    /**
     * Get assignments for a specific event or campaign.
     */
    public function forAssignable(Request $request, $type, $id)
    {
        $assignable = null;
        if ($type === 'Event') {
            $assignable = Event::findOrFail($id);
        } elseif ($type === 'Campaign') {
            $assignable = Campaign::findOrFail($id);
        }

        $assignments = $assignable->assignments()
            ->with(['volunteer.user'])
            ->latest()
            ->paginate(15);

        return view('assignments.for-assignable', compact('assignments', 'assignable'));
    }
}
