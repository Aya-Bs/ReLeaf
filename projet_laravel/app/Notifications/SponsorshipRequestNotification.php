<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\SponsorEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SponsorshipRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public SponsorEvent $sponsorEvent)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $event = $this->sponsorEvent->event;
        return (new MailMessage)
            ->subject('Nouvelle demande de sponsoring')
            ->greeting('Bonjour,')
            ->line("Vous avez reçu une demande de sponsoring pour l'événement: " . ($event?->title ?? 'Événement'))
            ->line('Date: ' . optional($event?->date)->format('d/m/Y H:i'))
            ->action('Voir la demande', url(route('sponsor.requests.index')))
            ->line('Vous pouvez accepter ou refuser cette demande depuis votre tableau de bord.');
    }
}
