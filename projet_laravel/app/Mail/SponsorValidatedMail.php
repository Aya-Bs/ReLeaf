<?php

namespace App\Mail;

use App\Models\Sponsor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SponsorValidatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sponsor;

    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Sponsor $sponsor, string $password)
    {
        $this->sponsor = $sponsor;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Votre demande de sponsoring a été approuvée !')
            ->markdown('emails.sponsors.validated');
    }
}
