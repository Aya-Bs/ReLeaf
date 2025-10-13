@extends('layouts.frontend')

@section('title', 'Créer une Ressource')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-3" style="color: var(--eco-green) !important;">
                    <i class="fas fa-plus-circle me-2"></i>Créer une <span class="text-success">Nouvelle Ressource</span>
                </h1>
                <p class="lead" style="color: var(--eco-green) !important; font-weight: 600;">
                    Ajoutez une ressource nécessaire à votre campagne écologique
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
                                <a href="{{ route('resources.index') }}" class="text-eco">
                                    <i class="fas fa-boxes me-1"></i>Ressources
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Créer une ressource</li>
                        </ol>
                    </nav>
                </div>
                </div>

            <!-- Main Form Card -->
            <div class="resource-form-card">
                <div class="form-content">
                    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-tag me-2 text-eco"></i>Nom de la ressource *
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="ex: Plants d'arbres, Gants de protection..." required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-2 text-eco"></i>Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3"
                                              placeholder="Description détaillée de la ressource...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="campaign_id" class="form-label fw-bold">
                                                <i class="fas fa-leaf me-2 text-eco"></i>Campagne associée *
                                            </label>
                                            <select class="form-select @error('campaign_id') is-invalid @enderror" 
                                                    id="campaign_id" name="campaign_id" required>
                                                <option value="">🌱 Sélectionnez une campagne</option>
                                                @foreach($campaigns as $campaign)
                                                    <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                                        {{ $campaign->name }} ({{ $campaign->category }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('campaign_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="provider" class="form-label fw-bold">
                                                <i class="fas fa-user me-2 text-eco"></i>Fournisseur
                                            </label>
                                            <input type="text" class="form-control @error('provider') is-invalid @enderror" 
                                                   id="provider" name="provider" value="{{ old('provider') }}"
                                                   placeholder="ex: Entreprise XYZ, Donateur...">
                                            @error('provider')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="quantity_needed" class="form-label">Quantité nécessaire *</label>
                                            <input type="number" class="form-control @error('quantity_needed') is-invalid @enderror" 
                                                   id="quantity_needed" name="quantity_needed" 
                                                   value="{{ old('quantity_needed', 1) }}" min="1" required>
                                            @error('quantity_needed')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="quantity_pledged" class="form-label">Quantité promise</label>
                                            <input type="number" class="form-control @error('quantity_pledged') is-invalid @enderror" 
                                                   id="quantity_pledged" name="quantity_pledged" 
                                                   value="{{ old('quantity_pledged', 0) }}" min="0">
                                            @error('quantity_pledged')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="unit" class="form-label">Unité *</label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                                   id="unit" name="unit" value="{{ old('unit', 'unité') }}"
                                                   placeholder="ex: kg, litre, pièce..." required>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="resource_type" class="form-label">Type de ressource *</label>
                                            <select class="form-control @error('resource_type') is-invalid @enderror" 
                                                    id="resource_type" name="resource_type" required>
                                                <option value="">Sélectionnez un type</option>
                                                @foreach(['money' => 'Argent', 'food' => 'Nourriture', 'clothing' => 'Vêtements', 'medical' => 'Médical', 'equipment' => 'Équipement', 'human' => 'Main d\'œuvre', 'other' => 'Autre'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('resource_type') == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('resource_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Catégorie *</label>
                                            <select class="form-control @error('category') is-invalid @enderror" 
                                                    id="category" name="category" required>
                                                @foreach(['materiel' => 'Matériel', 'financier' => 'Financier', 'humain' => 'Humain', 'technique' => 'Technique'] as $value => $label)
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
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priorité *</label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        @foreach(['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('priority', 'medium') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut initial *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        @foreach(['needed' => 'Nécessaire', 'pledged' => 'Promis', 'received' => 'Reçu', 'in_use' => 'En utilisation'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', 'needed') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Image de la ressource</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="imagePreview" src="#" alt="Aperçu de l'image" 
                                             class="img-fluid rounded" style="display: none; max-height: 200px;">
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

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes supplémentaires</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2"
                                      placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('resources.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-eco btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Créer la ressource
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
    .resource-form-card {
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
        color: var(--eco-green);
        font-weight: 600;
        margin-bottom: 1rem;
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
    
    /* Improved visibility of title and subtitle - GREEN COLOR */
    .hero-section h1 {
        font-size: 3rem;
        font-weight: 800;
        color: var(--eco-green) !important;
        text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.8);
    }

    .hero-section p.lead {
        font-size: 1.5rem;
        color: var(--eco-green) !important;
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
            color: var(--eco-green) !important;
        }
        
        .hero-section p {
            font-size: 1.2rem;
            color: var(--eco-green) !important;
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
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }

    // Calcul automatique du statut basé sur les quantités
    const quantityNeeded = document.getElementById('quantity_needed');
    const quantityPledged = document.getElementById('quantity_pledged');
    const statusSelect = document.getElementById('status');

    function updateStatus() {
        const needed = parseInt(quantityNeeded.value) || 0;
        const pledged = parseInt(quantityPledged.value) || 0;

        if (needed > 0 && pledged >= needed) {
            statusSelect.value = 'received';
        } else if (pledged > 0) {
            statusSelect.value = 'pledged';
        } else {
            statusSelect.value = 'needed';
        }
    }

    if (quantityNeeded && quantityPledged && statusSelect) {
        quantityNeeded.addEventListener('change', updateStatus);
        quantityPledged.addEventListener('change', updateStatus);
        quantityNeeded.addEventListener('input', updateStatus);
        quantityPledged.addEventListener('input', updateStatus);
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