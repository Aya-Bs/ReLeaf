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
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('profile.show') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-user-circle me-2"></i>Mon Profil
                    </a>
                    @if(auth()->user()->isVolunteer())
                        <a href="{{ route('volunteers.show', auth()->user()->volunteer) }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-hands-helping me-2"></i>Mon Profil Volontaire
                        </a>
                        <a href="{{ route('assignments.index') }}" class="btn btn-info btn-lg">
                            <i class="fas fa-tasks me-2"></i>Mes Missions
                        </a>
                    @else
                        <a href="{{ route('volunteers.create') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-hands-helping me-2"></i>Devenir Volontaire
                        </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('backend.dashboard') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-cog me-2"></i>Administration
                        </a>
                    @endif
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

        <!-- Events Carousel -->
        <div class="row">
            <div class="col-12">
                <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="30000">
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators mb-4">
                        @for($i = 0; $i < ceil($recentEvents->count() / 4); $i++)
                            <button type="button" data-bs-target="#eventsCarousel" data-bs-slide-to="{{ $i }}" 
                                    class="{{ $i === 0 ? 'active' : '' }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}"></button>
                        @endfor
                    </div>

                    <!-- Carousel Inner -->
                    <div class="carousel-inner">
                        @foreach($recentEvents->chunk(4) as $chunkIndex => $eventsChunk)
                            <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                <div class="row">
                                    @foreach($eventsChunk as $event)
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card event-card h-100 shadow-sm border-0">
                                            <!-- Event Image -->
                                        @if($event->images && is_array($event->images) && count($event->images) > 0 && !empty($event->images[0]))
    <img src="{{ asset('storage/' . $event->images[0]) }}" 
         class="card-img-top w-100" 
         style="height: 200px; object-fit: cover;" 
         onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
@endif




                                            <!-- Event Status Badge -->
                                            <div class="event-badge">
                                                @if($event->isPublished())
                                                    <span class="badge bg-success">Publié</span>
                                                @elseif($event->isPending())
                                                    <span class="badge bg-warning">En attente</span>
                                                @endif
                                            </div>

                                            <div class="card-body d-flex flex-column">
                                                <!-- Event Date -->
                                                <div class="event-date mb-2">
                                                    <small class="text-success fw-bold">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $event->date->format('d/m/Y à H:i') }}
                                                    </small>
                                                </div>

                                                <!-- Event Title -->
                                                <h5 class="card-title text-dark">{{ $event->title }}</h5>

                                                <!-- Event Description -->
                                                <p class="card-text text-muted flex-grow-1">
                                                    {{ Str::limit($event->description, 80) }}
                                                </p>

                                                <!-- Event Meta Information -->
                                                <div class="event-meta mt-auto">
                                                    <div class="row text-center">
                                                        <div class="col-4">
                                                            <small class="text-primary">
                                                                <i class="fas fa-money-bill-wave me-1"></i>
                                                                {{ $event->price ? $event->price . 'TND' : '100TND' }}
                                                            </small>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="text-info">
                                                                <i class="fas fa-users me-1"></i>
                                                                {{ $event->max_participants ?? 0 }}
                                                            </small>
                                                        </div>
                                                        <div class="col-4">
                                                            <small class="text-muted">
                                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                                {{ Str::limit($event->location, 10) }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- View Event Button -->
                                                <div class="mt-3">
                                                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-success btn-sm w-100">
                                                        <i class="fas fa-eye me-1"></i>Voir l'événement
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#eventsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#eventsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-success rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- No Events Message -->
        @if($recentEvents->count() === 0)
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun événement récent. Créez le premier événement de notre communauté !
                </div>
            </div>
        </div>
        @endif

  
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

/* Event Cards Styling */
.event-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.event-image {
    transition: transform 0.3s ease;
}

.event-card:hover .event-image {
    transform: scale(1.05);
}

.event-image-placeholder {
    height: 200px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px 15px 0 0;
}

.event-badge {
    position: absolute;
    top: 15px;
    right: 15px;
}

.event-badge .badge {
    font-size: 0.7rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
}

.event-date {
    background: rgba(40, 167, 69, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    display: inline-block;
}

.event-meta {
    border-top: 1px solid #e9ecef;
    padding-top: 1rem;
    margin-top: 1rem;
}

.event-meta .col-4 {
    border-right: 1px solid #e9ecef;
}

.event-meta .col-4:last-child {
    border-right: none;
}

/* Carousel Styling */
.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #6c757d;
    border: 2px solid transparent;
}

.carousel-indicators .active {
    background-color: #28a745;
    border-color: #28a745;
}

.carousel-control-prev,
.carousel-control-next {
    width: 50px;
    opacity: 0.8;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    opacity: 1;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-size: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .event-card {
        margin-bottom: 2rem;
    }
    
    .event-meta .col-4 {
        margin-bottom: 0.5rem;
        border-right: none;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.5rem;
    }
    
    .event-meta .col-4:last-child {
        border-bottom: none;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
    }
}

/* Animation for card entrance */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.carousel-item .row > div {
    animation: fadeInUp 0.6s ease-out;
}

.carousel-item .row > div:nth-child(1) { animation-delay: 0.1s; }
.carousel-item .row > div:nth-child(2) { animation-delay: 0.2s; }
.carousel-item .row > div:nth-child(3) { animation-delay: 0.3s; }
.carousel-item .row > div:nth-child(4) { animation-delay: 0.4s; }

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('eventsCarousel');
    
    // Auto-rotate carousel every 30 seconds
    if (carousel) {
        setInterval(() => {
            const nextButton = carousel.querySelector('.carousel-control-next');
            if (nextButton) {
                nextButton.click();
            }
        }, 30000);
    }

    // Add hover effects
    const eventCards = document.querySelectorAll('.event-card');
    eventCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
            this.style.boxShadow = '0 12px 35px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
        });
    });
});
</script>
@endpush
