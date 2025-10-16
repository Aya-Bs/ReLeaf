@extends('layouts.frontend')

@section('title', 'EcoEvents - Bienvenue')

@section('content')
<!-- Hero Section (Campaigns) -->
<section class="campaigns-hero-section position-relative overflow-hidden">
    <div id="campaignsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            @forelse($featuredCampaigns as $index => $campaign)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }} position-relative">
                <img src="{{ $campaign->image_url ? asset('storage/' . $campaign->image_url) : 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&w=2000&q=80' }}"
                    alt="{{ $campaign->name }}" class="d-block w-100 hero-carousel-image">
                <div class="carousel-overlay"></div>
                <div class="carousel-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 text-white">
                                <div class="hero-content">
                                    <h6 class="hero-subtitle mb-3">Campagne Écologique</h6>
                                    <h1 class="hero-title display-4 fw-bold mb-4">{{ $campaign->name }}</h1>
                                    <p class="hero-description lead mb-4">{{ Str::limit($campaign->description, 200) }}</p>
                                    <div class="hero-buttons d-flex gap-3 flex-wrap">
                                        <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-success btn-lg">
                                            <i class="fas fa-info-circle me-2"></i>Plus de détails
                                        </a>
                                        <a href="{{ route('sponsors.create') }}" class="btn btn-outline-light btn-lg">
                                            <i class="fas fa-handshake me-2"></i>Devenir sponsor
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
                <img src="https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?auto=format&fit=crop&w=2000&q=80" alt="Campagnes écologiques" class="d-block w-100 hero-carousel-image">
                <div class="carousel-overlay"></div>
                <div class="carousel-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 text-white">
                                <div class="hero-content">
                                    <h6 class="hero-subtitle mb-3">EcoEvents</h6>
                                    <h1 class="hero-title display-4 fw-bold mb-4">Agissez pour la planète</h1>
                                    <p class="hero-description lead mb-4">Rejoignez nos campagnes et événements pour un avenir durable.</p>
                                    <div class="hero-buttons d-flex gap-3 flex-wrap">
                                        <a href="{{ route('campaigns.index') }}" class="btn btn-outline-light btn-lg">
                                            <i class="fas fa-list me-2"></i>Explorer les campagnes
                                        </a>
                                        <a href="{{ route('sponsors.create') }}" class="btn btn-success btn-lg">
                                            <i class="fas fa-handshake me-2"></i>Devenir sponsor
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        @if($featuredCampaigns->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#campaignsCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#campaignsCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
        @endif
    </div>
</section>

<!-- Statistics -->
<section class="stats-section py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="stat-card p-4 rounded shadow-sm bg-white">
                    <div class="stat-icon mb-3"><i class="fas fa-users fa-3x text-success"></i></div>
                    <h3 class="h1 text-primary">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-muted">Membres actifs</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card p-4 rounded shadow-sm bg-white">
                    <div class="stat-icon mb-3"><i class="fas fa-leaf fa-3x text-success"></i></div>
                    <h3 class="h1 text-primary">{{ number_format($stats['eco_ambassadors']) }}</h3>
                    <p class="text-muted">Ambassadeurs Éco</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card p-4 rounded shadow-sm bg-white">
                    <div class="stat-icon mb-3"><i class="fas fa-calendar-alt fa-3x text-success"></i></div>
                    <h3 class="h3 text-primary">{{ number_format($stats['total_events']) }}</h3>
                    <p class="text-muted">Événements organisés</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Events Section (inlined) -->
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
            <div class="col-12">
                <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="30000">
                    <div class="carousel-indicators mb-4">
                        @for($i = 0; $i < ceil($recentEvents->count() / 4); $i++)
                            <button type="button" data-bs-target="#eventsCarousel" data-bs-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}" aria-current="{{ $i === 0 ? 'true' : 'false' }}"></button>
                            @endfor
                    </div>

                    <div class="carousel-inner">
                        @foreach($recentEvents->chunk(4) as $chunkIndex => $eventsChunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="row">
                                @foreach($eventsChunk as $event)
                                <div class="col-lg-3 col-md-6 mb-4" data-event-id="{{ $event->id }}">
                                    <div class="card event-card h-100 shadow-sm border-0">
                                        @if($event->images && is_array($event->images) && count($event->images) > 0 && !empty($event->images[0]))
                                        <img src="{{ asset('storage/' . $event->images[0]) }}"
                                            class="card-img-top w-100"
                                            style="height: 200px; object-fit: cover;"
                                            onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <div class="event-date mb-2">
                                                <small class="text-success fw-bold">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $event->date->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                            <h5 class="card-title text-dark">{{ $event->title }}</h5>
                                            <p class="card-text text-muted flex-grow-1">
                                                {{ Str::limit($event->description, 80) }}
                                            </p>
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

        @if($recentEvents->count() === 0)
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun événement récent. Rejoignez la communauté et participez au premier !
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- CTA sponsor -->
<section class="cta-section py-5 bg-success text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="h1 mb-3">Vous êtes une entreprise engagée ?</h2>
                <p class="lead mb-4">Soutenez nos événements et campagnes en devenant sponsor.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('sponsors.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-handshake me-2"></i>Devenir sponsor
                </a>
            </div>
        </div>
    </div>
</section>
@endsection