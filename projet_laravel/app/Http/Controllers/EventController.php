<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements publics
     */
    public function index(Request $request)
    {
        $query = Event::where('status', 'published')
                     ->with(['user', 'reservations'])
                     ->orderBy('date', 'asc');

        // Filtres
        switch ($request->get('filter')) {
            case 'upcoming':
                $query->where('date', '>=', now());
                break;
            case 'available':
                $query->whereRaw('(SELECT COUNT(*) FROM reservations WHERE event_id = events.id AND status IN ("pending", "confirmed")) < max_participants');
                break;
            default:
                // Tous les événements
                break;
        }

        // Recherche textuelle
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        $events = $query->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Afficher un événement spécifique
     */
    public function show(Event $event)
    {
        $event->load(['user', 'reservations']);
        
        $reservedCount = $event->reservations()->whereIn('status', ['pending', 'confirmed'])->count();
        $availableSeats = $event->max_participants - $reservedCount;
        
        $userReservation = auth()->check() 
            ? $event->reservations()->where('user_id', auth()->id())->whereIn('status', ['pending', 'confirmed'])->first() 
            : null;

        return view('events.show', compact('event', 'availableSeats', 'userReservation'));
    }

    
}
