@extends('layouts.frontend')

@section('title', 'Don Confirmé - EcoEvents')

@section('content')
<!-- Success Section -->
<section class="success-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="success-card">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h1 class="display-5 fw-bold text-success mb-4">
                        Don Confirmé !
                    </h1>
                    <p class="lead text-muted mb-4">
                        Merci pour votre générosité ! Votre don contribue directement à la réussite de l'événement.
                    </p>

                    <!-- Donation Details -->
                    <div class="donation-details bg-light p-4 rounded mb-4">
                        <h5 class="text-success mb-3">Détails du don</h5>
                        <div class="row text-start">
                            <div class="col-6">
                                <strong>Montant :</strong><br>
                                <span class="h5 text-success">{{ number_format($donation->amount, 2, ',', ' ') }} {{ $donation->currency }}</span>
                            </div>
                            <div class="col-6">
                                <strong>Type :</strong><br>
                                <span class="badge bg-info">{{ $donation->type === 'individual' ? 'Don individuel' : 'Don de sponsor' }}</span>
                            </div>
                        </div>
                        @if($donation->sponsor)
                        <div class="row text-start mt-2">
                            <div class="col-12">
                                <strong>Sponsor :</strong><br>
                                {{ $donation->sponsor->company_name }}
                            </div>
                        </div>
                        @endif
                        <div class="row text-start mt-2">
                            <div class="col-12">
                                <strong>Méthode de paiement :</strong><br>
                                {{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}
                            </div>
                        </div>
                        <div class="row text-start mt-2">
                            <div class="col-12">
                                <strong>Date :</strong><br>
                                {{ $donation->donated_at ? $donation->donated_at->format('d/m/Y à H:i') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Prochaines étapes :</strong><br>
                        • Votre don sera traité par notre équipe<br>
                        • Vous recevrez un email de confirmation<br>
                        • L'organisateur sera notifié de votre contribution
                    </div>

                    <div class="d-flex gap-3 justify-content-center mt-4">
                        <a href="{{ route('donations.create', $donation->event) }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i>Faire un autre don
                        </a>
                        @php
                        $redirectRoute = 'home';
                        if(auth()->check() && auth()->user()->role === 'sponsor') {
                        // Adjust this route name if your sponsor dashboard route differs
                        $redirectRoute = 'sponsor.dashboard';
                        }
                        @endphp
                        <a href="{{ route($redirectRoute) }}" class="btn btn-success">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Event Info Section -->
<section class="event-info-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title">
                    <span class="text-success">Événement soutenu</span>
                </h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">{{ $donation->event->title }}</h4>
                                <p class="text-muted mb-2">{{ Str::limit($donation->event->description, 150) }}</p>
                                <div class="event-meta">
                                    <small class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $donation->event->date ? $donation->event->date->format('d/m/Y à H:i') : 'Date à définir' }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $donation->event->location ?? 'Lieu à définir' }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="funding-progress">
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ min(($donation->event->total_funding / 1000) * 100, 100) }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ number_format($donation->event->total_funding, 0, ',', ' ') }} € collectés
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="impact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <span class="text-success">Votre contribution compte</span>
                </h2>
                <p class="lead text-muted">
                    Merci de faire partie de la solution pour un avenir plus durable
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="impact-item text-center">
                    <div class="impact-icon mb-3">
                        <i class="fas fa-heart fa-3x text-success"></i>
                    </div>
                    <h5>Générosité</h5>
                    <p class="text-muted">Votre don témoigne de votre engagement pour l'environnement</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="impact-item text-center">
                    <div class="impact-icon mb-3">
                        <i class="fas fa-leaf fa-3x text-success"></i>
                    </div>
                    <h5>Impact</h5>
                    <p class="text-muted">Vous contribuez directement à des actions concrètes</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="impact-item text-center">
                    <div class="impact-icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h5>Communauté</h5>
                    <p class="text-muted">Vous rejoignez une communauté de donateurs engagés</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section py-5 bg-success text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 mb-3">Continuez à faire la différence !</h2>
                <p class="lead mb-4">
                    Découvrez d'autres événements écologiques à soutenir ou créez votre propre événement.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('sponsors.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-handshake me-2"></i>Découvrir nos sponsors
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .success-section {
        min-height: 60vh;
        display: flex;
        align-items: center;
    }

    .success-card {
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .success-icon {
        animation: bounceIn 1s ease-out;
    }

    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }

        50% {
            transform: scale(1.05);
        }

        70% {
            transform: scale(0.9);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
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

    .impact-item {
        transition: transform 0.3s ease;
    }

    .impact-item:hover {
        transform: translateY(-5px);
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

    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endpush