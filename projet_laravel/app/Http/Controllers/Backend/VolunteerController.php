<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $volunteers = $query->latest()->paginate(15);

        $stats = [
            'total' => Volunteer::count(),
            'active' => Volunteer::where('status', 'active')->count(),
            'inactive' => Volunteer::where('status', 'inactive')->count(),
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
                'notes' => ($assignment->notes ?? '')."\nMission annulée - Profil volontaire supprimé par l'admin.",
            ]);
        }

        $volunteer->delete();

        return redirect()->route('backend.volunteers.index')
            ->with('success', 'Profil volontaire supprimé avec succès.');
    }
}
