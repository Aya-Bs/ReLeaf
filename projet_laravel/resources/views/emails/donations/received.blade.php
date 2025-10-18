@component('mail::message')
# Merci pour votre don !

Nous avons bien reçu votre don.

@component('mail::panel')
**Montant :** {{ number_format($donation->amount, 2, ',', ' ') }} {{ $donation->currency }}
**Type :** {{ $donation->type === 'sponsor' ? 'Don de sponsor' : 'Don individuel' }}
@if($donation->sponsor)
**Sponsor :** {{ $donation->sponsor->company_name }}
@endif
**Événement :** {{ $donation->event->title }}
@endcomponent

Vous recevrez une notification lorsque le don sera confirmé.

Merci encore pour votre soutien,
{{ config('app.name') }}
@endcomponent