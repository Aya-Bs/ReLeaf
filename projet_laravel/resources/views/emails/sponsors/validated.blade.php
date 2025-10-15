@component('mail::message')
# Félicitations, {{ $sponsor->company_name }} !

Votre demande de sponsoring pour EcoEvents a été approuvée. Nous sommes ravis de vous compter parmi nos partenaires.

Un compte a été créé pour vous sur notre plateforme. Voici vos identifiants de connexion :

- **Email :** {{ $sponsor->contact_email }}
- **Mot de passe temporaire :** {{ $password }}

Nous vous recommandons de changer votre mot de passe après votre première connexion.

@component('mail::button', ['url' => route('login')])
Se connecter
@endcomponent

Merci pour votre soutien,
L'équipe EcoEvents
@endcomponent