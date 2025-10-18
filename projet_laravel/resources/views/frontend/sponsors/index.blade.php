@extends('layouts.frontend')

@section('title', 'Nos Sponsors - EcoEvents')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">
                    <span class="text-success">Nos Sponsors</span>
                </h1>
                <p class="lead mb-4">
                    Découvrez les entreprises qui nous accompagnent dans notre mission écologique.
                    Leur soutien rend possible l'organisation d'événements durables.
                </p>
                <a href="{{ route('sponsors.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-handshake me-2"></i>Devenir Sponsor
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Sponsors Grid Section -->
<section class="sponsors-section py-5">
    <div class="container">
        <div class="row">
            @forelse($sponsors as $sponsor)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card sponsor-card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="sponsor-logo mb-3">
                            <i class="fas fa-building fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">{{ $sponsor->company_name }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($sponsor->motivation, 100) }}
                        </p>
                        <div class="sponsor-meta mb-3">
                            <span class="badge bg-success me-2">
                                {{ $sponsor->formatted_sponsorship_type }}
                            </span>
                            @if($sponsor->city)
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $sponsor->city }}
                            </small>
                            @endif
                        </div>
                        <div class="sponsor-actions">
                            <a href="{{ route('sponsors.show', $sponsor) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                            @if($sponsor->website)
                            <a href="{{ $sponsor->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i>Site web
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-handshake fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted">Aucun sponsor pour le moment</h3>
                    <p class="lead text-muted mb-4">
                        Soyez le premier à nous rejoindre dans cette aventure écologique !
                    </p>
                    <a href="{{ route('sponsors.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus me-2"></i>Devenir le premier sponsor
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        @if($sponsors->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Pagination des sponsors">
                    {{ $sponsors->links() }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 mb-3">Votre entreprise partage nos valeurs ?</h2>
                <p class="lead mb-4">
                    Rejoignez notre communauté de sponsors engagés pour l'environnement.
                    Ensemble, créons un impact positif sur notre planète.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('sponsors.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-handshake me-2"></i>Devenir Sponsor
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <span class="text-success">Pourquoi nous rejoindre ?</span>
                </h2>
                <p class="lead text-muted">
                    Les avantages de devenir sponsor EcoEvents
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="benefit-item text-center">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-eye fa-3x text-success"></i>
                    </div>
                    <h5>Visibilité</h5>
                    <p class="text-muted">Augmentez votre visibilité auprès d'une communauté engagée</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="benefit-item text-center">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-leaf fa-3x text-success"></i>
                    </div>
                    <h5>Impact</h5>
                    <p class="text-muted">Contribuez directement à des actions pour l'environnement</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="benefit-item text-center">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h5>Réseau</h5>
                    <p class="text-muted">Rejoignez un réseau d'entreprises responsables</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="benefit-item text-center">
                    <div class="benefit-icon mb-3">
                        <i class="fas fa-chart-line fa-3x text-success"></i>
                    </div>
                    <h5>Croissance</h5>
                    <p class="text-muted">Développez votre image de marque éco-responsable</p>
                </div>
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

    .sponsor-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
    }

    .sponsor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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

    .benefit-item {
        transition: transform 0.3s ease;
    }

    .benefit-item:hover {
        transform: translateY(-5px);
    }

    .empty-state {
        padding: 4rem 2rem;
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

    .badge {
        font-size: 0.8rem;
    }
</style>
@endpush