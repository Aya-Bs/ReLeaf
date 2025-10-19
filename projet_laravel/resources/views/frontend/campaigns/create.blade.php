@extends('layouts.frontend')

@section('title', 'Créer une Campagne')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-3" style="color: #000000 !important;">
                    <i class="fas fa-plus-circle me-2"></i>Créer une <span class="text-success">Nouvelle Campagne</span>
                </h1>
                <p class="lead" style="color: #000000 !important; font-weight: 600;">
                    Lancez votre campagne écologique et mobilisez votre communauté
                </p>
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
                            <li class="breadcrumb-item active" aria-current="page">Créer une campagne</li>
                        </ol>
                    </nav>
                </div>
                </div>

            <!-- Main Form Card -->
            <div class="campaign-form-card">
                <div class="form-content">
                    <form id="campaignForm" novalidate action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-tag me-2 text-eco"></i>Nom de la campagne *
                                    </label>
                     <input type="text" class="form-control @error('name') is-invalid @enderror" 
                         id="name" name="name" value="{{ old('name') }}" 
                         placeholder="ex: Nettoyage des plages de Marseille">
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
                                              placeholder="Décrivez votre campagne, ses objectifs et son impact environnemental...">{{ old('description') }}</textarea>
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
                            id="category" name="category">
                                                <option value="">🌱 Sélectionnez une catégorie</option>
                                                @foreach(['reforestation' => '🌲 Reforestation', 'nettoyage' => '🧹 Nettoyage', 'sensibilisation' => '📢 Sensibilisation', 'recyclage' => '♻️ Recyclage', 'biodiversite' => '🦋 Biodiversité', 'energie_renouvelable' => '⚡ Énergie Renouvelable', 'autre' => '🔧 Autre'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
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
                            id="status" name="status">
                                                @foreach(['active' => '🟢 Active', 'inactive' => '🔴 Inactive', 'completed' => '✅ Terminée', 'cancelled' => '❌ Annulée'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
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
                              id="start_date" name="start_date" value="{{ old('start_date') }}">
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
                              id="end_date" name="end_date" value="{{ old('end_date') }}">
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="goal" class="form-label fw-bold">
                                                <i class="fas fa-euro-sign me-2 text-eco"></i>Objectif financier (€)
                                            </label>
                                            <input type="number" step="0.01" class="form-control @error('goal') is-invalid @enderror" 
                                                   id="goal" name="goal" value="{{ old('goal') }}"
                                                   placeholder="0.00">
                                            @error('goal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="environmental_impact" class="form-label fw-bold">
                                                <i class="fas fa-globe-europe me-2 text-eco"></i>Impact environnemental
                                            </label>
                                            <input type="text" class="form-control @error('environmental_impact') is-invalid @enderror" 
                                                   id="environmental_impact" name="environmental_impact" value="{{ old('environmental_impact') }}"
                                                   placeholder="ex: Réduction de 50kg de déchets">
                                            @error('environmental_impact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="tags" class="form-label fw-bold">
                                        <i class="fas fa-tags me-2 text-eco"></i>Tags (séparés par des virgules)
                                    </label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags') }}" 
                                           placeholder="ex: écologie, déchets, recyclage, plage, environnement">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="image" class="form-label fw-bold">
                                        <i class="fas fa-image me-2 text-eco"></i>Image de la campagne
                                    </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <img id="imagePreview" src="#" alt="Aperçu de l'image" 
                                             class="img-fluid rounded shadow-sm" style="display: none; max-height: 250px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" 
                                           id="visibility" name="visibility" value="1" 
                                           {{ old('visibility', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="visibility">
                                            <i class="fas fa-eye me-2 text-eco"></i>Campagne visible publiquement
                                        </label>
                                    </div>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-info-circle"></i> Informations</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Les champs marqués d'un * sont obligatoires</li>
                                            <li>L'image doit être au format JPG, PNG ou WebP</li>
                                            <li>Taille maximale : 2MB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-eco btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Créer la campagne
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
        background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
        min-height: 50vh;
        display: flex;
        align-items: center;
    }
    
    /* Breadcrumb Navigation */
    .breadcrumb-nav .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-nav .breadcrumb-item a {
        color: var(--eco-green);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-nav .breadcrumb-item a:hover {
        color: var(--eco-light-green);
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
    
    .form-header {
        background: linear-gradient(135deg, var(--eco-green) 0%, var(--eco-light-green) 100%);
        color: white;
        padding: 2.5rem 2rem;
        text-align: center;
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .form-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }
    
    .form-content {
        padding: 3rem 2rem;
    }
    
    /* Form Controls - Normal size text */
    .form-control, .form-select {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        font-size: 1rem;
        padding: 0.75rem 1rem;
        height: auto;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--eco-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
        background-color: #fff;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--eco-green);
        margin-bottom: 0.75rem;
        font-size: 1rem;
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
    
    /* Image Preview */
    #imagePreview {
        border-radius: 1rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 2px solid #e9ecef;
    }
    
    /* Info Card */
    .eco-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
    }
    
    .eco-card .card-header {
        background: rgba(45, 90, 39, 0.1);
        border-bottom: 1px solid #e9ecef;
        border-radius: 1rem 1rem 0 0 !important;
    }
    
    .eco-card .card-header .card-title {
        color: var(--eco-green);
        font-weight: 600;
        margin: 0;
    }
    
    /* Form Switch */
    .form-check-input:checked {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    .form-check-label {
        font-weight: 600;
        color: var(--eco-green);
    }
    
    /* Form Actions */
    .form-actions {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid #e9ecef;
    }
    
    .btn-eco {
        background: linear-gradient(135deg, var(--eco-green) 0%, var(--eco-light-green) 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .btn-eco:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(45, 90, 39, 0.3);
        color: white;
    }
    
    .btn-outline-eco {
        border: 2px solid var(--eco-green);
        color: var(--eco-green);
        background: transparent;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .btn-outline-eco:hover {
        background: var(--eco-green);
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        background: transparent;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Improved visibility of title and subtitle - FORCE BLACK COLOR */
    .hero-section h1 {
        font-size: 3rem;
        font-weight: 800;
        color: #000000 !important;
        text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);
    }

    .hero-section p.lead {
        font-size: 1.5rem;
        color: #000000 !important;
        text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
        font-weight: 600;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 40vh;
            text-align: center;
        }
        
        .hero-section h1 {
            font-size: 2rem;
            color: #000000 !important;
        }
        
        .hero-section p {
            font-size: 1.2rem;
            color: #000000 !important;
        }
        
        .form-header {
            padding: 2rem 1.5rem;
        }
        
        .form-title {
            font-size: 1.5rem;
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
        
        if (imageInput && preview) {
            imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
        }

    // Validation des dates
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        if (startDate && endDate) {
            startDate.addEventListener('change', function() {
        if (this.value && endDate.value && this.value > endDate.value) {
            endDate.value = '';
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

    // validate on blur for required fields
    ['name','category','status','start_date','end_date'].forEach(function(fieldName) {
        const field = form.querySelector('[name="' + fieldName + '"]');
        if (!field) return;
        field.addEventListener('blur', function() {
            // clear only this field errors
            if (field.classList.contains('is-invalid')) {
                field.classList.remove('is-invalid');
                const fb = field.parentElement && field.parentElement.querySelector('.invalid-feedback');
                if (fb) fb.remove();
            }
            if (!field.value || !String(field.value).trim()) {
                showError(field, 'Ce champ est obligatoire.');
                return;
            }
            if (fieldName === 'start_date') {
                const todayStr = new Date().toISOString().split('T')[0];
                if (field.value < todayStr) {
                    showError(field, 'La date de début ne peut pas être antérieure à aujourd\'hui.');
                }
            }
        });
    });
});
</script>
@endpush