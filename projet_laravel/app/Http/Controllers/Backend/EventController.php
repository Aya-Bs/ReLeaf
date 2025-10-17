<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Sponsor;
use App\Models\SponsorEvent;
use App\Notifications\SponsorshipRequestNotification;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::where('user_id', auth()->id())
            ->with('location'); // Charger la relation location

        // Search by title
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('title', 'LIKE', "%{$searchTerm}%");
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Get all events based on filters
        $allEvents = $query->orderBy('created_at', 'desc')->get();

        // Pour chaque événement, charger la réservation de l'utilisateur connecté (s'il n'est pas l'organisateur)
        $allEvents->load(['reservations' => function ($query) {
            $query->where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'confirmed']);
        }]);

        // Separate pending events from others
        $pendingEvents = $allEvents->where('status', 'pending');
        $otherEvents = $allEvents->where('status', '!=', 'pending');


        return view('frontend.events.index', compact('otherEvents', 'pendingEvents'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->isOrganizer()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        // Get campaigns for dropdown
        if (Auth::user()->isAdmin()) {
            $campaigns = \App\Models\Campaign::all();
        } else {
            $campaigns = \App\Models\Campaign::where('organizer_id', Auth::id())->get();
        }

        return view('frontend.events.create', compact('campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isOrganizer()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'duration' => 'required|string',
            'max_participants' => 'nullable|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'location_id' => 'nullable|exists:locations,id',
            'sponsor_id' => 'nullable|exists:sponsors,id'

        ]);




        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'max_participants' => $request->max_participants,
            'duration' => $request->duration,
            'user_id' => Auth::id(),
            'status' => 'draft',
            'images' => [],
            'campaign_id' => $request->campaign_id,
            'location_id' => $request->location_id,
        ]);

        // Set location as reserved
        if ($request->location_id) {
            $location = \App\Models\Location::find($request->location_id);
            if ($location) {
                $location->reserved = true;
                $location->save();
            }
        }

        // If sponsor selected, create pending sponsorship request and notify sponsor
        if ($request->filled('sponsor_id')) {
            $sponsor = Sponsor::validated()->find($request->sponsor_id);
            if ($sponsor) {
                $se = SponsorEvent::firstOrCreate(
                    ['sponsor_id' => $sponsor->id, 'event_id' => $event->id],
                    ['status' => 'pending', 'amount' => 0]
                );
                // notify sponsor user if available
                if ($sponsor->user) {
                    $sponsor->user->notify(new SponsorshipRequestNotification($se));
                }
            }
        }

        if ($request->hasFile('images')) {
            $imagePaths = [];

            foreach ($request->file('images') as $image) {
                // Stockez l'image correctement
                $path = $image->store('events/' . $event->id, 'public');

                // Assurez-vous que le chemin utilise des slashs normaux
                $cleanPath = str_replace('\\', '/', $path);
                $imagePaths[] = $cleanPath;
            }

            $event->images = $imagePaths;
            $event->save();
        }
        return redirect()->route('events.my-events')->with('success', 'Événement créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Check if user is authorized to view this event
        if ($event->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('events.index')->with('error', 'Accès non autorisé.');
        }

        return view('frontend.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->canBeEdited()) {
            return redirect()->route('events.my-events')->with('error', 'Cet événement ne peut pas être modifié.');
        }

        // Get campaigns for dropdown
        if (Auth::user()->isAdmin()) {
            $campaigns = \App\Models\Campaign::all();
        } else {
            $campaigns = \App\Models\Campaign::where('organizer_id', Auth::id())->get();
        }

        return view('frontend.events.edit', compact('event', 'campaigns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->canBeEdited()) {
            return redirect()->route('events.my-events')->with('error', 'Cet événement ne peut pas être modifié.');
        }


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'location_id' => 'required|exists:locations,id',
            'max_participants' => 'nullable|integer|min:1',
            'duration' => 'required|string|max:50',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'campaign_id' => 'nullable|exists:campaigns,id'
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location_id' => $request->location_id,
            'max_participants' => $request->max_participants,
            'duration' => $request->duration,
            'campaign_id' => $request->campaign_id,
        ]);

        // Handle image upload
        if ($request->hasFile('images')) {
            $imagePaths = $event->images ?? [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('events/' . $event->id, 'public');
                $imagePaths[] = $path;
            }
            $event->update(['images' => $imagePaths]);
        }

        return redirect()->route('events.my-events')->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->canBeDeleted()) {
            return redirect()->route('events.my-events')->with('error', 'Cet événement ne peut pas être supprimé.');
        }

        // Delete associated images
        if ($event->images) {
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $event->delete();

        return redirect()->route('events.my-events')->with('success', 'Événement supprimé avec succès.');
    }

    /**
     * Remove an image from the event
     */
    public function removeImage(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->canBeEdited()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $imageToRemove = $request->input('image_path');

        if ($event->images && is_array($event->images)) {
            // Remove the image from the array
            $updatedImages = array_filter($event->images, function ($image) use ($imageToRemove) {
                return $image !== $imageToRemove;
            });

            // Delete the physical file from storage
            if (Storage::disk('public')->exists($imageToRemove)) {
                Storage::disk('public')->delete($imageToRemove);
            }

            // Update the event with the new images array
            $event->update(['images' => array_values($updatedImages)]);

            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès'
            ]);
        }

        return response()->json(['error' => 'Image non trouvée'], 404);
    }

    /**
     * Submit event for admin approval
     */
    public function submitForApproval(Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->isDraft()) {
            return redirect()->route('events.my-events')->with('error', 'Cet événement ne peut pas être soumis pour approbation.');
        }

        $event->submitForApproval();

        return redirect()->route('events.my-events')->with('success', 'Événement soumis pour approbation. Vous serez notifié lorsque l\'admin aura pris une décision.');
    }

    /**
     * Cancel an event
     */
    public function cancel(Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->isPublished()) {
            return redirect()->route('events.my-events')->with('error', 'Cet événement ne peut pas être annulé.');
        }

        $event->update(['status' => 'cancelled']);

        return redirect()->route('events.my-events')->with('success', 'Événement annulé avec succès.');
    }
}