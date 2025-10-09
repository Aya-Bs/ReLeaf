@extends('layouts.frontend')

@section('title', 'Modifier le lieu')

@section('content')
<div class="container-fluid py-4" style="background: #f8fbf7; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold mb-0" style="color:#2d5a27;"><i class="fas fa-map-marker-alt me-2" style="color:#2d5a27;"></i>Modifier le lieu</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="background: transparent; padding: 0; margin: 0;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('locations.index') }}" style="color: #2d5a27;">Lieux</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;">Modifier</li>
            </ol>
        </nav>
    </div>
    <form action="{{ route('locations.update', $location) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow-lg rounded-4" style="max-width: 900px; margin:auto;">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-6">
                <h5 class="fw-bold mb-3" style="color:#2d5a27;">Informations principales</h5>
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold" style="color:#2d5a27;">
                        <i class="fas fa-leaf me-2" style="color:#2d5a27;"></i><span style="color:#111;">Nom du lieu <span class="text-danger">*</span></span>
                    </label>
                    <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ old('name', $location->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label fw-semibold" style="color:#2d5a27;">
                        <i class="fas fa-map-pin me-2" style="color:#2d5a27;"></i><span style="color:#111;">Adresse <span class="text-danger">*</span></span>
                    </label>
                    <input type="text" class="form-control form-control-lg" id="address" name="address" value="{{ old('address', $location->address) }}" required>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label fw-semibold" style="color:#2d5a27;">
                        <i class="fas fa-city me-2" style="color:#2d5a27;"></i><span style="color:#111;">Ville <span class="text-danger">*</span></span>
                    </label>
                    <input type="text" class="form-control form-control-lg" id="city" name="city" value="{{ old('city', $location->city) }}" required>
                </div>
                <div class="mb-3">
                    <label for="capacity" class="form-label fw-semibold" style="color:#2d5a27;">
                        <i class="fas fa-users me-2" style="color:#2d5a27;"></i><span style="color:#111;">Capacité</span>
                    </label>
                    <input type="number" class="form-control form-control-lg" id="capacity" name="capacity" value="{{ old('capacity', $location->capacity) }}" min="1">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold" style="color:#2d5a27;">
                        <i class="fas fa-align-left me-2" style="color:#2d5a27;"></i><span style="color:#111;">Description</span>
                    </label>
                    <textarea class="form-control form-control-lg" id="description" name="description" rows="4">{{ old('description', $location->description) }}</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="fw-bold mb-3" style="color:#2d5a27;">Coordonnées & Images</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label fw-semibold" style="color:#2d5a27;">
                            <i class="fas fa-globe-africa me-2" style="color:#2d5a27;"></i><span style="color:#111;">Latitude</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" id="latitude" name="latitude" value="{{ old('latitude', $location->latitude) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label fw-semibold" style="color:#2d5a27;">
                            <i class="fas fa-globe-europe me-2" style="color:#2d5a27;"></i><span style="color:#111;">Longitude</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" id="longitude" name="longitude" value="{{ old('longitude', $location->longitude) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color:#2d5a27;">
                        <i class="fas fa-images me-2" style="color:#2d5a27;"></i><span style="color:#111;">Images</span>
                    </label>
                    <div class="border border-2 border-dashed rounded-4 p-4 bg-light text-center position-relative" style="min-height: 180px; cursor:pointer;" id="imageDropArea">
                        <input type="file" id="images" name="images[]" multiple accept="image/*" style="opacity:0;position:absolute;left:0;top:0;width:100%;height:100%;cursor:pointer;z-index:2;">
                        <div id="imageDropContent" style="z-index:1;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color:#2d5a27;"></i>
                            <div style="color:#2d5a27;font-weight:500;">Glissez-déposez ou cliquez pour ajouter des images</div>
                            <div class="text-muted" style="font-size:0.95em;">Formats: JPG, PNG, GIF. Max: 2MB/image.</div>
                        </div>
                        <div id="imagePreview" class="row mt-3 g-2"></div>
                        @if($location->images && count($location->images))
                            <div class="row mt-2 g-2">
                                @foreach($location->images as $img)
                                    <div class="col-4 mb-2">
                                        <div class="card border-0 shadow-sm">
                                            <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded-3" style="height:90px;object-fit:cover;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary px-4 py-2 me-2">Annuler</a>
            <button type="submit" class="btn btn-eco px-4 py-2" style="background:#2d5a27; color:white;">Mettre à jour</button>
        </div>
    </form>
</div>
@push('scripts')
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
</script>
@endpush
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
