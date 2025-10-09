<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\Certification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $certification;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre réservation - EcoEvents')
                    ->view('emails.reservation-confirmed')
                    ->with([
                        'userName' => $this->reservation->user_name,
                        'eventTitle' => $this->reservation->event->title,
                        'eventDate' => $this->reservation->event->date,
                        'seatNumber' => $this->reservation->seat_number,
                        'numGuests' => $this->reservation->num_guests,
                        'hasComments' => !empty($this->reservation->comments),
                        'comments' => $this->reservation->comments,
                        'certification' => null
                    ]);
    }
}
