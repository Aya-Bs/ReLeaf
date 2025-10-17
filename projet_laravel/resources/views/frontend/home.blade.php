@extends('layouts.frontend')

@section('title', 'EcoEvents - Accueil')

@section('content')
<!-- Hero Section - Campagnes -->
<section class="campaigns-hero-section position-relative overflow-hidden">
    <div id="campaignsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            @forelse($featuredCampaigns as $index => $campaign)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }} position-relative">
                <!-- Image de fond -->
                <img src="{{ $campaign->image_url ? Storage::url($campaign->image_url) : 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80' }}"
                     alt="{{ $campaign->name }}"
                     class="d-block w-100 hero-carousel-image">
                
                <!-- Overlay sombre pour meilleure lisibilité -->
                <div class="carousel-overlay"></div>
                
                <!-- Contenu superposé sur l'image -->
                <div class="carousel-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 text-white">
                                <div class="hero-content">
                                    <h6 class="hero-subtitle mb-3">Campagne Écologique</h6>
                                    <h1 class="hero-title display-4 fw-bold mb-4">
                                        {{ $campaign->name }}
                                    </h1>
                                    <p class="hero-description lead mb-4">
                                        {{ Str::limit($campaign->description, 200) }}
                                    </p>
                                    
                                    <div class="campaign-meta mb-4">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="meta-item">
                                                    <i class="fas fa-calendar me-2"></i>
                                                    <strong>Début:</strong> 
                                                    {{ $campaign->start_date->format('d/m/Y') }}
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="meta-item">
                                                    <i class="fas fa-bullseye me-2"></i>
                                                    <strong>Objectif:</strong> 
                                                    {{ number_format($campaign->goal, 0, ',', ' ') }} TND
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="meta-item">
                                                    <i class="fas fa-leaf me-2"></i>
                                                    <strong>Impact:</strong> 
                                                    {{ $campaign->environmental_impact ?? 'Écologique' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hero-buttons d-flex gap-3 flex-wrap">
                                        <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-success btn-lg">
                                            <i class="fas fa-info-circle me-2"></i>Plus de détails
                                        </a>
                                        <a href="{{ route('campaigns.index') }}" class="btn btn-outline-light btn-lg">
                                            <i class="fas fa-list me-2"></i>Toutes les campagnes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="carousel-item active position-relative">
                <!-- Image de fond par défaut -->
                <img src="https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" 
                     alt="Campagnes écologiques"
                     class="d-block w-100 hero-carousel-image">
                
                <!-- Overlay sombre -->
                <div class="carousel-overlay"></div>
                
                <!-- Contenu superposé -->
                <div class="carousel-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 text-white">
                                <div class="hero-content">
                                    <h6 class="hero-subtitle mb-3">Prochaines Campagnes</h6>
                                    <h1 class="hero-title display-4 fw-bold mb-4">
                                        Rejoignez le Mouvement Écologique
                                    </h1>
                                    <p class="hero-description lead mb-4">
                                        Découvrez nos prochaines campagnes pour un avenir plus durable. 
                                        Ensemble, créons un impact positif sur notre environnement.
                                    </p>
                                    
                                    <div class="hero-buttons d-flex gap-3 flex-wrap">
                                        @guest
                                            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-light btn-lg">
                                                <i class="fas fa-list me-2"></i>Explorer les campagnes
                                            </a>
                                        @else
                                            @php $role = optional(auth()->user())->role; @endphp
                                            @if(in_array($role, ['admin', 'organizer']))
                                                <a href="{{ route('campaigns.create') }}" class="btn btn-success btn-lg">
                                                    <i class="fas fa-plus me-2"></i>Créer une campagne
                                                </a>
                                            @endif

                                            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-light btn-lg">
                                                <i class="fas fa-list me-2"></i>Explorer les campagnes
                                            </a>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Carousel Controls -->
        @if($featuredCampaigns->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#campaignsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#campaignsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>

        <!-- Carousel Indicators -->
        <div class="carousel-indicators-container">
            <div class="carousel-indicators">
                @foreach($featuredCampaigns as $index => $campaign)
                <button type="button" data-bs-target="#campaignsCarousel" data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
        </div>
        @endif
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
                                 class="rounded-circle ambassador-avatar fixed-avatar-size">
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
                                <div class="row" >
                                    @foreach($eventsChunk as $event)
                                    <div class="col-lg-3 col-md-6 mb-4" data-event-id="{{ $event->id }}">
                                        <div class="card event-card h-100 shadow-sm border-0">
                                            <!-- Event Image -->
                                        @if($event->images && is_array($event->images) && count($event->images) > 0 && !empty($event->images[0]))
    <img src="{{ asset('storage/' . $event->images[0]) }}" 
         class="card-img-top w-100" 
         style="height: 200px; object-fit: cover;" 
         onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
@endif
                                           
                                            <div class="card-body d-flex flex-column" >
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
                                                                {{ Str::limit($event->location->name, 10) }}
                                                            </small>
                                                        </div>
                                                    </div>
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
/* Hero Carousel Styles - CORRECTED */
.campaigns-hero-section {
    position: relative;
    overflow: hidden;
}

.hero-carousel-image {
    height: 420px; /* Fixed header image height (reduced for consistent header) */
    width: 100%;
    object-fit: cover !important;
    object-position: center center;
}

.carousel-item {
    position: relative;
}

.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.3) 0%,
        rgba(0, 0, 0, 0.5) 50%,
        rgba(0, 0, 0, 0.7) 100%
    );
    z-index: 1;
}

.carousel-content {
    z-index: 2;
}

.hero-content {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
}

.hero-subtitle {
    font-size: 1.1rem;
    color: #28a745;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero-description {
    font-size: 1.3rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.meta-item {
    background: rgba(255, 255, 255, 0.15);
    padding: 0.75rem 1rem;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
}

.meta-item strong {
    color: #28a745;
}

.hero-buttons .btn {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.hero-buttons .btn-success {
    background: #28a745;
    border-color: #28a745;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.hero-buttons .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.hero-buttons .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: 60px;
    height: 60px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    margin: 0 2rem;
    backdrop-filter: blur(10px);
    z-index: 3;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background: rgba(255, 255, 255, 0.3);
}

.carousel-indicators-container {
    position: absolute;
    bottom: 2rem;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    z-index: 3;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    border: 2px solid transparent;
    margin: 0 5px;
}

.carousel-indicators .active {
    background-color: #28a745;
    border-color: #28a745;
}

/* Event Cards - Rectangle avec contenu sur image */
.event-card-wrapper {
    height: 280px; /* Hauteur augmentée */
}

.event-card-rectangle {
    width: 100%;
    height: 100%;
    background: #fff;
}

.event-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

.event-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.2) 0%,
        rgba(0, 0, 0, 0.4) 30%,
        rgba(0, 0, 0, 0.7) 100%
    );
    z-index: 1;
}

.event-content {
    top: 0;
    left: 0;
    z-index: 2;
}

.event-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 3;
}

.event-badge .badge {
    font-size: 0.65rem;
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
}

.event-date {
    display: inline-block;
}

.event-title {
    font-size: 1.2rem;
    font-weight: 600;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    line-height: 1.3;
}

.event-description {
    font-size: 0.9rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
    line-height: 1.4;
    opacity: 0.9;
}

.event-meta {
    background: rgba(255, 255, 255, 0.15);
    padding: 0.5rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.event-meta small {
    font-size: 0.75rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
}

.event-actions .btn {
    border-radius: 20px;
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

/* Avatar et images fixes */
.fixed-avatar-size {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border: 3px solid #28a745;
}

/* Existing Styles */
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
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    font-size: 0.7rem;
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

.event-card-rectangle {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-card-rectangle:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3) !important;
}

.event-card-rectangle:hover .event-bg-image {
    transform: scale(1.05);
}

.event-bg-image {
    transition: transform 0.3s ease;
}

.cta-section {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

/* Responsive Design */
@media (max-width: 768px) {
    .hero-carousel-image {
        height: 500px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-description {
        font-size: 1.1rem;
    }
    
    .hero-buttons .btn {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        margin: 0 1rem;
        width: 50px;
        height: 50px;
    }
    
    .event-card-wrapper {
        height: 240px;
    }
    
    .fixed-avatar-size {
        width: 60px;
        height: 60px;
    }
    
    .event-content {
        padding: 1rem !important;
    }
    
    .event-title {
        font-size: 1rem;
    }
    
    .event-description {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .hero-carousel-image {
        height: 400px;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .event-card-wrapper {
        height: 220px;
    }
    
    .fixed-avatar-size {
        width: 50px;
        height: 50px;
    }
    
    .event-content {
        padding: 0.75rem !important;
    }
    
    .event-meta .row {
        font-size: 0.7rem;
    }
    
    .event-actions .btn {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
    
    .event-title {
        font-size: 0.9rem;
    }
    
    .event-description {
        font-size: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>

    // Make event cards clickable
document.addEventListener('DOMContentLoaded', function() {
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        card.style.cursor = 'pointer';
        
        card.addEventListener('click', function(e) {
            // Don't redirect if clicking on buttons or links inside the card
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || e.target.closest('button') || e.target.closest('a')) {
                return;
            }
            
            // Find the event ID from the card's data attribute or parent element
            const eventId = this.closest('[data-event-id]')?.getAttribute('data-event-id') || 
                           this.querySelector('a[href*="/events/"]')?.getAttribute('href')?.split('/').pop();
            
            if (eventId) {
                window.location.href = `/events/${eventId}`;
            }
        });
    });
});

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

    // Auto-rotate campaigns carousel every 5 seconds
    const campaignsCarousel = document.getElementById('campaignsCarousel');
    if (campaignsCarousel) {
        setInterval(() => {
            const nextButton = campaignsCarousel.querySelector('.carousel-control-next');
            if (nextButton) {
                nextButton.click();
            }
        }, 5000);
    }

    // Auto-rotate events carousel every 30 seconds
    const eventsCarousel = document.getElementById('eventsCarousel');
    if (eventsCarousel) {
        setInterval(() => {
            const nextButton = eventsCarousel.querySelector('.carousel-control-next');
            if (nextButton) {
                nextButton.click();
            }
        }, 30000);
    }

    // Add hover effects to event cards
    const eventCards = document.querySelectorAll('.event-card-rectangle');
    eventCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.3)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        });
    });

    // Ensure all images maintain fixed size
    const images = document.querySelectorAll('.event-bg-image, .fixed-avatar-size');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.objectFit = 'cover';
        });
    });
});
</script>
@endpush