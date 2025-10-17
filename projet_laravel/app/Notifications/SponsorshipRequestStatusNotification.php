<?php

namespace App\Notifications;

use App\Models\SponsorEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SponsorshipRequestStatusNotification extends Notification
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
        $status = $this->sponsorEvent->status;
        $statusText = $status === 'active' ? 'acceptée' : ($status === 'cancelled' ? 'refusée' : $status);
        return (new MailMessage)
            ->subject('Mise à jour de la demande de sponsoring')
            ->greeting('Bonjour,')
            ->line("Votre demande de sponsoring pour l'événement " . ($event?->title ?? 'Événement') . " a été " . $statusText . '.')
            ->action('Voir mes événements', url(route('events.my-events')))
            ->line('Merci d’utiliser EcoEvents.');
    }
}
