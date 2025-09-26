@extends('layouts.frontend')

@section('title', 'EcoEvents - Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <span class="text-success">EcoEvents</span>
                </h1>
                <p class="lead mb-4">
                    Rejoignez la communauté pour organiser et promouvoir des événements
                    autour de l'écologie et du développement durable.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('profile.show') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-user-circle me-2"></i>Mon Profil
                    </a>
                    <a href="{{ route('backend.dashboard') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-cog me-2"></i>Administration
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/eco-events-hero.png') }}"
                     alt="Événements écologiques"
                     class="img-fluid rounded shadow"
                     onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="stat-card p-4 rounded shadow-sm bg-white">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h3 class="h1 text-primary">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-muted">Membres actifs</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card p-4 rounded shadow-sm bg-white">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-leaf fa-3x text-success"></i>
                    </div>
                    <h3 class="h1 text-primary">{{ number_format($stats['eco_ambassadors']) }}</h3>
                    <p class="text-muted">Ambassadeurs Éco</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card p-4 rounded shadow-sm bg-white">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-calendar-alt fa-3x text-success"></i>
                    </div>
                    <h3 class="h3 text-primary">{{ number_format($stats['total_events']) }}</h3>
                    <p class="text-muted">Événements organisés</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Eco Ambassadors Section -->
<section class="eco-ambassadors-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <span class="text-success">Nos Ambassadeurs Écologiques</span>
                </h2>
                <p class="lead text-muted">
                    Découvrez les membres les plus engagés de notre communauté
                </p>
            </div>
        </div>

        <div class="row">
            @forelse($ecoAmbassadors as $ambassador)
            <div class="col-md-4 mb-4">
                <div class="card ambassador-card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar-container mb-3">
                            <img src="{{ $ambassador->avatar_url }}"
                                 alt="{{ $ambassador->full_name }}"
                                 class="rounded-circle ambassador-avatar"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="eco-badge">
                                <i class="fas fa-leaf text-white"></i>
                            </div>
                        </div>
                        <h5 class="card-title">{{ $ambassador->full_name }}</h5>
                        <p class="card-text text-muted">
                            {{ $ambassador->profile->bio ?? 'Ambassadeur écologique engagé' }}
                        </p>
                        <div class="ambassador-stats">
                            <small class="text-success">
                                <i class="fas fa-star me-1"></i>
                                {{ $ambassador->profile->eco_points ?? 0 }} points éco
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun ambassadeur écologique pour le moment. Soyez le premier !
                </div>
            </div>
            @endforelse
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('backend.users.index') }}" class="btn btn-outline-success">
                    <i class="fas fa-users me-2"></i>Voir tous les membres
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Recent Events Section -->
<section class="recent-events-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <span class="text-success">Événements Récents</span>
                </h2>
                <p class="lead text-muted">
                    Découvrez les dernières actions organisées par notre communauté
                </p>
            </div>
        </div>

        <div class="row">
            @forelse($recentEvents as $event)
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
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun événement récent. Créez le premier événement de notre communauté !
                </div>
                <a href="#" class="btn btn-success mt-3">
                    <i class="fas fa-plus me-2"></i>Créer un événement
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section py-5 bg-success text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 mb-3">Prêt à rejoindre le mouvement écologique ?</h2>
                <p class="lead mb-4">
                    Ensemble, créons un avenir plus durable. Votre participation compte !
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('profile.edit.extended') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-edit me-2"></i>Compléter mon profil
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
    min-height: 60vh;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.ambassador-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.ambassador-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.avatar-container {
    position: relative;
    display: inline-block;
}

.eco-badge {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: #28a745;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
}

.ambassador-avatar {
    border: 3px solid #28a745;
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

.event-card {
    border: none;
    transition: transform 0.3s ease;
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
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des statistiques au scroll
    const statNumbers = document.querySelectorAll('.stat-card h3');

    const animateStats = () => {
        statNumbers.forEach(stat => {
            const rect = stat.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                stat.classList.add('animate');
            }
        });
    };

    window.addEventListener('scroll', animateStats);
    animateStats(); // Animation initiale
});
</script>
@endpush
