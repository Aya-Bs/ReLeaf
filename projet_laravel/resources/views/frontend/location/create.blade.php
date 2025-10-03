@extends('layouts.frontend')

@section('title', 'Ajouter un lieu')

@section('content')
    <div class="container-fluid py-4" >
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0 me-6" style="color:#2d5a27;"><i class="fas fa-map-marker-alt me-2" style="color:#2d5a27;"></i><span style=" font-weight:bold;">Ajouter un nouveau lieu</span></h2>
            <nav aria-label="breadcrumb" class="bg-white shadow-sm rounded-pill px-3 py-1" style="font-size:0.95em; box-shadow:0 2px 8px rgba(0,0,0,0.07)!important;">
                <ol class="breadcrumb mb-0" style="background: transparent; padding: 0; margin: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27; opacity:0.7;">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('locations.index') }}" style="color: #2d5a27; opacity:0.7;">Lieux</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27; font-weight:bold;">Ajouter</li>
                </ol>
            </nav>
        </div>
        
        <!-- <CHANGE> Two-column layout: form on left, map on right -->
        <div class="row justify-content-center" style="max-width:1400px;margin:0 auto;">
            <div class="col-12">
                <div class="d-flex flex-column flex-lg-row gap-4">
                    
                    <!-- Left Column: Form -->
                    <div class="flex-fill" style="flex: 1 1 60%;">
                        <form action="{{ route('locations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded-5 border-0 h-100">
                            @csrf
                            <div class="row">
                                                                <div class="col-md-6 mb-3">

                                    <label for="name" class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-leaf me-2" style="color:#2d5a27;"></i>Nom du lieu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="capacity" class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-users me-2" style="color:#2d5a27;"></i>Capacité</label>
                                    <input type="number" class="form-control form-control-lg" id="capacity" name="capacity" value="{{ old('capacity') }}" min="1">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-map-pin me-2" style="color:#2d5a27;"></i>Adresse <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="address" name="address" value="{{ old('address') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-city me-2" style="color:#2d5a27;"></i>Ville <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" id="city" name="city" value="{{ old('city') }}" required autocomplete="off">
                                    <small class="form-text text-muted">Tapez une ville pour centrer la carte.</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-align-left me-2" style="color:#2d5a27;"></i>Description</label>
                                <textarea class="form-control form-control-lg" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-globe-africa me-2" style="color:#2d5a27;"></i>Latitude</label>
                                    <input type="text" class="form-control form-control-lg" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-globe-europe me-2" style="color:#2d5a27;"></i>Longitude</label>
                                    <input type="text" class="form-control form-control-lg" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="color:#2d5a27;"><i class="fas fa-images me-2" style="color:#2d5a27;"></i>Images</label>
                                <div class="border border-2 border-dashed rounded-4 p-4 bg-light text-center position-relative" style="min-height: 160px; cursor:pointer;" id="imageDropArea">
                                    <input type="file" id="images" name="images[]" multiple accept="image/*" style="opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;z-index:2;">
                                    <div id="imageDropContent" style="z-index:1;">
                                        <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color:#2d5a27;"></i>
                                        <div style="color:#2d5a27;font-weight:500;">Glissez-déposez ou cliquez pour ajouter des images</div>
                                        <div class="text-muted" style="font-size:0.95em;">Formats: JPG, PNG, GIF. Max: 2MB/image.</div>
                                    </div>
                                    <div id="imagePreview" class="row mt-3 g-2"></div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary px-4 py-2 me-2">Annuler</a>
                                <button type="submit" class="btn btn-eco px-4 py-2" style="background:#2d5a27; color:white;">Ajouter le lieu</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Right Column: Map -->
                    <div class="flex-shrink-0" style="flex: 0 0 38%; min-width: 320px;">
                        <div class="bg-white shadow rounded-5 border-0 p-3 sticky-top" style="top: 20px; height: fit-content;">
                            <div id="map" style="height:600px; width:100%; border-radius:18px; border:2px solid #2d5a27;"></div>
                        </div>
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
const imageInput = document.getElementById('images');
const imageDropArea = document.getElementById('imageDropArea');
const imagePreview = document.getElementById('imagePreview');

imageDropArea.addEventListener('click', () => imageInput.click());
imageInput.addEventListener('change', handleFiles);

function handleFiles() {
    imagePreview.innerHTML = '';
    Array.from(imageInput.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-4 mb-2';
                col.innerHTML = `<div class='card border-0 shadow-sm'><img src='${e.target.result}' class='img-fluid rounded-3' style='height:90px;object-fit:cover;'></div>`;
                imagePreview.appendChild(col);
            };
            reader.readAsDataURL(file);
        }
    });
}

// Map logic
let map = L.map('map').setView([36.8065, 10.1815], 7); // Default: Tunis
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap'
}).addTo(map);
let marker = null;

function updateMap() {
    let lat = parseFloat(document.getElementById('latitude').value);
    let lng = parseFloat(document.getElementById('longitude').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        map.setView([lat, lng], 13);
        if (marker) { marker.setLatLng([lat, lng]); }
        else { marker = L.marker([lat, lng]).addTo(map); }
    }
}
document.getElementById('latitude').addEventListener('input', updateMap);
document.getElementById('longitude').addEventListener('input', updateMap);

// Click on map to fill lat/lng
map.on('click', function(e) {
    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    updateMap();
});

// City search and zoom
let cityInput = document.getElementById('city');
let cityTimeout = null;
cityInput.addEventListener('input', function() {
    clearTimeout(cityTimeout);
    let city = cityInput.value.trim();
    if (city.length > 2) {
        cityTimeout = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?city=${encodeURIComponent(city)}&format=json&limit=1`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        let lat = parseFloat(data[0].lat);
                        let lon = parseFloat(data[0].lon);
                        map.setView([lat, lon], 13);
                        if (marker) { marker.setLatLng([lat, lon]); }
                        else { marker = L.marker([lat, lon]).addTo(map); }
                        document.getElementById('latitude').value = lat.toFixed(6);
                        document.getElementById('longitude').value = lon.toFixed(6);
                    }
                });
        }, 600);
    }
});
</script>
@endpush
@endsection