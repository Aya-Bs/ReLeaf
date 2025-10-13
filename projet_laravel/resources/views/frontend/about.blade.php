@extends('layouts.frontend')

@section('title', 'À propos - EcoEvents')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-4">
                    À propos d'<span class="text-success">EcoEvents</span>
                </h1>
                <p class="lead">
                    Notre mission : rassembler, sensibiliser et agir pour un avenir durable
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Notre Mission</h2>
                <p class="lead text-muted">
                    EcoEvents est une plateforme collaborative dédiée à l'organisation d'événements
                    écologiques et au développement durable.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="mission-card text-center p-4 h-100">
                    <div class="mission-icon mb-3">
                        <i class="fas fa-handshake fa-4x text-success"></i>
                    </div>
                    <h4>Rassembler</h4>
                    <p class="text-muted">
                        Créer un point de rencontre pour tous les acteurs engagés
                        dans la protection de l'environnement.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="mission-card text-center p-4 h-100">
                    <div class="mission-icon mb-3">
                        <i class="fas fa-bullhorn fa-4x text-success"></i>
                    </div>
                    <h4>Sensibiliser</h4>
                    <p class="text-muted">
                        Promouvoir les bonnes pratiques environnementales
                        et sensibiliser le grand public.
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="mission-card text-center p-4 h-100">
                    <div class="mission-icon mb-3">
                        <i class="fas fa-leaf fa-4x text-success"></i>
                    </div>
                    <h4>Agir</h4>
                    <p class="text-muted">
                        Organiser des actions concrètes pour un impact
                        positif sur notre planète.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Nos Valeurs</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="value-card p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-start">
                        <div class="value-icon me-3">
                            <i class="fas fa-heart text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Engagement</h5>
                            <p class="text-muted mb-0">
                                Nous croyons en l'action collective et en l'engagement
                                de chacun pour un avenir meilleur.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="value-card p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-start">
                        <div class="value-icon me-3">
                            <i class="fas fa-balance-scale text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Équité</h5>
                            <p class="text-muted mb-0">
                                L'accès aux événements et aux ressources est ouvert
                                à tous, sans discrimination.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="value-card p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-start">
                        <div class="value-icon me-3">
                            <i class="fas fa-lightbulb text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Innovation</h5>
                            <p class="text-muted mb-0">
                                Nous encourageons les nouvelles idées et les approches
                                créatives pour résoudre les défis environnementaux.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="value-card p-4 bg-white rounded shadow-sm">
                    <div class="d-flex align-items-start">
                        <div class="value-icon me-3">
                            <i class="fas fa-shield-alt text-success fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Transparence</h5>
                            <p class="text-muted mb-0">
                                Nous communiquons ouvertement sur nos actions,
                                nos réussites et nos défis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">L'Équipe Fondatrice</h2>
                <p class="lead text-muted">
                    Les passionnés qui ont créé EcoEvents
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="team-card text-center p-4 bg-white rounded shadow-sm">
                    <div class="team-avatar mb-3">
                        <img src="https://ui-avatars.com/api/?name=Admin+EcoEvents&color=ffffff&background=2d5a27&size=150"
                             alt="Admin EcoEvents"
                             class="rounded-circle"
                             style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <h5 class="mb-2">Équipe EcoEvents</h5>
                    <p class="text-muted mb-3">Fondateurs</p>
                    <p class="text-sm text-muted">
                        Passionnés par l'environnement et le développement durable,
                        nous avons créé cette plateforme pour faciliter l'organisation
                        d'événements écologiques.
                    </p>
                    <div class="team-social">
                        <a href="#" class="text-success me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-success me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-success"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="impact-section py-5 bg-success text-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Notre Impact</h2>
                <p class="lead">
                    Ensemble, nous créons un changement positif pour notre planète
                </p>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="impact-stat">
                    <h3 class="h1 mb-2">{{ number_format($stats['total_users'] + 1) }}+</h3>
                    <p class="mb-0">Membres inscrits</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="impact-stat">
                    <h3 class="h1 mb-2">{{ number_format($stats['eco_ambassadors']) }}+</h3>
                    <p class="mb-0">Ambassadeurs actifs</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="impact-stat">
                    <h3 class="h1 mb-2">{{ number_format($stats['total_events']) }}+</h3>
                    <p class="mb-0">Événements organisés</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 mb-3">Rejoignez notre communauté</h2>
                <p class="lead mb-4">
                    Chaque action compte. Votre participation peut faire la différence.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('register') }}" class="btn btn-success btn-lg me-3 mb-3 mb-lg-0">
                    <i class="fas fa-user-plus me-2"></i>S'inscrire
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
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

.mission-card {
    transition: transform 0.3s ease;
    border: 1px solid rgba(40, 167, 69, 0.1);
}

.mission-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.mission-icon {
    opacity: 0.8;
}

.value-card {
    transition: transform 0.3s ease;
    border-left: 4px solid #28a745;
}

.value-card:hover {
    transform: translateX(5px);
}

.value-icon {
    min-width: 40px;
}

.team-card {
    transition: transform 0.3s ease;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.team-social a {
    transition: opacity 0.3s ease;
}

.team-social a:hover {
    opacity: 0.7;
}

.impact-stat {
    padding: 2rem 1rem;
}

.section-title {
    position: relative;
    display: inline-block;
    color: #2d5a27;
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

@media (max-width: 768px) {
    .hero-section {
        text-align: center;
        padding: 3rem 0;
    }

    .hero-section h1 {
        font-size: 2.5rem;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }

    .impact-stat {
        padding: 1.5rem 0.5rem;
    }

    .impact-stat h3 {
        font-size: 2rem;
    }
}
</style>
@endpush
