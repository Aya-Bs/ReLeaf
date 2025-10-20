@component('mail::message')
# Nouveau don pour votre événement

Un don a été effectué pour votre événement "{{ $donation->event->title }}".

@component('mail::panel')
**Montant :** {{ number_format($donation->amount, 2, ',', ' ') }} {{ $donation->currency }}
**Statut :** {{ ucfirst($donation->status) }}
**Type :** {{ $donation->type === 'sponsor' ? 'Don de sponsor' : 'Don individuel' }}
**Donateur :** {{ $donation->donor_name }} ({{ $donation->donor_email }})
@endcomponent

@component('mail::button', ['url' => route('events.donations', $donation->event)])
Voir les dons de l'événement
@endcomponent

Merci pour votre engagement,
{{ config('app.name') }}
@endcomponent