<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Volunteer;
use App\Models\Event;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments.
     */
    public function index(Request $request): View
    {
        $query = Assignment::with(['volunteer.user', 'assignable']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('assignable_type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('volunteer.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $assignments = $query->latest()->paginate(15);

        $stats = [
            'total' => Assignment::count(),
            'pending' => Assignment::where('status', 'pending')->count(),
            'approved' => Assignment::where('status', 'approved')->count(),
            'completed' => Assignment::where('status', 'completed')->count(),
            'cancelled' => Assignment::where('status', 'cancelled')->count(),
        ];

        return view('backend.assignments.index', compact('assignments', 'stats'));
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment): View
    {
        $assignment->load(['volunteer.user', 'assignable']);
        return view('backend.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(Assignment $assignment): View
    {
        $volunteers = Volunteer::with('user')->get();
        return view('backend.assignments.edit', compact('assignment', 'volunteers'));
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'volunteer_id' => 'required|exists:volunteers,id',
            'role' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'hours_committed' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        $assignment->update($validated);

        return redirect()->route('backend.assignments.show', $assignment)
            ->with('success', 'Mission mise à jour avec succès !');
    }

    /**
     * Approve an assignment.
     */
    public function approve(Assignment $assignment)
    {
        $assignment->update([
            'status' => 'approved',
            'assigned_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Mission approuvée avec succès !');
    }

    /**
     * Reject an assignment.
     */
    public function reject(Assignment $assignment)
    {
        $assignment->update([
            'status' => 'rejected',
        ]);

        return redirect()->back()
            ->with('success', 'Mission rejetée avec succès !');
    }

    /**
     * Remove the specified assignment.
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('backend.assignments.index')
            ->with('success', 'Mission supprimée avec succès.');
    }
}
