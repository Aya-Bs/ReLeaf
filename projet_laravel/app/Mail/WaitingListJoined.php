<?php

namespace App\Mail;

use App\Models\WaitingList;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaitingListJoined extends Mailable
{
    use Queueable, SerializesModels;

    public WaitingList $waitingList;

    /**
     * Create a new message instance.
     */
    public function __construct(WaitingList $waitingList)
    {
        $this->waitingList = $waitingList;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vous avez rejoint la liste d\'attente - '.$this->waitingList->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.waiting-list-joined',
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
