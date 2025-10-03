<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\Certification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateGranted extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $certification;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, Certification $certification)
    {
        $this->reservation = $reservation;
        $this->certification = $certification;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Votre certificat de participation est prêt !',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate-granted',
            with: [
                'reservation' => $this->reservation,
                'certification' => $this->certification,
            ]
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
