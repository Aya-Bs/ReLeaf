@extends('layouts.frontend')

@section('title', 'Modifier la Campagne: ' . $campaign->name)

@section('content')
<!-- Hero Section -->
<section class="hero-section py-4" style="background: #f8f9fa !important; min-height: 30vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-3" style="color: #2d5a27 !important; font-size: 2.5rem;">
                    <i class="fas fa-edit me-2" style="color: #2d5a27 !important;"></i>Modifier la <span style="color: #4a7c59 !important;">Campagne</span>
                </h1>
                <p class="lead" style="color: #2d5a27 !important; font-weight: 600; font-size: 1.25rem;">{{ $campaign->name }}</p>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <!-- Navigation Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="breadcrumb-nav">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('campaigns.index') }}" class="text-eco">
                                    <i class="fas fa-leaf me-1"></i>Campagnes
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('campaigns.show', $campaign) }}" class="text-eco">
                                    {{ Str::limit($campaign->name, 20) }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="campaign-form-card">
                <div class="form-content">
                    <form id="campaignForm" action="{{ route('campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-tag me-2 text-eco"></i>Nom de la campagne *
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $campaign->name) }}" required
                                           style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-2 text-eco"></i>Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4"
                                              style="font-size: 12px !important; padding: 8px 10px !important;">{{ old('description', $campaign->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="category" class="form-label fw-bold">
                                                <i class="fas fa-folder me-2 text-eco"></i>Catégorie *
                                            </label>
                                            <select class="form-select @error('category') is-invalid @enderror" 
                                                    id="category" name="category" required
                                                    style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                                <option value="">🌱 Sélectionnez une catégorie</option>
                                                @foreach(['reforestation' => '🌲 Reforestation', 'nettoyage' => '🧹 Nettoyage', 'sensibilisation' => '📢 Sensibilisation', 'recyclage' => '♻️ Recyclage', 'biodiversite' => '🦋 Biodiversité', 'energie_renouvelable' => '⚡ Énergie Renouvelable', 'autre' => '🔧 Autre'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('category', $campaign->category) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="status" class="form-label fw-bold">
                                                <i class="fas fa-toggle-on me-2 text-eco"></i>Statut *
                                            </label>
                                            <select class="form-select @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required
                                                    style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                                @foreach(['active' => '🟢 Active', 'inactive' => '🔴 Inactive', 'completed' => '✅ Terminée', 'cancelled' => '❌ Annulée'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('status', $campaign->status) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="start_date" class="form-label fw-bold">
                                                <i class="fas fa-calendar-plus me-2 text-eco"></i>Date de début *
                                            </label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" 
                                                   value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}" required
                                                   style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="end_date" class="form-label fw-bold">
                                                <i class="fas fa-calendar-check me-2 text-eco"></i>Date de fin *
                                            </label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" 
                                                   value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}" required
                                                   style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="goal" class="form-label">Objectif financier (€)</label>
                                            <input type="number" step="0.01" class="form-control @error('goal') is-invalid @enderror" 
                                                   id="goal" name="goal" value="{{ old('goal', $campaign->goal) }}"
                                                   style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                            @error('goal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="funds_raised" class="form-label">Montant collecté (€)</label>
                                            <input type="number" step="0.01" class="form-control @error('funds_raised') is-invalid @enderror" 
                                                   id="funds_raised" name="funds_raised" value="{{ old('funds_raised', $campaign->funds_raised) }}"
                                                   style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                            @error('funds_raised')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="environmental_impact" class="form-label">Impact environnemental</label>
                                    <input type="text" class="form-control @error('environmental_impact') is-invalid @enderror" 
                                           id="environmental_impact" name="environmental_impact" 
                                           value="{{ old('environmental_impact', $campaign->environmental_impact) }}"
                                           style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                    @error('environmental_impact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" 
                                           value="{{ old('tags', $campaign->tags ? implode(', ', $campaign->tags) : '') }}" 
                                           placeholder="ex: écologie, déchets, recyclage"
                                           style="font-size: 12px !important; padding: 6px 10px !important; height: 35px !important;">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Image actuelle</label>
                                    @if($campaign->image_url)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($campaign->image_url) }}" 
                                                 alt="{{ $campaign->name }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="text-muted mb-2">
                                            <i class="fas fa-image fa-2x"></i>
                                            <p>Aucune image</p>
                                        </div>
                                    @endif
                                    
                                    <label for="image" class="form-label">Changer l'image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*"
                                           style="font-size: 12px !important; padding: 6px 10px !important;">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 form-check form-switch">
                                    <input type="checkbox" class="form-check-input" 
                                           id="visibility" name="visibility" value="1" 
                                           {{ old('visibility', $campaign->visibility) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visibility">Campagne visible publiquement</label>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title" style="color: #2d5a27 !important;">Statistiques</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Participants: {{ $campaign->participants_count }}</li>
                                            <li>Créée le: {{ $campaign->created_at->format('d/m/Y') }}</li>
                                            <li>Modifiée le: {{ $campaign->updated_at->format('d/m/Y') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-eco btn-sm">
                                    <i class="fas fa-save me-2"></i>Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: #f8f9fa !important;
        min-height: 30vh;
        display: flex;
        align-items: center;
    }
    
    /* Forcer la couleur verte sur tous les éléments du hero */
    .hero-section h1,
    .hero-section h1 i,
    .hero-section .lead,
    .hero-section .text-success {
        color: #2d5a27 !important;
    }
    
    /* Breadcrumb Navigation */
    .breadcrumb-nav .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-nav .breadcrumb-item a {
        color: #2d5a27;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-nav .breadcrumb-item a:hover {
        color: #4a7c59;
    }
    
    .breadcrumb-nav .breadcrumb-item.active {
        color: #6c757d;
        font-weight: 500;
    }
    
    /* Main Form Card */
    .campaign-form-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }
    
    .form-content {
        padding: 3rem 2rem;
    }
    
    /* Form Controls - TAILLE RÉDUITE */
    .form-control, .form-select {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        font-size: 12px !important;
        padding: 6px 10px !important;
        height: 35px !important;
    }
    
    textarea.form-control {
        height: auto !important;
        min-height: 120px !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #2d5a27;
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
        background-color: #fff;
    }
    
    .form-label {
        font-weight: 600;
        color: #2d5a27;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-label i {
        margin-right: 0.5rem;
    }
    
    /* Form Groups */
    .mb-4 {
        margin-bottom: 2rem !important;
    }
    
    .mb-3 {
        margin-bottom: 1.5rem !important;
    }
    
    /* Info Card */
    .card.bg-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
    }
    
    .card.bg-light .card-body {
        padding: 1.5rem;
    }
    
    .card.bg-light .card-title {
        color: #2d5a27;
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    /* Form Switch */
    .form-check-input:checked {
        background-color: #2d5a27;
        border-color: #2d5a27;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .form-check-label {
        font-weight: 600;
        color: #2d5a27;
        font-size: 0.85rem;
    }
    
    /* Form Actions */
    .form-actions {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid #e9ecef;
    }
    
    .btn-eco {
        background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }
    
    .btn-eco:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(45, 90, 39, 0.3);
        color: white;
    }
    
    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        background: transparent;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }
    
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 25vh;
            text-align: center;
        }
        
        .form-content {
            padding: 2rem 1.5rem;
        }
        
        .form-actions .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .form-actions .btn {
            width: 100%;
        }
    }
    
    /* Animations */
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
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Aperçu de l'image
        const imageInput = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (!preview) {
                    // Créer l'élément preview s'il n'existe pas
                            const previewDiv = imageInput.parentElement;
                    const newPreview = document.createElement('img');
                    newPreview.id = 'imagePreview';
                    newPreview.src = e.target.result;
                            newPreview.className = 'img-fluid rounded shadow-sm mt-3';
                            newPreview.style = 'max-height: 250px; width: 100%; object-fit: cover; display: block;';
                    previewDiv.appendChild(newPreview);
                } else {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            }
            reader.readAsDataURL(file);
        }
    });
        }

    // Validation des dates
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        if (startDate && endDate) {
            startDate.addEventListener('change', function() {
        if (this.value && endDate.value && this.value > endDate.value) {
            alert('La date de fin doit être après la date de début');
            endDate.value = this.value;
        }
        endDate.min = this.value;
            });
        }
        
        // Animation des formulaires
        const formControls = document.querySelectorAll('.form-control, .form-select');
        formControls.forEach(control => {
            control.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            control.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    });
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('campaignForm');
    if (!form) return;

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }

    function showError(input, message) {
        input.classList.add('is-invalid');
        const fb = document.createElement('div');
        fb.className = 'invalid-feedback';
        fb.textContent = message;
        if (input.parentElement) input.parentElement.appendChild(fb);
    }

    // validate on submit
    form.addEventListener('submit', function(e) {
        clearErrors();
        let hasError = false;

        const name = form.querySelector('[name="name"]');
        const start_date = form.querySelector('[name="start_date"]');
        const end_date = form.querySelector('[name="end_date"]');
        const category = form.querySelector('[name="category"]');
        const status = form.querySelector('[name="status"]');
        const goal = form.querySelector('[name="goal"]');
        const image = form.querySelector('[name="image"]');

        if (!name || !name.value.trim()) {
            showError(name || form, 'Le nom de la campagne est requis.');
            hasError = true;
        } else if (name.value.length > 255) {
            showError(name, 'Le nom ne peut pas dépasser 255 caractères.');
            hasError = true;
        }

        if (!category || !category.value) {
            showError(category || form, 'Veuillez choisir une catégorie pour votre campagne.');
            hasError = true;
        }

        if (!status || !status.value) {
            showError(status || form, 'Indiquez le statut de la campagne.');
            hasError = true;
        }

        const todayStr = new Date().toISOString().split('T')[0];
        if (!start_date || !start_date.value) {
            showError(start_date || form, 'Veuillez saisir la date de début.');
            hasError = true;
        } else if (start_date.value < todayStr) {
            showError(start_date, 'La date de début ne peut pas être antérieure à aujourd\'hui.');
            hasError = true;
        }

        if (!end_date || !end_date.value) {
            showError(end_date || form, 'Veuillez saisir la date de fin.');
            hasError = true;
        }

        if (start_date && end_date && start_date.value && end_date.value && start_date.value > end_date.value) {
            showError(end_date, 'La date de fin doit être après la date de début.');
            hasError = true;
        }

        if (goal && goal.value) {
            const n = Number(goal.value);
            if (isNaN(n) || n < 0) {
                showError(goal, 'L\'objectif doit être un nombre positif.');
                hasError = true;
            }
        }

        if (image && image.files && image.files[0]) {
            const file = image.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                showError(image, 'L\'image doit faire moins de 2MB.');
                hasError = true;
            }
        }

        if (hasError) {
            e.preventDefault();
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
        }
    });

    // validate fields on blur and show custom messages
    ['name','category','status','start_date','end_date'].forEach(function(fieldName) {
        const field = form.querySelector('[name="' + fieldName + '"]');
        if (!field) return;
        field.addEventListener('blur', function() {
            clearErrors();
            if (!field.value || !String(field.value).trim()) {
                showError(field, 'Ce champ est obligatoire.');
            } else if (fieldName === 'start_date') {
                const todayStr = new Date().toISOString().split('T')[0];
                if (field.value < todayStr) showError(field, 'La date de début ne peut pas être antérieure à aujourd\'hui.');
            }
        });
    });
});
</script>
@endpush