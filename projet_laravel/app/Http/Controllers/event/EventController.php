<?php

namespace App\Http\Controllers\event;

use App\Http\Controllers\Controller; 
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isOrganizer()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $events = Event::byOrganizer($user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $pendingEvents = $events->where('status', 'pending');
        $otherEvents = $events->where('status', '!=', 'pending');

        return view('events.index', compact('pendingEvents', 'otherEvents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->isOrganizer()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isOrganizer()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'duration' => 'required|string|max:50',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'duration' => $request->duration,
            'user_id' => Auth::id(),
            'status' => 'draft', 
            'images' => [], 

        ]);

        // Handle image upload
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('events/' . $event->id, 'public');
                $imagePaths[] = $path;
            }
            $event->update(['images' => $imagePaths]);
        }

        return redirect()->route('events.index')->with('success', 'Événement créé avec succès. Vous pouvez maintenant le soumettre pour approbation.');
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

    return view('events.show', compact('event'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->canBeEdited()) {
            return redirect()->route('events.index')->with('error', 'Cet événement ne peut pas être modifié.');
        }

        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->canBeEdited()) {
            return redirect()->route('events.index')->with('error', 'Cet événement ne peut pas être modifié.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'duration' => 'required|string|max:50',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'duration' => $request->duration,
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

        return redirect()->route('events.index')->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->canBeDeleted()) {
            return redirect()->route('events.index')->with('error', 'Cet événement ne peut pas être supprimé.');
        }

        // Delete associated images
        if ($event->images) {
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Événement supprimé avec succès.');
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
        $updatedImages = array_filter($event->images, function($image) use ($imageToRemove) {
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
            return redirect()->route('events.index')->with('error', 'Cet événement ne peut pas être soumis pour approbation.');
        }

        $event->submitForApproval();

        return redirect()->route('events.index')->with('success', 'Événement soumis pour approbation. Vous serez notifié lorsque l\'admin aura pris une décision.');
    }

    /**
     * Cancel an event
     */
    public function cancel(Event $event)
    {
        if ($event->user_id !== Auth::id() || !$event->isPublished()) {
            return redirect()->route('events.index')->with('error', 'Cet événement ne peut pas être annulé.');
        }

        $event->update(['status' => 'cancelled']);

        return redirect()->route('events.index')->with('success', 'Événement annulé avec succès.');
    }
}