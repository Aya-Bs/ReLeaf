<?php

namespace App\Http\Controllers;

use App\Mail\WaitingListJoined;
use App\Mail\WaitingListPromoted;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\WaitingList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WaitingListController extends Controller
{
    /**
     * Ajouter un utilisateur à la liste d'attente
     */
    public function join(Request $request, Event $event): RedirectResponse
    {
        // Vérifier que l'événement est complet
        if (! $event->isFull()) {
            return redirect()->back()->with('error', 'Cet événement a encore des places disponibles.');
        }

        // Vérifier que l'utilisateur n'a pas déjà une réservation
        $existingReservation = Reservation::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existingReservation) {
            return redirect()->back()->with('error', 'Vous avez déjà une réservation pour cet événement.');
        }

        try {
            $waitingList = WaitingList::addToWaitingList(auth()->id(), $event->id);

            // Envoyer un email de confirmation
            Mail::to(auth()->user()->email)->send(new WaitingListJoined($waitingList));

            return redirect()->back()->with('success', 'Vous avez été ajouté à la liste d\'attente avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Retirer un utilisateur de la liste d'attente
     */
    public function leave(Request $request, Event $event): RedirectResponse
    {
        try {
            $removed = WaitingList::removeFromWaitingList(auth()->id(), $event->id);

            if ($removed) {
                return redirect()->back()->with('success', 'Vous avez été retiré de la liste d\'attente.');
            } else {
                return redirect()->back()->with('error', 'Vous n\'êtes pas dans la liste d\'attente pour cet événement.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la liste d\'attente.');
        }
    }

    /**
     * Dashboard admin pour gérer les listes d'attente
     */
    public function adminIndex(Request $request)
    {
        $query = WaitingList::with(['user', 'event', 'promotedBy']);

        // Filtres
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $waitingLists = $query->orderBy('created_at', 'desc')->paginate(15);
        $events = Event::published()->get();

        return view('admin.waiting-lists.index', compact('waitingLists', 'events'));
    }

    /**
     * Promouvoir manuellement un utilisateur de la liste d'attente
     */
    public function promote(Request $request, WaitingList $waitingList): RedirectResponse
    {
        if ($waitingList->status !== 'waiting') {
            return redirect()->back()->with('error', 'Cet utilisateur ne peut pas être promu.');
        }

        // Vérifier qu'il y a encore des places disponibles
        if ($waitingList->event->isFull()) {
            return redirect()->back()->with('error', 'L\'événement est complet.');
        }

        try {
            // Créer une réservation pour l'utilisateur
            $reservation = Reservation::createWithTimeout([
                'user_name' => $waitingList->user_name,
                'user_id' => $waitingList->user_id,
                'event_id' => $waitingList->event_id,
                'seat_number' => $this->getNextAvailableSeat($waitingList->event),
                'num_guests' => 1,
                'comments' => 'Promotion automatique depuis la liste d\'attente',
                'seat_details' => [
                    'type' => 'standard',
                    'section' => 'A',
                    'row' => '1',
                ],
            ]);

            // Confirmer automatiquement la réservation
            $reservation->confirm(auth()->user());

            // Marquer comme promu dans la liste d'attente
            $waitingList->update([
                'status' => 'promoted',
                'promoted_at' => now(),
                'promoted_by' => auth()->id(),
            ]);

            // Envoyer un email de confirmation
            Mail::to($waitingList->user_email)->send(new WaitingListPromoted($waitingList, $reservation));

            return redirect()->back()->with('success', 'L\'utilisateur a été promu et une réservation confirmée a été créée.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la promotion: '.$e->getMessage());
        }
    }

    /**
     * Obtenir la prochaine place disponible pour un événement
     */
    private function getNextAvailableSeat(Event $event): string
    {
        $reservedSeats = Reservation::where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('seat_number')
            ->toArray();

        $allSeats = ['A1', 'A2', 'A3']; // Nos 3 places fixes

        foreach ($allSeats as $seat) {
            if (! in_array($seat, $reservedSeats)) {
                return $seat;
            }
        }

        throw new \Exception('Aucune place disponible trouvée.');
    }

    /**
     * Méthode statique pour promouvoir automatiquement lors d'une annulation
     */
    public static function promoteFirstFromWaitingList(int $eventId): ?WaitingList
    {
        $waitingList = WaitingList::promoteFirst($eventId);

        if (! $waitingList) {
            return null;
        }

        try {
            // Créer une réservation confirmée automatiquement
            $reservation = Reservation::createWithTimeout([
                'user_name' => $waitingList->user_name,
                'user_id' => $waitingList->user_id,
                'event_id' => $waitingList->event_id,
                'seat_number' => (new self)->getNextAvailableSeat($waitingList->event),
                'num_guests' => 1,
                'comments' => 'Promotion automatique depuis la liste d\'attente',
                'seat_details' => [
                    'type' => 'standard',
                    'section' => 'A',
                    'row' => '1',
                ],
            ]);

            // Confirmer automatiquement
            $reservation->confirm(\App\Models\User::where('role', 'admin')->first());

            // Envoyer un email
            Mail::to($waitingList->user_email)->send(new WaitingListPromoted($waitingList, $reservation));

            return $waitingList;
        } catch (\Exception $e) {
            // En cas d'erreur, remettre l'utilisateur en liste d'attente
            $waitingList->update(['status' => 'waiting']);

            return null;
        }
    }
}
