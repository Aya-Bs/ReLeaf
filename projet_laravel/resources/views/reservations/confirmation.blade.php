@extends('layouts.frontend')

@section('title', 'EcoEvents - Confirmation de réservation')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if($reservation->status === 'confirmed')
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Réservation confirmée !
                        </h3>
                    </div>
                @elseif($reservation->status === 'cancelled')
                    <div class="card-header bg-danger text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-times-circle me-2"></i>
                            Réservation annulée
                        </h3>
                    </div>
                @elseif($reservation->status === 'expired')
                    <div class="card-header bg-warning text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Réservation expirée
                        </h3>
                    </div>
                @else
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Réservation confirmée
                        </h3>
                    </div>
                @endif
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="countdown-timer" id="countdown">
                            <div class="time-remaining">
                                ✅ Réservation enregistrée
                            </div>
                            <small class="text-muted">Votre place est garantie</small>
                        </div>
                    </div>

                    <div class="reservation-details">
                        <h5>Détails de votre réservation</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Événement :</strong> {{ $reservation->event->title }}</p>
                                <p><strong>Date :</strong> {{ $reservation->event->date->format('d/m/Y à H:i') }}</p>
                                <p><strong>Place :</strong> {{ $reservation->seat_number }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nombre d'invités :</strong> {{ $reservation->num_guests }}</p>
                                <p><strong>Statut :</strong> 
                                    @switch($reservation->status)
                                        @case('pending')
                                            <span class="badge bg-success">Réservée</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-success">Confirmée</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Annulée</span>
                                            @break
                                        @case('expired')
                                            <span class="badge bg-secondary">Expirée</span>
                                            @break
                                        @default
                                            <span class="badge bg-success">Réservée</span>
                                    @endswitch
                                </p>
                                <p><strong>Réservé le :</strong> {{ $reservation->reserved_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($reservation->comments)
                            <p><strong>Commentaires :</strong> {{ $reservation->comments }}</p>
                        @endif
                    </div>

                    @if($reservation->status === 'confirmed')
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>Réservation confirmée !</h6>
                            <ul class="mb-0">
                                <li>✅ Votre réservation a été approuvée par notre équipe</li>
                                <li>📧 Un email de confirmation vous a été envoyé</li>
                                <li>📅 Rendez-vous le {{ $reservation->event->date->format('d/m/Y à H:i') }}</li>
                                <li>🎫 Votre place {{ $reservation->seat_number }} vous attend !</li>
                            </ul>
                        </div>
                    @elseif($reservation->status === 'cancelled')
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-times-circle me-2"></i>Réservation annulée</h6>
                            <p class="mb-0">Votre réservation a été annulée. Un email d'information vous a été envoyé.</p>
                        </div>
                    @elseif($reservation->status === 'expired')
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-clock me-2"></i>Réservation expirée</h6>
                            <p class="mb-0">Votre réservation a expiré. Vous pouvez faire une nouvelle réservation si des places sont disponibles.</p>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>Réservation enregistrée</h6>
                            <ul class="mb-0">
                                <li>✅ Votre réservation a été enregistrée avec succès</li>
                                <li>📧 Vous recevrez un email de confirmation sous peu</li>
                                <li>🎫 Votre place est garantie pour cet événement</li>
                                <li>📅 N'oubliez pas la date : {{ $reservation->event->date->format('d/m/Y à H:i') }}</li>
                            </ul>
                        </div>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('home') }}" class="btn btn-eco me-2">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                        
                        @if($reservation->status === 'confirmed')
                            <div class="btn-group">
                                <a href="{{ route('events.index') }}" class="btn btn-outline-eco">
                                    <i class="fas fa-calendar-alt me-2"></i>Voir d'autres événements
                                </a>
                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation confirmée ?')">
                                        <i class="fas fa-times me-2"></i>Annuler la réservation
                                    </button>
                                </form>
                            </div>
                        @elseif($reservation->status === 'expired')
                            <a href="{{ route('events.seats', $reservation->event) }}" class="btn btn-outline-eco">
                                <i class="fas fa-redo me-2"></i>Réserver à nouveau
                            </a>
                        @elseif($reservation->status === 'pending')
                            <div class="btn-group">
                                <a href="{{ route('events.index') }}" class="btn btn-outline-eco">
                                    <i class="fas fa-calendar-alt me-2"></i>Voir d'autres événements
                                </a>
                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        <i class="fas fa-times me-2"></i>Annuler la réservation
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations utiles -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>
                        Informations importantes
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            Vous recevrez un email de confirmation à l'adresse {{ auth()->user()->email }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock me-2 text-warning"></i>
                            Arrivez 15 minutes avant le début de l'événement
                        </li>
                        <li>
                            <i class="fas fa-certificate me-2 text-success"></i>
                            Un certificat de participation vous sera délivré après l'événement
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.btn-eco {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.btn-eco:hover {
    background-color: #234420;
    border-color: #234420;
    color: white;
}

.countdown-timer {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    display: inline-block;
}

.time-remaining {
    font-size: 1.5rem;
    font-weight: bold;
    color: #e67e22;
}

.reservation-details {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}
</style>
@endpush

@endsection
