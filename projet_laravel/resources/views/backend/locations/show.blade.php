@extends('backend.layouts.app')

@section('title', $location->name)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color:#2d5a27;">{{ $location->name }}</h2>
        <a href="{{ route('backend.locations.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
    </div>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color:#2d5a27;">Détails</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nom:</strong> {{ $location->name }}</li>
                        <li class="list-group-item"><strong>Adresse:</strong> {{ $location->address }}</li>
                        <li class="list-group-item"><strong>Ville:</strong> {{ $location->city }}</li>
                        <li class="list-group-item"><strong>Capacité:</strong> {{ $location->capacity ?? '-' }}</li>
                        <li class="list-group-item"><strong>Description:</strong> {{ $location->description ?? '-' }}</li>
                        <li class="list-group-item"><strong>Latitude:</strong> {{ $location->latitude ?? '-' }}</li>
                        <li class="list-group-item"><strong>Longitude:</strong> {{ $location->longitude ?? '-' }}</li>
                    </ul>
                </div>
            </div>
            @if($location->images && count($location->images))
            <div class="mb-4">
                <h5 class="fw-bold mb-2" style="color:#2d5a27;">Images</h5>
                <div class="row g-2">
                    @foreach($location->images as $img)
                        <div class="col-4">
                            <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded-3 shadow-sm" style="height:120px;object-fit:cover;">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color:#2d5a27;">Localisation</h5>
                    <div id="map" style="height:340px; width:100%; border-radius:18px; border:2px solid #2d5a27;"></div>
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
        L.marker([lat, lng]).addTo(map);
    }
</script>
@endpush
@endsection
