@extends('backend.layouts.app')

@section('title', $location->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.locations.index') }}">Locations</a></li>
    <li class="breadcrumb-item active">{{ $location->name }}</li>
@endsection


@section('content')
<style>
    .location-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .location-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .location-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .location-icon {
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
    
    .location-title-text h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #2d5a27;
    }
    
    .location-title-text p {
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
    
    .badge-modern.badge-light {
        background: linear-gradient(135deg, #fdfdfe 0%, #f8f9fa 100%);
        color: #495057;
        border: 1px solid #e9ecef;
    }
    
    .map-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    
    #locationMap {
        height: 400px;
        width: 100%;
        border-radius: 12px;
        border: 2px solid #e9ecef;
    }
    
    .gallery-section {
        margin-top: 2rem;
    }
    
    .gallery-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    
    .gallery-header {
        padding: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .gallery-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #2d5a27;
    }
    
    .gallery-header i {
        color: #2d5a27;
        font-size: 20px;
    }
    
    .events-section {
        margin-top: 2rem;
    }
    
    .events-list-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    
    .events-list-header {
        padding: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .events-list-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #2d5a27;
    }
    
    .events-list-header i {
        color: #2d5a27;
        font-size: 20px;
    }
    
    .table-modern {
        margin: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    
    .table-modern thead th {
        background: #f8f9fa;
        border: none;
        padding: 0.75rem 1.2rem;
        font-weight: 500;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2d5a27;
    }
    
    .table-modern tbody tr {
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }
    
    .table-modern tbody tr:hover {
        background: #f4f8f4;
        transform: scale(1.01);
    }
    
    .table-modern tbody td {
        padding: 0.85rem 1.2rem;
        vertical-align: middle;
        border: none;
        font-size: 13px;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }
    
    .empty-state-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 24px;
        color: #adb5bd;
    }
    
    .empty-state h4 {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 16px;
    }
    
    .empty-state p {
        color: #adb5bd;
        margin: 0;
        font-size: 14px;
    }
    
    /* Updated Image Gallery Styles */
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
    }
    
    .image-item {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        background: white;
    }
    
    .image-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }
    
    .image-item img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        display: block;
    }
    
    .image-item:hover img {
        transform: scale(1.05);
    }
    
    /* Custom Pagination Styles */
    .custom-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.9rem;
        margin-top: 1.5rem;
        padding: 1rem;
    }
    
    .custom-pagination .page-item {
        margin: 0;
    }
    
    .custom-pagination .page-link {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 14px;
        font-weight: 500;
        color: #2d5a27;
        background: white;
        transition: all 0.2s ease;
        text-decoration: none;
        min-width: 42px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .custom-pagination .page-item.active .page-link {
        background: #2d5a27;
        border-color: #2d5a27;
        color: white;
    }
    
    .custom-pagination .page-link:hover {
        background: #e9f5e6;
        border-color: #2d5a27;
        color: #2d5a27;
    }
    
    .custom-pagination .page-item.disabled .page-link {
        color: #bdbdbd;
        background: #f8f9fa;
        border-color: #e0e0e0;
        cursor: not-allowed;
    }
    
    .custom-pagination .pagination-info {
        font-size: 14px;
        color: #6c757d;
        margin: 0 1rem;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .image-gallery {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }
        
        .image-item img {
            height: 120px;
        }
        
        .custom-pagination {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .custom-pagination .pagination-info {
            order: -1;
            width: 100%;
            text-align: center;
            margin-bottom: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .image-gallery {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 0.75rem;
        }
        
        .image-item img {
            height: 100px;
        }
        
        .custom-pagination .page-link {
            padding: 0.4rem 0.8rem;
            font-size: 13px;
            min-width: 38px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="location-header">
        <div class="location-header-content">
            <div class="location-title-section">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="location-title-text">
                    <h1>{{ $location->name }}</h1>
                    <p>Détails du lieu</p>
                </div>
            </div>
            <div>
                <a href="{{ route('backend.locations.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>  
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content - Two Columns -->
    <div class="row">
        <!-- Left Column: Location Information -->
        <div class="col-lg-6 mb-4">
            <div class="info-card">
                <!-- Basic Information -->
                <div class="info-section">
                    <!-- Description -->
                @if($location->description)
                <div class="info-section">
                    <h4><i class="fas fa-align-left"></i> Description</h4>
                    <div class="info-content">
                        {{ $location->description }}
                    </div>
                </div>
                @endif
                
                    <h4><i class="fas fa-info-circle"></i> Informations de base</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nom</span>
                            <span class="info-value">{{ $location->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Ville</span>
                            <span class="info-value">{{ $location->city }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Capacité</span>
                            <span class="info-value">{{ $location->capacity ?? 'Non spécifiée' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Adresse</span>
                            <span class="info-value"> {{ $location->address  ?? 'Non spécifiée' }}</span>
                        </div>
                    </div>
                </div>
               
                

                <!-- Status -->
                <div class="info-section">
                    <h4><i class="fas fa-tag"></i> Statut</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Réservation</span>
                            <div>
                                @if($location->reserved)
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-lock"></i> Réservé
                                    </span>
                                @else
                                    <span class="badge-modern badge-secondary">
                                        <i class="fas fa-unlock"></i> Libre
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Maintenance</span>
                            <div>
                                @if($location->in_repair)
                                    <span class="badge-modern badge-danger">
                                        <i class="fas fa-tools"></i> En réparation
                                    </span>
                                @else
                                    <span class="badge-modern badge-light">
                                        <i class="fas fa-check"></i> Opérationnel
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coordinates -->
                <div class="info-section">
                    <h4><i class="fas fa-globe"></i> Coordonnées GPS</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Latitude</span>
                            <span class="info-value">{{ $location->latitude }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Longitude</span>
                            <span class="info-value">{{ $location->longitude }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Map -->
        <div class="col-lg-6 mb-4">
            <div class="map-container">
                <div id="locationMap"></div>
            </div>
        </div>
    </div>

    <!-- Gallery Section - Full Width -->
    @if($location->images && count($location->images) > 0)
    <div class="gallery-section">
        <div class="gallery-card">
            <div class="gallery-header">
                <i class="fas fa-images"></i>
                <h3>Galerie d'images</h3>
            </div>
            <div class="image-gallery">
                @foreach($location->images as $image)
                <div class="image-item">
                    <img src="{{ asset('storage/' . $image) }}" alt="Image du lieu {{ $location->name }}">
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

<!-- Events Section -->
<div class="events-section">
    <div class="events-list-card">
        <div class="events-list-header">
            <i class="fas fa-calendar-alt"></i>
            <h3>Événements à ce lieu</h3>
        </div>
        <div class="card-body p-0">
            <div id="events-table-container">
                @include('backend.locations.partials.events_table', ['events' => $events])
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<!-- Leaflet.js for map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('locationMap').setView([{{ $location->latitude }}, {{ $location->longitude }}], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    
    // Add marker for the location
    L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
        .addTo(map)
        .bindPopup('<strong>{{ $location->name }}</strong><br>{{ $location->address }}')
        .openPopup();
});
</script>
@endpush
@endsection