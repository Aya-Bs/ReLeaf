<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\WaitingList;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaitingListPromoted extends Mailable
{
    use Queueable, SerializesModels;

    public WaitingList $waitingList;

    public Reservation $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct(WaitingList $waitingList, Reservation $reservation)
    {
        $this->waitingList = $waitingList;
        $this->reservation = $reservation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Félicitations ! Vous avez été promu de la liste d\'attente',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.waiting-list-promoted',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
