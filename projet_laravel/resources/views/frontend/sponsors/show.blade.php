@extends('layouts.frontend')

@section('title', $sponsor->company_name . ' - EcoEvents')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <div class="sponsor-logo mb-4">
                    <i class="fas fa-building fa-4x text-success"></i>
                </div>
                <h1 class="display-4 fw-bold mb-4">
                    {{ $sponsor->company_name }}
                </h1>
                <p class="lead mb-4">
                    {{ $sponsor->formatted_sponsorship_type }}
                </p>
                @if($sponsor->website)
                    <a href="{{ $sponsor->website }}" target="_blank" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-external-link-alt me-2"></i>Visiter le site web
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Sponsor Details Section -->
<section class="sponsor-details-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Motivation -->
                        <div class="mb-5">
                            <h3 class="text-success mb-3">
                                <i class="fas fa-heart me-2"></i>Motivation du partenariat
                            </h3>
                            <p class="lead">{{ $sponsor->motivation }}</p>
                        </div>

                        <!-- Additional Info -->
                        @if($sponsor->additional_info)
                        <div class="mb-5">
                            <h3 class="text-success mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informations supplémentaires
                            </h3>
                            <p>{{ $sponsor->additional_info }}</p>
                        </div>
                        @endif

                        <!-- Contact Information -->
                        <div class="mb-5">
                            <h3 class="text-success mb-3">
                                <i class="fas fa-address-book me-2"></i>Informations de contact
                            </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="contact-item mb-3">
                                        <i class="fas fa-envelope text-success me-2"></i>
                                        <strong>Email :</strong> {{ $sponsor->contact_email }}
                                    </div>
                                    @if($sponsor->contact_phone)
                                    <div class="contact-item mb-3">
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <strong>Téléphone :</strong> {{ $sponsor->contact_phone }}
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($sponsor->address)
                                    <div class="contact-item mb-3">
                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                        <strong>Adresse :</strong><br>
                                        {{ $sponsor->address }}<br>
                                        @if($sponsor->city)
                                            {{ $sponsor->city }}
                                        @endif
                                        @if($sponsor->country)
                                            , {{ $sponsor->country }}
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sponsorship Type -->
                        <div class="mb-5">
                            <h3 class="text-success mb-3">
                                <i class="fas fa-gift me-2"></i>Type de sponsoring
                            </h3>
                            <div class="sponsorship-type-badge">
                                <span class="badge bg-success fs-6 p-3">
                                    <i class="fas fa-{{ $sponsor->sponsorship_type === 'argent' ? 'money-bill' : ($sponsor->sponsorship_type === 'materiel' ? 'box' : 'tools') }} me-2"></i>
                                    {{ $sponsor->formatted_sponsorship_type }}
                                </span>
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="text-center">
                            <a href="{{ route('sponsors.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux sponsors
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Events Section -->
@if($sponsor->events->count() > 0)
<section class="related-events-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <span class="text-success">Événements soutenus</span>
                </h2>
                <p class="lead text-muted">
                    Découvrez les événements que {{ $sponsor->company_name }} soutient
                </p>
            </div>
        </div>

        <div class="row">
            @foreach($sponsor->events as $event)
            <div class="col-md-4 mb-4">
                <div class="card event-card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="event-date mb-3">
                            <small class="text-success fw-bold">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $event->date ?? 'Date à définir' }}
                            </small>
                        </div>
                        <h5 class="card-title">{{ $event->title ?? 'Événement écologique' }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($event->description ?? 'Description de l\'événement...', 100) }}
                        </p>
                        <div class="event-meta">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $event->location ?? 'Lieu à définir' }}
                            </small>
                        </div>
                        @if($event->pivot->amount)
                        <div class="sponsorship-amount mt-2">
                            <small class="text-success fw-bold">
                                <i class="fas fa-euro-sign me-1"></i>
                                Montant : {{ number_format($event->pivot->amount, 0, ',', ' ') }} €
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action Section -->
<section class="cta-section py-5 bg-success text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 mb-3">Vous aussi, devenez sponsor !</h2>
                <p class="lead mb-4">
                    Rejoignez {{ $sponsor->company_name }} et d'autres entreprises engagées pour l'environnement.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('sponsors.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-handshake me-2"></i>Devenir Sponsor
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
    min-height: 50vh;
}

.section-title {
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: #28a745;
    border-radius: 2px;
}

.contact-item {
    padding: 0.5rem 0;
}

.sponsorship-type-badge {
    text-align: center;
}

.event-card {
    transition: transform 0.3s ease;
    border: none;
}

.event-card:hover {
    transform: translateY(-2px);
}

.event-date {
    background: rgba(40, 167, 69, 0.1);
    padding: 8px 16px;
    border-radius: 20px;
    display: inline-block;
}

.cta-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    transition: transform 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
}
</style>
@endpush

