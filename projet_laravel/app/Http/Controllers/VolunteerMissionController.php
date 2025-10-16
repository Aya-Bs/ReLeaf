<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Campaign;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VolunteerMissionController extends Controller
{
    /**
     * Afficher les événements et campagnes disponibles pour postuler.
     */
    public function availableMissions(Request $request)
    {
        try {
            $user = auth()->user();
            $volunteer = $user->isVolunteer() ? $user->volunteer : null;

            // Événements à venir (tous les statuts pour le test)
            $eventsQuery = Event::where('date', '>', now());

            // Campagnes actives (tous les statuts pour le test)
            $campaignsQuery = Campaign::where('end_date', '>', now());

            // Filtres
            if ($request->filled('search')) {
                $search = $request->search;
                $eventsQuery->where('title', 'like', "%{$search}%");
                $campaignsQuery->where('title', 'like', "%{$search}%");
            }

            if ($request->filled('type')) {
                if ($request->type === 'events') {
                    $campaignsQuery->whereRaw('1 = 0'); // Exclure les campagnes
                } elseif ($request->type === 'campaigns') {
                    $eventsQuery->whereRaw('1 = 0'); // Exclure les événements
                }
            }

            $events = $eventsQuery->latest('date')->paginate(10, ['*'], 'events_page');
            $campaigns = $campaignsQuery->latest('start_date')->paginate(10, ['*'], 'campaigns_page');

            // Missions déjà postulées par ce volontaire
            $appliedAssignments = [];
            if ($volunteer) {
                $appliedAssignments = $volunteer->assignments()
                    ->with('assignable')
                    ->get()
                    ->pluck('assignable_id', 'assignable_type')
                    ->toArray();
            }

            return view('volunteers.available-missions', compact(
                'events',
                'campaigns',
                'appliedAssignments'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * S'inscrire directement à un événement.
     */
    public function applyForMission(Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isVolunteer()) {
            return redirect()->back()
                ->with('error', 'Vous devez être volontaire pour vous inscrire.');
        }

        $validated = $request->validate([
            'assignable_type' => 'required|in:App\Models\Event,App\Models\Campaign',
            'assignable_id' => 'required|integer',
            'role' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Valeurs par défaut si non fournies
        $validated['role'] = $validated['role'] ?? 'Participant';
        $validated['notes'] = $validated['notes'] ?? '';

        // Vérifier si le volontaire est déjà inscrit
        $existingAssignment = Assignment::where('volunteer_id', $user->volunteer->id)
            ->where('assignable_type', $validated['assignable_type'])
            ->where('assignable_id', $validated['assignable_id'])
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'Vous êtes déjà inscrit à cette mission.');
        }

        // Récupérer l'événement ou la campagne
        if ($validated['assignable_type'] === 'App\Models\Event') {
            $event = Event::findOrFail($validated['assignable_id']);
            $startDate = $event->date;
            $endDate = $event->date->addHours($event->duration ?? 2); // Durée par défaut de 2h
        } else {
            $campaign = Campaign::findOrFail($validated['assignable_id']);
            $startDate = $campaign->start_date;
            $endDate = $campaign->end_date;
        }

        // Créer l'inscription directe
        Assignment::create([
            'volunteer_id' => $user->volunteer->id,
            'assignable_type' => $validated['assignable_type'],
            'assignable_id' => $validated['assignable_id'],
            'role' => $validated['role'] ?? 'Participant',
            'status' => 'approved', // Inscription directe approuvée
            'assigned_at' => now(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'hours_committed' => 1, // Par défaut
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()
            ->with('success', 'Vous êtes maintenant inscrit à cette mission !');
    }

    /**
     * Afficher les détails d'un événement/campagne pour postuler.
     */
    public function showMissionDetails(Request $request): View
    {
        $type = $request->get('type');
        $id = $request->get('id');

        if ($type === 'App\Models\Event') {
            $mission = Event::findOrFail($id);
        } elseif ($type === 'App\Models\Campaign') {
            $mission = Campaign::findOrFail($id);
        } else {
            abort(404);
        }

        $user = auth()->user();
        $hasApplied = false;

        if ($user->isVolunteer()) {
            $hasApplied = Assignment::where('volunteer_id', $user->volunteer->id)
                ->where('assignable_type', $type)
                ->where('assignable_id', $id)
                ->exists();
        }

        return view('volunteers.mission-details', compact('mission', 'type', 'hasApplied'));
    }
}
