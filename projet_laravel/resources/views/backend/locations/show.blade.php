@extends('backend.layouts.app')

@section('title', $location->name)

@section('content')
<style>
    body, .container-fluid {
        background: #f7f8fa !important;
    }
    .location-map-section {
        position: relative;
        width: 100vw;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background: #eaf3ea;
        min-height: 420px;
        overflow: visible;
        z-index: 1;
    }
    #map {
        width: 100%;
        height: 420px;
        border-radius: 0 0 32px 32px;
        box-shadow: 0 4px 24px rgba(45,90,39,0.10);
        z-index: 1;
    }
    .floating-stats {
        position: absolute;
        top: 32px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 18px;
        z-index: 10;
    }
    .stat-card {
        background: #111;
        color: #fff;
        border-radius: 18px;
        padding: 18px 28px 12px 28px;
        min-width: 120px;
        text-align: center;
        font-size: 22px;
        font-weight: 600;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .stat-card-label {
        font-size: 13px;
        color: #e0e0e0;
        font-weight: 400;
        margin-top: 2px;
    }
    .location-main {
        margin-top: -90px;
        z-index: 2;
        position: relative;
    }
    .location-info-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 16px rgba(45,90,39,0.10);
        padding: 32px 32px 24px 32px;
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .location-info-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .location-info-title {
        font-size: 22px;
        font-weight: 700;
        color: #222;
    }
    .location-info-heart {
        color: #f7b731;
        font-size: 22px;
        margin-left: 8px;
        cursor: pointer;
    }
    .location-info-meta {
        color: #888;
        font-size: 15px;
        margin-bottom: 8px;
    }
    .location-info-icons {
        display: flex;
        gap: 18px;
        margin-top: 10px;
    }
    .location-info-icon {
        background: #f4f7f4;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 18px;
        color: #2d5a27;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .location-stats-row {
        display: flex;
        gap: 18px;
        margin-top: 18px;
    }
    .location-stat-box {
        background: #f4f7f4;
        border-radius: 16px;
        flex: 1;
        padding: 18px 0 10px 0;
        text-align: center;
        font-size: 20px;
        font-weight: 600;
        color: #222;
        box-shadow: 0 1px 4px rgba(45,90,39,0.04);
    }
    .location-stat-label {
        font-size: 13px;
        color: #888;
        font-weight: 400;
        margin-top: 2px;
    }
    .location-image-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 16px rgba(45,90,39,0.10);
        padding: 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 24px;
    }
    .location-image-main {
        width: 100%;
        max-width: 340px;
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(45,90,39,0.10);
        object-fit: cover;
        aspect-ratio: 4/3;
    }
</style>
<div class="location-map-section">
    <div id="map"></div>
    <div class="floating-stats">
        <div class="stat-card">
            {{ $location->latitude ?? '-' }}
            <div class="stat-card-label">Latitude</div>
        </div>
        <div class="stat-card">
            {{ $location->longitude ?? '-' }}
            <div class="stat-card-label">Longitude</div>
        </div>
        <div class="stat-card">
            {{ $location->capacity ?? '-' }}
            <div class="stat-card-label">Capacity</div>
        </div>
    </div>
</div>
<div class="container location-main">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="location-info-card">
                <div class="location-info-header">
                    <div>
                        <div class="location-info-title">{{ $location->name }}</div>
                        <div class="location-info-meta">{{ $location->full_address ?? ($location->address . ', ' . $location->city) }}</div>
                    </div>
                    <div class="location-info-heart"><i class="fas fa-heart"></i></div>
                </div>
                <div class="location-info-description" style="margin-top:10px; color:#444; font-size:16px;">
                    {{ $location->description ?? '-' }}
                </div>
                <div class="location-stats-row">
                    <div class="location-stat-box">
                        {{ $location->capacity ?? '-' }}
                        <div class="location-stat-label">Capacity</div>
                    </div>
                    <div class="location-stat-box">
                        {{ $location->city ?? '-' }}
                        <div class="location-stat-label">City</div>
                    </div>
                    <div class="location-stat-box">
                        {{-- TODO: Replace 'temperature' with real value from weather API in controller --}}
                        {{ isset($temperature) ? $temperature . '°C' : '-' }}
                        <div class="location-stat-label">Temperature</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="location-image-card">
                @if($location->images && count($location->images))
                    <img src="{{ asset('storage/' . $location->images[0]) }}" class="location-image-main" alt="Location image">
                @else
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80" class="location-image-main" alt="Location image">
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let lat = {{ $location->latitude ?? 'null' }};
    let lng = {{ $location->longitude ?? 'null' }};
    let map = L.map('map').setView([lat || 36.8065, lng || 10.1815], lat && lng ? 13 : 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    if(lat && lng) {
        const cuteIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // green marker icon
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -38],
        });
        L.marker([lat, lng], {icon: cuteIcon}).addTo(map);
    }
</script>
@endpush
@endsection
