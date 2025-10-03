@extends('layouts.frontend')

@section('title', 'Créer un Événement')


@section('content')
<div class="container">

    <div class="row">
        <div class="col-12">
            <div >
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-4">
                        <i class="fas fa-calendar-plus me-2" style="color: #2d5a27;"></i><strong>Créer un nouvel événement</strong>
                    </h4>
                    <!-- Breadcrumb path on top right -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-4" style="background: transparent; padding: 0; margin: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27;">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('events.index') }}" style="color: #2d5a27;">Événements</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;"><strong>Créer</strong></li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <strong>Erreur :</strong> {{ session('error') }}
                        </div>
                    @endif
                    @if(session('debug'))
                        <div class="alert alert-warning">
                            <strong>Debug :</strong> {{ session('debug') }}
                        </div>
                    @endif
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" id="eventForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Left Column - Form Fields -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading me-2" style="color: #2d5a27;"></i>Titre de l'événement <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           required 
                                           placeholder="Ex: Nettoyage de la plage">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left me-2" style="color: #2d5a27;"></i>Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              required 
                                              placeholder="Décrivez votre événement...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date and Time -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">
                                                <i class="fas fa-calendar-day me-2" style="color: #2d5a27;"></i>Date et heure <span class="text-danger">*</span>
                                            </label>
                                            <input type="datetime-local" 
                                                   class="form-control @error('date') is-invalid @enderror" 
                                                   id="date" 
                                                   name="date" 
                                                   value="{{ old('date') }}" 
                                                   required 
                                                   min="{{ now()->format('Y-m-d\TH:i') }}">
                                            @error('date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">
                                                <i class="fas fa-clock me-2" style="color: #2d5a27;"></i>Durée <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('duration') is-invalid @enderror" 
                                                    id="duration" 
                                                    name="duration" 
                                                    required>
                                                <option value="">Sélectionnez une durée</option>
                                                <option value="1 heure" {{ old('duration') == '1 heure' ? 'selected' : '' }}>1 heure</option>
                                                <option value="2 heures" {{ old('duration') == '2 heures' ? 'selected' : '' }}>2 heures</option>
                                                <option value="3 heures" {{ old('duration') == '3 heures' ? 'selected' : '' }}>3 heures</option>
                                                <option value="4 heures" {{ old('duration') == '4 heures' ? 'selected' : '' }}>4 heures</option>
                                                <option value="Demi-journée" {{ old('duration') == 'Demi-journée' ? 'selected' : '' }}>Demi-journée</option>
                                                <option value="Journée entière" {{ old('duration') == 'Journée entière' ? 'selected' : '' }}>Journée entière</option>
                                                <option value="Week-end" {{ old('duration') == 'Week-end' ? 'selected' : '' }}>Week-end</option>
                                            </select>
                                            @error('duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">

                                <!-- Location Dropdown -->
                                <div class="col-md-6">
                                    <label for="location_id" class="form-label">
                                        <i class="fas fa-map-marker-alt me-2" style="color: #2d5a27;"></i>Lieu <span class="text-danger">*</span>
                                    </label>
                                    @php
                                        $locations = \App\Models\Location::all();
                                    @endphp
                                    <select class="form-select @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                                        <option value="">Sélectionnez un lieu</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }} ({{ $location->city }})</option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                  
                                    <div class="col-md-6 ">
                                    <label for="campaign_id" class="form-label  
                                             @error('campaign_id') is-invalid @enderror" 
                                             style="color: #2d5a27;">
                                         <i class="fas fa-bullhorn me-2" style="color: #2d5a27;"></i>Campagne
                                    </label>
                                    <select class="form-select @error('campaign_id') is-invalid @enderror"
                                            id="campaign_id" 
                                            name="campaign_id">
                                        <option value="">Sélectionnez une campagne</option>
                                        @php
                                            $campaigns = \App\Models\Campaign::all();
                                        @endphp
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('campaign_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>
                                </div>
                                <div class="mb-3">
                                    <label for="max_participants" class="form-label">
                                        <i class="fas fa-users me-2" style="color: #2d5a27;"></i>Nombre maximum de participants
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('max_participants') is-invalid @enderror" 
                                           id="max_participants" 
                                           name="max_participants" 
                                           value="{{ old('max_participants') }}" 
                                           min="1" 
                                           placeholder="Laissez vide pour illimité">
                                    @error('max_participants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Optionnel - Laissez vide si pas de limite</small>
                                </div>
                              
</div>


                            <!-- Right Column - More Fields, Images and Actions -->
                            <div class="col-md-4 ">
                                <!-- Max Participants -->
                         

                                <!-- Images - Updated Drag & Drop Style -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-images me-2" style="color: #2d5a27;"></i>Images de l'événement
                                    </label>
                                    <div class="drag-drop-area @error('images.*') is-invalid @enderror" id="dragDropArea" style="border:2px dashed #2d5a27; border-radius:12px; background:#f4fbf4; padding:1.2rem 0.5rem; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; max-width:340px; margin:auto;">
                                        <div class="drag-drop-content" style="pointer-events:none;">
                                            <i class="fas fa-cloud-upload-alt" style="font-size:2.1rem; color:#2d5a27; margin-bottom:0.5rem;"></i>
                                            <div style="font-size:1.05rem; color:#2d5a27; font-weight:500;">Drag & Drop vos images</div>
                                            <div style="color:#2d5a27; margin:0.25rem 0; font-size:0.95rem;">ou</div>
                                            <div style="pointer-events:all;">
                                                <button type="button" class="btn btn-outline-success btn-sm" id="browseBtn" style="border-color:#2d5a27; color:#2d5a27;">Parcourir les fichiers</button>
                                                <input type="file" id="images" name="images[]" multiple accept="image/*" style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Vous pouvez sélectionner plusieurs images. Formats acceptés: JPG, PNG, GIF. Max: 2MB par image.
                                    </small>
                                    <!-- Image preview -->
                                    <div id="imagePreview" class="mt-3 row g-2"></div>
                                </div>

                                <!-- Information Box -->
                               

                                <!-- Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-eco" style="background-color: #2d5a27; border-color: #2d5a27; color: white;">
                                        <i class="fas fa-save me-2"></i>Créer l'événement
                                    </button>
                                </div>

                                 <div class="alert alert-info mt-6 mb-4">
                                    <i class="fas fa-info-circle me-2 "></i>
                                    <strong>Important :</strong> Après la création, votre événement sera en statut "Brouillon". 
                                    Vous pourrez le soumettre pour approbation depuis la liste de vos événements.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Drag & Drop functionality
const dragDropArea = document.getElementById('dragDropArea');
const fileInput = document.getElementById('images');
const browseBtn = document.getElementById('browseBtn');

// Browse button click
browseBtn.addEventListener('click', function() {
    fileInput.click();
});

// File input change
fileInput.addEventListener('change', function(e) {
    handleFiles(e.target.files);
});

// Drag & drop events
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dragDropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dragDropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dragDropArea.addEventListener(eventName, unhighlight, false);
});

function highlight() {
    dragDropArea.classList.add('drag-over');
}

function unhighlight() {
    dragDropArea.classList.remove('drag-over');
}

dragDropArea.addEventListener('drop', function(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
    fileInput.files = files;
});

// Handle selected files
function handleFiles(files) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-4 mb-2';
                col.innerHTML = `
                    <div class="card position-relative">
                        <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-new-image" data-file-name="${file.name}" style="padding: 0.25rem 0.5rem;">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="card-body p-2">
                            <small class="text-muted d-block text-truncate">${file.name}</small>
                        </div>
                    </div>
                `;
                preview.appendChild(col);
                
                // Add remove functionality for new images
                col.querySelector('.remove-new-image').addEventListener('click', function() {
                    removeNewImage(this, file.name);
                });
            };
            
            reader.readAsDataURL(file);
        }
    }
}

// Remove new image before upload
function removeNewImage(button, fileName) {
    if (confirm('Supprimer cette image ?')) {
        const card = button.closest('.col-6');
        card.remove();
        
        // Remove file from input
        const dt = new DataTransfer();
        const files = fileInput.files;
        
        for (let i = 0; i < files.length; i++) {
            if (files[i].name !== fileName) {
                dt.items.add(files[i]);
            }
        }
        
        fileInput.files = dt.files;
    }
}

// Form validation
document.getElementById('eventForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    const date = document.getElementById('date').value;
    const location = document.getElementById('location').value;
    const duration = document.getElementById('duration').value;

    if (!title || !description || !date || !location || !duration) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }

    const selectedDate = new Date(date);
    if (selectedDate <= new Date()) {
        e.preventDefault();
        alert('La date de l\'événement doit être dans le futur.');
        return;
    }
});

// Set min datetime for date field
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('date').min = now.toISOString().slice(0, 16);
});
</script>
@endpush

@push('styles')
<style>
.drag-drop-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 10px;
}

.drag-drop-area:hover {
    border-color: #2d5a27;
    background-color: #f0f9f0;
}

.drag-drop-area.drag-over {
    border-color: #2d5a27;
    background-color: #e8f5e8;
}

.drag-drop-content {
    pointer-events: none;
}

.drag-drop-icon {
    font-size: 48px;
    color: #6c757d;
    margin-bottom: 16px;
}

.drag-drop-area h5 {
    color: #495057;
    font-weight: 500;
    margin-bottom: 8px;
    font-size: 16px;
}

.drag-drop-area .text-muted {
    color: #6c757d !important;
    margin-bottom: 12px;
    font-size: 14px;
}

#browseBtn {
    pointer-events: all;
    background-color: white;
    border: 1px solid #2d5a27;
    color: #2d5a27
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
}

#browseBtn:hover {
    background-color: #2d5a27;
    color: white;
}

.card-img-top {
    object-fit: cover;
}

.remove-new-image {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.remove-new-image:hover {
    opacity: 1;
}

.alert-info {
    background-color: #e8f4f8;
    border-color: #b3e0f0;
    color: #055160;
}

.alert-info i {
    color: #055160;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-md-6 {
        width: 100%;
    }
    
    .drag-drop-area {
        padding: 30px 15px;
    }
    
    .drag-drop-icon {
        font-size: 36px;
    }
}
</style>
@endpush