<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Annulation de votre réservation - EcoEvents')
                    ->view('emails.reservation-cancelled')
                    ->with([
                        'userName' => $this->reservation->user_name,
                        'eventTitle' => $this->reservation->event->title,
                        'eventDate' => $this->reservation->event->date,
                        'seatNumber' => $this->reservation->seat_number,
                        'reason' => 'Décision administrative'
                    ]);
    }
}
