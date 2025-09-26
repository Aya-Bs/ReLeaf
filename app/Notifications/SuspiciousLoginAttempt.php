<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginAttempt extends Notification
{
    use Queueable;

    protected $loginDetails;

    public function __construct(array $loginDetails)
    {
        $this->loginDetails = $loginDetails;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Connexion suspecte détectée')
            ->greeting('Bonjour ' . $notifiable->first_name)
            ->line('Nous avons détecté une connexion inhabituelle à votre compte.')
            ->line('Détails de la connexion :')
            ->line('IP : ' . $this->loginDetails['ip_address'])
            ->line('Localisation : ' . $this->loginDetails['location'])
            ->line('Navigateur : ' . $this->loginDetails['user_agent'])
            ->line('Date : ' . $this->loginDetails['time'])
            ->action('Vérifier l\'activité du compte', url('/profile/security'))
            ->line('Si ce n\'était pas vous, nous vous recommandons de changer immédiatement votre mot de passe.');
    }
}
