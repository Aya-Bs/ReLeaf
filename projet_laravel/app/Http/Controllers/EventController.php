<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements selon le rôle de l'utilisateur
     */
    public function index(Request $request)
    {
        $query = Event::where('status', 'published')
                     ->with(['user', 'reservations', 'location'])
                     ->orderBy('date', 'asc');

        // Logique selon le rôle de l'utilisateur
        if (auth()->check()) {
            if (auth()->user()->role === 'organizer') {
                // Les organisateurs voient seulement leurs propres événements
                $query->where('user_id', auth()->id());
            }
            // Les utilisateurs avec rôle 'user' voient tous les événements (pas de filtre supplémentaire)
        }

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
                  ->orWhereHas('location', function($locationQuery) use ($search) {
                      $locationQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('city', 'LIKE', "%{$search}%");
                  });
            });
        }

        $events = $query->paginate(12);

        // Pour chaque événement, calculer les informations nécessaires pour la liste d'attente
        $events->getCollection()->transform(function ($event) {
            $reservedCount = $event->reservations()->where('status', 'confirmed')->count();
            $availableSeats = $event->max_participants - $reservedCount;
            $isFull = $availableSeats <= 0;
            
            $userReservation = auth()->check() 
                ? $event->reservations()->where('user_id', auth()->id())->whereIn('status', ['pending', 'confirmed'])->first() 
                : null;
                
            $userInWaitingList = auth()->check() 
                ? $event->waitingList()->where('user_id', auth()->id())->exists() 
                : false;

            $event->availableSeats = $availableSeats;
            $event->isFull = $isFull;
            $event->userReservation = $userReservation;
            $event->userInWaitingList = $userInWaitingList;

            return $event;
        });

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
