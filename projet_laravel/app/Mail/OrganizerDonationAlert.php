<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrganizerDonationAlert extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function build(): self
    {
        $status = $this->donation->status;
        $subject = $status === 'confirmed'
            ? 'Don confirmé pour votre événement'
            : 'Nouveau don reçu (en attente)';

        return $this->subject($subject)
            ->markdown('emails.donations.organizer_alert');
    }
}
