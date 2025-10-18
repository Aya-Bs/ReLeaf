@extends('layouts.frontend')

@section('title', 'Modifier le lieu')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-12">
            <div>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-4">
                         <i class="fas fa-map-pin me-2 style="color: #2d5a27;"></i><strong>Modifier le lieu</strong>
                    </h4>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-4" style="background: transparent; padding: 0; margin: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('locations.index') }}" style="color: #2d5a27;">Lieux</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;"><strong>Modifier</strong></li>
                        </ol>
                    </nav>
                </div>

                <!-- Layout -->
                <div class="row justify-content-center" style="max-width:1400px;margin:0 auto;">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-lg-row gap-4">
                            
                            <!-- Form -->
                            <div class="flex-fill" style="flex: 1 1 70%;">
                                <form action="{{ route('locations.update', $location) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded-5 border-0 h-100">
                                    @csrf
                                    @method('PUT')
                                        <div class="col-md-12 mb-3">
                                            <label for="name" class="form-label fw-semibold" style="color:#2d5a27;">
                                                <i class="fas fa-leaf me-2"></i>Nom du lieu <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ old('name', $location->name) }}" required>
                                        </div>
                                                                            
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="address" class="form-label fw-semibold" style="color:#2d5a27;">
                                                <i class="fas fa-map-pin me-2"></i>Adresse <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="address" name="address" value="{{ old('address', $location->address) }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="city" class="form-label fw-semibold" style="color:#2d5a27;">
                                                <i class="fas fa-city me-2"></i>Ville <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="city" name="city" value="{{ old('city', $location->city) }}" required autocomplete="off">
                                            <small class="form-text text-muted">Tapez une ville pour centrer la carte.</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="capacity" class="form-label fw-semibold" style="color:#2d5a27;">
                                                <i class="fas fa-users me-2"></i>Capacité
                                            </label>
                                            <input type="number" class="form-control form-control-lg" id="capacity" name="capacity" value="{{ old('capacity', $location->capacity) }}" min="1">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="price" class="form-label fw-semibold" style="color:#2d5a27;">
                                                <i class="fas fa-dollar-sign me-2"></i>Prix <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control form-control-lg" id="price" name="price" value="{{ old('price', $location->price) }}" min="0" step="0.01" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold" style="color:#2d5a27;">
                                            <i class="fas fa-align-left me-2"></i>Description
                                        </label>
                                        <textarea class="form-control form-control-lg" id="description" name="description" rows="3">{{ old('description', $location->description) }}</textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="in_repair" name="in_repair" value="1" {{ old('in_repair', $location->in_repair) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="in_repair" style="color:#2d5a27;">
                                                <i class="fas fa-tools me-2"></i>Ce lieu est en réparation
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Existing Images Section -->
                                    @if($location->images && count($location->images))
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold" style="color:#2d5a27;">
                                            <i class="fas fa-images me-2"></i>Images existantes
                                        </label>
                                        <div class="d-flex flex-wrap gap-3 mt-2">
                                            @foreach($location->images as $index => $img)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $img) }}" 
                                                     class="rounded" 
                                                     style="width:150px; height:150px; object-fit:cover;">
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 remove-existing-image" 
                                                        data-image-index="{{ $index }}" 
                                                        data-image-path="{{ $img }}"
                                                        style="width:25px;height:25px;padding:0;border-radius:50%;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted mt-2 d-block">Cliquez sur la croix pour supprimer une image.</small>
                                    </div>
                                    @endif

                                    <!-- New Images Upload Section -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold" style="color:#2d5a27;">
                                            <i class="fas fa-plus me-2"></i>Ajouter de nouvelles images
                                        </label><br>

                                        <input type="file" id="images" name="images[]" multiple accept="image/*" hidden>

                                        <label for="images" 
                                               class="border border-2 border-dashed rounded-4 p-4 bg-light text-center w-100 d-block"
                                               style="min-height:160px; cursor:pointer;" 
                                               id="imageDropArea">
                                            <div id="imageDropContent" class="text-center">
                                                <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color:#2d5a27;"></i>
                                                <div style="color:#2d5a27;font-weight:500;">Glissez-déposez ou cliquez pour ajouter des images</div>
                                                <div class="text-muted" style="font-size:0.95em;">Formats: JPG, PNG, GIF. Max: 2MB/image.</div>
                                            </div>

                                            <div id="imagePreview" class="d-flex flex-wrap gap-3 justify-content-center mt-3"></div>
                                        </label>
                                    </div>

                                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $location->latitude) }}">
                                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $location->longitude) }}">
                                    <input type="hidden" id="images_to_delete" name="images_to_delete" value="">

                                    <div class="d-flex justify-content-end mt-4">
                                        <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary px-4 py-2 me-2">Annuler</a>
                                        <button type="submit" class="btn btn-eco px-4 py-2" style="background:#2d5a27; color:white;">Mettre à jour le lieu</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Map -->
                            <div class="flex-shrink-0" style="flex: 0 0 38%; min-width: 320px;">
                                <div class="bg-white shadow rounded-5 border-0 p-3 sticky-top" style="top: 20px;">
                                    <div id="map" style="height:600px; width:100%; border-radius:18px; border:2px solid #2d5a27;"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const imageInput = document.getElementById('images');
const imageDropArea = document.getElementById('imageDropArea');
const imagePreview = document.getElementById('imagePreview');
const imagesToDeleteInput = document.getElementById('images_to_delete');

// === Upload handling ===
imageDropArea.addEventListener('click', () => imageInput.click());
imageInput.addEventListener('change', handleFiles);

function handleFiles() {
    imagePreview.innerHTML = '';
    Array.from(imageInput.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('rounded');
                img.style.width = '150px';
                img.style.height = '150px';
                img.style.objectFit = 'cover';
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
}

// === Drag & Drop ===
imageDropArea.addEventListener('dragover', e => {
    e.preventDefault();
    imageDropArea.classList.add('border-primary');
});
imageDropArea.addEventListener('dragleave', () => imageDropArea.classList.remove('border-primary'));
imageDropArea.addEventListener('drop', e => {
    e.preventDefault();
    imageDropArea.classList.remove('border-primary');
    imageInput.files = e.dataTransfer.files;
    handleFiles();
});

// === Remove existing images ===
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-existing-image')) {
        const button = e.target.closest('.remove-existing-image');
        const imageIndex = button.getAttribute('data-image-index');
        const imagePath = button.getAttribute('data-image-path');
        
        // Add to images_to_delete hidden field
        let imagesToDelete = imagesToDeleteInput.value ? imagesToDeleteInput.value.split(',') : [];
        imagesToDelete.push(imagePath);
        imagesToDeleteInput.value = imagesToDelete.join(',');
        
        // Remove from UI
        button.closest('.position-relative').remove();
    }
});

// === Map ===
let map = L.map('map').setView([{{ $location->latitude ?? 36.8065 }}, {{ $location->longitude ?? 10.1815 }}], {{ $location->latitude && $location->longitude ? 13 : 7 }});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap'
}).addTo(map);
let marker = null;

@if($location->latitude && $location->longitude)
marker = L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map);
@endif

function updateMap() {
    let lat = parseFloat(document.getElementById('latitude').value);
    let lng = parseFloat(document.getElementById('longitude').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        map.setView([lat, lng], 13);
        if (marker) marker.setLatLng([lat, lng]);
        else marker = L.marker([lat, lng]).addTo(map);
    }
}

map.on('click', function(e) {
    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    updateMap();
});

const cityInput = document.getElementById('city');
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
                        if (marker) marker.setLatLng([lat, lon]);
                        else marker = L.marker([lat, lon]).addTo(map);
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