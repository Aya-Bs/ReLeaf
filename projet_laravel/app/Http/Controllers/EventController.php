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
        
              $query = Event::whereIn('status', ['published', 'cancelled'])
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
        // Apply filters using model scopes when available
        switch ($request->get('filter')) {
            case 'upcoming':
                $query->upcoming();
                break;
            case 'available':
                // Use the scopeWithAvailableSeats implemented on the Event model
                $query->withAvailableSeats();
                break;
            default:
                // no additional filter - published and cancelled events only
                break;
        }

        // Recherche textuelle
       // Search by title/description/location
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('location', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('city', 'like', '%' . $request->search . '%');
                  });
        }

        // Filter by location
        if ($request->filled('location') && $request->location !== 'all') {
            $query->where('location_id', $request->location);
        }

        // Filter by max price
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by date (YYYY-MM-DD)
        if ($request->filled('date')) {
            $query->whereDate('date', '=', $request->date);
        }

        $events = $query->paginate(8);

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

        // Provide a list of published AND cancelled event dates for the calendar (YYYY-MM-DD)
        $allEventDates = Event::whereIn('status', ['published', 'cancelled'])
            ->pluck('date')
            ->map(function ($d) {
                return $d->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->toArray();

        return view('events.index', compact('events', 'allEventDates'));
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

        return view('frontend.events.show', compact('event', 'availableSeats', 'userReservation'));
    }
}