<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\Certification;
use App\Models\SeatLock;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationCancelled;

class ReservationController extends Controller
{
    /**
     * Afficher les places disponibles pour un événement
     */
    public function showSeats(Event $event)
    {
        // Charger la relation user pour l'organisateur
        $event->load('user');
        
        // Vérifier si l'utilisateur a déjà une réservation pour cet événement
        $userReservation = Reservation::where('user_id', auth()->id())
                                    ->where('event_id', $event->id)
                                    ->whereIn('status', ['pending', 'confirmed'])
                                    ->first();

        // Récupérer toutes les réservations actives
        $reservedSeats = Reservation::where('event_id', $event->id)
                                  ->whereIn('status', ['pending', 'confirmed'])
                                  ->pluck('seat_number')
                                  ->toArray();

        // Récupérer les places bloquées temporairement
        $lockedSeats = SeatLock::getLockedSeats($event->id);
        
        // Fusionner les places réservées et bloquées
        $unavailableSeats = array_merge($reservedSeats, $lockedSeats);

        // Générer la liste des places
        $availableSeats = $this->generateSeatMap($event, $unavailableSeats);
        
        // Récupérer le verrou de l'utilisateur actuel s'il existe
        $userLock = SeatLock::getUserLock(auth()->id());

        return view('reservations.select-seats', [
            'event' => $event,
            'availableSeats' => $availableSeats,
            'reservedSeats' => $reservedSeats,
            'lockedSeats' => $lockedSeats,
            'userReservation' => $userReservation,
            'userLock' => $userLock
        ]);
    }

    /**
     * Créer une réservation temporaire
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'seat_number' => 'required|string',
            'num_guests' => 'integer|min:1|max:10',
            'comments' => 'nullable|string|max:500'
        ]);

        // Vérifier si l'utilisateur a déjà une réservation
        $existingReservation = Reservation::where('user_id', auth()->id())
                                        ->where('event_id', $event->id)
                                        ->whereIn('status', ['pending', 'confirmed'])
                                        ->first();

        if ($existingReservation) {
            return redirect()->back()->with('error', 'Vous avez déjà une réservation pour cet événement.');
        }

        // Vérifier si la place est disponible
        $seatTaken = Reservation::where('event_id', $event->id)
                               ->where('seat_number', $request->seat_number)
                               ->whereIn('status', ['pending', 'confirmed'])
                               ->exists();

        if ($seatTaken) {
            return redirect()->back()->with('error', 'Cette place n\'est plus disponible.');
        }

        // Créer la réservation avec blocage temporaire
        $reservation = Reservation::createWithTimeout([
            'user_name' => auth()->user()->name,
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'seat_number' => $request->seat_number,
            'num_guests' => $request->num_guests ?? 1,
            'comments' => $request->comments,
            'seat_details' => [
                'type' => 'standard',
                'section' => substr($request->seat_number, 0, 1),
                'row' => substr($request->seat_number, 1)
            ]
        ]);

        // Libérer le verrou temporaire
        SeatLock::releaseSeat($event->id, $request->seat_number);

        return redirect()->route('reservations.confirmation', $reservation)
                        ->with('success', 'Votre place a été réservée avec succès.');
    }

    /**
     * Page de confirmation de réservation
     */
    public function confirmation(Reservation $reservation)
    {
        // Vérifier que c'est bien l'utilisateur propriétaire
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        return view('reservations.confirmation', compact('reservation'));
    }


    /**
     * Dashboard admin - Liste des réservations (inclut les listes d'attente)
     */
    public function adminIndex(Request $request)
    {
        // Récupérer les réservations
        $reservationQuery = Reservation::with(['user', 'event', 'confirmedBy'])
                           ->selectRaw('*, "reservation" as type, created_at as display_date')
                           ->orderBy('created_at', 'desc');

        // Filtres pour les réservations
        if ($request->status && $request->status !== 'waiting') {
            $reservationQuery->where('status', $request->status);
        }

        if ($request->event_id) {
            $reservationQuery->where('event_id', $request->event_id);
        }

        // Récupérer les listes d'attente
        $waitingQuery = \App\Models\WaitingList::with(['user', 'event', 'promotedBy'])
                           ->selectRaw('*, "waiting" as type, joined_at as display_date')
                           ->orderBy('joined_at', 'desc');

        // Filtres pour les listes d'attente
        if ($request->status === 'waiting') {
            $waitingQuery->where('status', 'waiting');
        } elseif ($request->status) {
            $waitingQuery->where('status', $request->status);
        }

        if ($request->event_id) {
            $waitingQuery->where('event_id', $request->event_id);
        }

        // Union des deux requêtes
        $allItems = $reservationQuery->get()->concat($waitingQuery->get());
        
        // Trier par date d'affichage
        $allItems = $allItems->sortByDesc('display_date');
        
        // Pagination manuelle
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $allItems->slice($offset, $perPage)->values();
        
        // Créer un paginateur manuel
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $allItems->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
        $paginator->withQueryString();

        $events = Event::all();

        return view('admin.reservations.index', [
            'reservations' => $paginator,
            'events' => $events
        ]);
    }

    /**
     * Confirmer une réservation (admin)
     */
    public function confirm(Reservation $reservation)
    {
        if (!$reservation->canBeConfirmed()) {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas être confirmée.');
        }

        $reservation->confirm(auth()->user());

        // Envoyer l'email de confirmation (sans certificat pour l'instant)
        Mail::to($reservation->user->email)->send(new ReservationConfirmed($reservation));

        return redirect()->back()->with('success', 'Réservation confirmée et email envoyé.');
    }

    /**
     * Rejeter une réservation (admin)
     */
    public function reject(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);

        // Envoyer l'email d'annulation
        Mail::to($reservation->user->email)->send(new ReservationCancelled($reservation));

        return redirect()->back()->with('success', 'Réservation rejetée et email envoyé.');
    }

    /**
     * Supprimer définitivement une réservation (admin)
     */
    public function destroy(Reservation $reservation)
    {
        // Supprimer aussi le certificat associé s'il existe
        if ($reservation->certification) {
            $reservation->certification->delete();
        }

        // Supprimer la réservation
        $reservation->delete();

        return redirect()->back()->with('success', 'Réservation supprimée définitivement de la base de données.');
    }

    /**
     * Annuler une réservation (utilisateur)
     */
    public function cancel(Reservation $reservation)
    {
        // Vérifier que c'est bien l'utilisateur propriétaire
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Vous ne pouvez pas annuler cette réservation.');
        }

        // Vérifier que la réservation peut être annulée
        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas être annulée.');
        }

        // Annuler la réservation
        $reservation->update(['status' => 'cancelled']);

        // Envoyer l'email d'annulation
        Mail::to($reservation->user->email)->send(new ReservationCancelled($reservation));

        // Tenter de promouvoir le premier utilisateur de la liste d'attente
        $promotedUser = \App\Http\Controllers\WaitingListController::promoteFirstFromWaitingList($reservation->event_id);

        if ($promotedUser) {
            return redirect()->route('home')->with('success', 'Réservation annulée. Le premier utilisateur de la liste d\'attente a été automatiquement promu.');
        }

        return redirect()->route('home')->with('success', 'Réservation annulée avec succès.');
    }


    /**
     * Générer le plan des places
     */
    private function generateSeatMap(Event $event, array $reservedSeats): array
    {
        // Configuration fixe : 3 places seulement pour tester la liste d'attente
        $seats = [];
        $seatNumbers = ['A1', 'A2', 'A3'];
        
        foreach ($seatNumbers as $seatNumber) {
            $seats[] = [
                'number' => $seatNumber,
                'available' => !in_array($seatNumber, $reservedSeats),
                'row' => 'A',
                'position' => substr($seatNumber, 1)
            ];
        }
        
        return $seats;
    }

    /**
     * AJAX: Verrouiller temporairement une place
     */
    public function lockSeat(Request $request): JsonResponse
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_number' => 'required|string'
        ]);

        $eventId = $request->event_id;
        $seatNumber = $request->seat_number;
        $userId = auth()->id();

        // Vérifier si la place est déjà réservée
        $isReserved = Reservation::where('event_id', $eventId)
                                ->where('seat_number', $seatNumber)
                                ->whereIn('status', ['pending', 'confirmed'])
                                ->exists();

        if ($isReserved) {
            return response()->json([
                'success' => false,
                'message' => 'Cette place est déjà réservée.'
            ], 400);
        }

        // Vérifier si la place est déjà bloquée par un autre utilisateur
        $existingLock = SeatLock::where('event_id', $eventId)
                              ->where('seat_number', $seatNumber)
                              ->where('user_id', '!=', $userId)
                              ->where('expires_at', '>', now())
                              ->first();

        if ($existingLock) {
            return response()->json([
                'success' => false,
                'message' => 'Cette place est temporairement bloquée par un autre utilisateur.',
                'remaining_seconds' => $existingLock->getRemainingSeconds()
            ], 400);
        }

        // Créer le verrou
        $lock = SeatLock::lockSeat($eventId, $seatNumber, $userId);

        return response()->json([
            'success' => true,
            'message' => 'Place bloquée pendant 5 minutes.',
            'expires_at' => $lock->expires_at->toISOString(),
            'remaining_seconds' => $lock->getRemainingSeconds()
        ]);
    }

    /**
     * AJAX: Libérer une place bloquée
     */
    public function releaseSeat(Request $request): JsonResponse
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_number' => 'required|string'
        ]);

        $eventId = $request->event_id;
        $seatNumber = $request->seat_number;
        $userId = auth()->id();

        // Supprimer le verrou de l'utilisateur
        SeatLock::where('event_id', $eventId)
               ->where('seat_number', $seatNumber)
               ->where('user_id', $userId)
               ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Place libérée.'
        ]);
    }

    /**
     * AJAX: Récupérer le statut des places pour un événement
     */
    public function getSeatsStatus(Event $event): JsonResponse
    {
        // Nettoyer les verrous expirés
        SeatLock::cleanExpiredLocks();

        // Récupérer les places réservées
        $reservedSeats = Reservation::where('event_id', $event->id)
                                  ->whereIn('status', ['pending', 'confirmed'])
                                  ->pluck('seat_number')
                                  ->toArray();

        // Récupérer les places bloquées
        $lockedSeats = SeatLock::getLockedSeats($event->id);

        // Récupérer le verrou de l'utilisateur actuel
        $userLock = SeatLock::getUserLock(auth()->id());

        return response()->json([
            'reserved_seats' => $reservedSeats,
            'locked_seats' => $lockedSeats,
            'user_lock' => $userLock ? [
                'seat_number' => $userLock->seat_number,
                'expires_at' => $userLock->expires_at->toISOString(),
                'remaining_seconds' => $userLock->getRemainingSeconds()
            ] : null
        ]);
    }
}
