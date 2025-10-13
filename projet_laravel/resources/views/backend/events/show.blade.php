@extends('backend.layouts.app')

@section('title', 'Gestion des Événements')
@section('page-title', 'Gestion des Événements')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.events.index') }}">Événements</a></li>
    <li class="breadcrumb-item active font-bold">{{ Str::limit($event->title, 24) }}</li>
@endsection

@section('content')
<style>
    .event-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .event-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .event-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .event-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        box-shadow: 0 2px 6px rgba(45, 90, 39, 0.18);
    }
    
    .event-title-text h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #2d5a27;
    }
    
    .event-title-text p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    
    .info-section {
        margin-bottom: 1.5rem;
    }
    
    .info-section:last-child {
        margin-bottom: 0;
    }
    
    .info-section h4 {
        color: #2d5a27;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-section h4 i {
        color: #2d5a27;
    }
    
    .info-content {
        color: #495057;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 14px;
        font-weight: 500;
        color: #2d5a27;
    }
    
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .badge-modern.badge-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 1px solid #b1dfbb;
    }
    
    .badge-modern.badge-secondary {
        background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
        color: #383d41;
        border: 1px solid #c6c8ca;
    }
    
    .badge-modern.badge-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 1px solid #f1b0b7;
    }
    
    .badge-modern.badge-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .badge-modern.badge-light {
        background: linear-gradient(135deg, #fdfdfe 0%, #f8f9fa 100%);
        color: #495057;
        border: 1px solid #e9ecef;
    }
    
    /* Image Carousel Styles */
    .image-carousel-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    
    .carousel-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        background: #f8f9fa;
    }
    
    .carousel-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        display: block;
        transition: opacity 0.5s ease-in-out;
    }
    
    .carousel-placeholder {
        width: 100%;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #adb5bd;
    }
    
    .carousel-placeholder i {
        font-size: 4rem;
        opacity: 0.5;
    }
    
    .carousel-nav {
        position: absolute;
        top: 55%;
        transform: translateY(-50%);
        background: transparent;
        color: rgba(45, 90, 39, 0.8);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .carousel-nav:hover {
        background: transparent;
        transform: translateY(-50%) scale(1.1);
    }
    
    .carousel-nav.prev {
        left: -18px;
    }
    
    .carousel-nav.next {
        right: -18px;
    }
    
    .carousel-nav:disabled {
        background: transparent;
        cursor: not-allowed;
        transform: translateY(-50%) scale(1);
        color : transparent;
    }
    
    .carousel-indicators {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 8px;
        z-index: 10;
    }
    
    .carousel-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(45, 90, 39, 0.8);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .carousel-indicator.active {
        background: rgba(45, 90, 39, 0.8);
        transform: scale(1.2);
    }
    
    .carousel-counter {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(45, 90, 39, 0.8);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        z-index: 10;
    }
    
    /* Icon Cards Section */
    .icon-cards-section {
        margin-top: 2rem;
    }
    
    .icon-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .icon-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 2px solid #f0f0f0;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .icon-card:hover {
        border-color: #2d5a27;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(45, 90, 39, 0.15);
    }
    
    .icon-card-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .icon-card:hover .icon-card-icon {
        background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
    }
    
    .icon-card-icon i {
        font-size: 1.5rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .icon-card:hover .icon-card-icon i {
        color: white;
    }
    
    .icon-card-label {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.25rem;
    }
    
    .icon-card-value {
        font-size: 16px;
        font-weight: 700;
        color: #2d5a27;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .carousel-image,
        .carousel-placeholder {
            height: 300px;
        }
        
        .carousel-nav {
            width: 40px;
            height: 40px;
        }
        
        .icon-cards-container {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .icon-card {
            padding: 1rem;
        }
        
        .icon-card-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 0.75rem;
        }
        
        .icon-card-icon i {
            font-size: 1.25rem;
        }
    }
    
    @media (max-width: 576px) {
        .carousel-image,
        .carousel-placeholder {
            height: 250px;
        }
        
        .icon-cards-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="event-header">
        <div class="event-header-content">
            <div class="event-title-section">
                <div class="event-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="event-title-text">
                    <h1>{{ $event->title }}</h1>
                    <p>Détails de l'événement</p>
                </div>
            </div>
            <div>
                        <div class="info-item">
                            <div>
                                @if($event->status === 'published')
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check-circle"></i> Publié
                                    </span>
                                @elseif($event->status === 'pending')
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-clock"></i> En attente
                                    </span>
                                @elseif($event->status === 'draft')
                                    <span class="badge-modern badge-secondary">
                                        <i class="fas fa-edit"></i> Brouillon
                                    </span>
                                @elseif($event->status === 'cancelled')
                                    <span class="badge-modern badge-danger">
                                        <i class="fas fa-times-circle"></i> Annulé
                                    </span>
                                @elseif($event->status === 'rejected')
                                    <span class="badge-modern badge-danger">
                                        <i class="fas fa-ban"></i> Rejeté
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content - Two Columns -->
    <div class="row">
        <!-- Left Column: Event Information -->
        <div class="col-lg-6 mb-4">
           
            <div class="image-carousel-container">
                <div class="carousel-wrapper">
                    @if($event->images && count($event->images) > 0)
                        @foreach($event->images as $index => $image)
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $event->title }}" 
                                 class="carousel-image {{ $index === 0 ? '' : 'd-none' }}"
                                 data-index="{{ $index }}">
                        @endforeach
                        
                        <!-- Navigation Arrows -->
                        <button class="carousel-nav prev" onclick="changeImage(-1)" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="carousel-nav next" onclick="changeImage(1)" {{ count($event->images) <= 1 ? 'disabled' : '' }}>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        
                        <!-- Image Counter -->
                        <div class="carousel-counter">
                            <span id="current-image">1</span> / <span id="total-images">{{ count($event->images) }}</span>
                        </div>
                        
                        
                    @else
                        <div class="carousel-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Image Carousel -->
        <div class="col-lg-6 mb-4">
             <div class="info-card">
                <!-- Basic Information -->
                <div class="info-section">
                    <!-- Description -->
                @if($event->description)
                <div class="info-section">
                    <h4><i class="fas fa-align-left"></i> Description</h4>
                    <div class="info-content">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
                @endif
                    <h4><i class="fas fa-info-circle"></i> Informations de base</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Date</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Heure</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($event->event_date)->format('H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Durée</span>
                            <span class="info-value">{{ $event->duration }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Prix</span>
                            <span class="info-value">{{ $event->price ? $event->price . ' TND' : 'Gratuit' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="info-section">
                    <h4><i class="fas fa-map-marker-alt"></i> Lieu</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nom du lieu</span>
                            <span class="info-value">{{ $event->location->name ?? 'Non spécifié' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Adresse</span>
                            <span class="info-value">{{ $event->location->address ?? 'Non spécifiée' }}</span>
                        </div>
                        
                    </div>
                </div>

                <!-- Participants Information -->
                <div class="info-section">
                    <h4><i class="fas fa-users"></i> Participants</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Capacité maximale</span>
                            <span class="info-value">{{ $event->max_participants ?? 'Illimité' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Places réservées</span>
                            @php
                                $reservedSeats = $event->reservations()->where('status', 'confirmed')->count();
                                $availableSeats = $event->max_participants - $reservedSeats;
                            @endphp
                            <span class="info-value">{{ $reservedSeats }}</span>
                        </div>
                       
                    </div>
                </div>

               

                

                
            </div>
        </div>
    </div>

    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentImageIndex = 0;
    const images = document.querySelectorAll('.carousel-image');
    const totalImages = images.length;
    const prevButton = document.querySelector('.carousel-nav.prev');
    const nextButton = document.querySelector('.carousel-nav.next');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const currentImageSpan = document.getElementById('current-image');
    const totalImagesSpan = document.getElementById('total-images');

    function updateCarousel() {
        // Hide all images
        images.forEach(img => img.classList.add('d-none'));
        
        // Show current image
        if (images[currentImageIndex]) {
            images[currentImageIndex].classList.remove('d-none');
        }
        
        // Update navigation buttons
        prevButton.disabled = currentImageIndex === 0;
        nextButton.disabled = currentImageIndex === totalImages - 1;
        
        // Update indicators
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentImageIndex);
        });
        
        // Update counter
        currentImageSpan.textContent = currentImageIndex + 1;
        totalImagesSpan.textContent = totalImages;
    }

    window.changeImage = function(direction) {
        currentImageIndex += direction;
        
        // Ensure index stays within bounds
        if (currentImageIndex < 0) {
            currentImageIndex = 0;
        } else if (currentImageIndex >= totalImages) {
            currentImageIndex = totalImages - 1;
        }
        
        updateCarousel();
    }

    window.goToImage = function(index) {
        currentImageIndex = index;
        updateCarousel();
    }

    // Initialize carousel
    if (totalImages > 0) {
        updateCarousel();
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowLeft') {
            changeImage(-1);
        } else if (event.key === 'ArrowRight') {
            changeImage(1);
        }
    });
});
</script>
@endsection