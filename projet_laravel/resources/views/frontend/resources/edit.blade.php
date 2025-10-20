@extends('layouts.frontend')

@section('title', 'Modifier la Ressource: ' . $resource->name)

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold mb-3" style="color: #2d5a27 !important;">
                    <i class="fas fa-edit me-2" style="color: #2d5a27 !important;"></i>Modifier la <span class="text-success" style="color: #4a7c59 !important;">Ressource</span>
                </h1>
                <p class="lead" style="color: #2d5a27 !important; font-weight: 600;">{{ $resource->name }}</p>
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
                            <li class="breadcrumb-item">
                                <a href="{{ route('resources.show', $resource) }}" class="text-eco">
                                    {{ Str::limit($resource->name, 20) }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="resource-form-card">
                <div class="form-content">
                    <form id="resourceForm" novalidate action="{{ route('resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de la ressource *</label>
                     <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                         id="name" name="name" value="{{ old('name', $resource->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $resource->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="campaign_id" class="form-label">Campagne associée *</label>
                        <select class="form-control form-control-sm @error('campaign_id') is-invalid @enderror" 
                            id="campaign_id" name="campaign_id">
                                                <option value="">Sélectionnez une campagne</option>
                                                @foreach($campaigns as $campaign)
                                                    <option value="{{ $campaign->id }}" {{ old('campaign_id', $resource->campaign_id) == $campaign->id ? 'selected' : '' }}>
                                                        {{ $campaign->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('campaign_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="provider" class="form-label">Fournisseur</label>
                                            <input type="text" class="form-control form-control-sm @error('provider') is-invalid @enderror" 
                                                   id="provider" name="provider" value="{{ old('provider', $resource->provider) }}">
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
                          <input type="number" class="form-control form-control-sm @error('quantity_needed') is-invalid @enderror" 
                              id="quantity_needed" name="quantity_needed" 
                              value="{{ old('quantity_needed', $resource->quantity_needed) }}" min="1">
                                            @error('quantity_needed')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="quantity_pledged" class="form-label">Quantité promise</label>
                                            <input type="number" class="form-control form-control-sm @error('quantity_pledged') is-invalid @enderror" 
                                                   id="quantity_pledged" name="quantity_pledged" 
                                                   value="{{ old('quantity_pledged', $resource->quantity_pledged) }}" min="0">
                                            @error('quantity_pledged')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="unit" class="form-label">Unité *</label>
                          <input type="text" class="form-control form-control-sm @error('unit') is-invalid @enderror" 
                              id="unit" name="unit" value="{{ old('unit', $resource->unit) }}">
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
                        <select class="form-control form-control-sm @error('resource_type') is-invalid @enderror" 
                            id="resource_type" name="resource_type">
                                                @foreach(['money' => 'Argent', 'food' => 'Nourriture', 'clothing' => 'Vêtements', 'medical' => 'Médical', 'equipment' => 'Équipement', 'human' => 'Main d\'œuvre', 'other' => 'Autre'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('resource_type', $resource->resource_type) == $value ? 'selected' : '' }}>
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
                        <select class="form-control form-control-sm @error('category') is-invalid @enderror" 
                            id="category" name="category">
                                                @foreach(['materiel' => 'Matériel', 'financier' => 'Financier', 'humain' => 'Humain', 'technique' => 'Technique'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('category', $resource->category) == $value ? 'selected' : '' }}>
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
                    <select class="form-control form-control-sm @error('priority') is-invalid @enderror" 
                        id="priority" name="priority">
                                        @foreach(['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('priority', $resource->priority) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut *</label>
                    <select class="form-control form-control-sm @error('status') is-invalid @enderror" 
                        id="status" name="status">
                                        @foreach(['needed' => 'Nécessaire', 'pledged' => 'Promis', 'received' => 'Reçu', 'in_use' => 'En utilisation'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $resource->status) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Image actuelle</label>
                                    @if($resource->image_url)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($resource->image_url) }}" 
                                                 alt="{{ $resource->name }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 150px; width: 100%; object-fit: cover;">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" 
                                                       id="remove_image" name="remove_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_image">
                                                    Supprimer l'image
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-muted mb-2">
                                            <i class="fas fa-image fa-2x"></i>
                                            <p>Aucune image</p>
                                        </div>
                                    @endif
                                    
                                    <label for="image" class="form-label">Changer l'image</label>
                                    <input type="file" class="form-control form-control-sm @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title" style="color: #2d5a27 !important;">Statistiques</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Progression: {{ $resource->progress_percentage }}%</li>
                                            <li>Manquant: {{ $resource->missing_quantity }} {{ $resource->unit }}</li>
                                            <li>Créée le: {{ $resource->created_at->format('d/m/Y') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes supplémentaires</label>
                            <textarea class="form-control form-control-sm @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes', $resource->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('resources.index') }}" class="btn btn-outline-secondary btn-sm">
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
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
        min-height: 50vh;
        display: flex;
        align-items: center;
        border-bottom: 3px solid #2d5a27;
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
    
    /* Form Controls */
    .form-control {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
    
    .form-control-sm {
        font-size: 0.8rem;
        padding: 0.4rem 0.75rem;
    }
    
    .form-control:focus {
        border-color: var(--eco-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
        background-color: #fff;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--eco-green);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    /* Form Groups */
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
        color: var(--eco-green);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 0.9rem;
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
        font-size: 0.85rem;
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
            min-height: 40vh;
            text-align: center;
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
    
    /* Styles spécifiques pour forcer la couleur verte */
    .force-green {
        color: #2d5a27 !important;
    }
    
    .hero-title {
        color: #2d5a27 !important;
        text-shadow: none !important;
    }
    
    .hero-subtitle {
        color: #4a7c59 !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('resourceForm');
    if (!form) return;

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }

    function showError(input, message) {
        if (!input) return;
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
        const campaign_id = form.querySelector('[name="campaign_id"]');
        const quantity_needed = form.querySelector('[name="quantity_needed"]');
        const quantity_pledged = form.querySelector('[name="quantity_pledged"]');
        const unit = form.querySelector('[name="unit"]');
        const resource_type = form.querySelector('[name="resource_type"]');
        const category = form.querySelector('[name="category"]');
        const priority = form.querySelector('[name="priority"]');
        const status = form.querySelector('[name="status"]');
        const image = form.querySelector('[name="image"]');

        if (!name || !name.value.trim()) {
            showError(name || form, 'Le nom de la ressource est requis.');
            hasError = true;
        }

        if (!campaign_id || !campaign_id.value) {
            showError(campaign_id || form, 'Veuillez sélectionner une campagne.');
            hasError = true;
        }

        if (!quantity_needed || !quantity_needed.value || Number(quantity_needed.value) < 1) {
            showError(quantity_needed || form, 'La quantité nécessaire doit être au moins 1.');
            hasError = true;
        }

        if (quantity_pledged && quantity_pledged.value && Number(quantity_pledged.value) < 0) {
            showError(quantity_pledged, 'La quantité promise doit être positive.');
            hasError = true;
        }

        if (!unit || !unit.value.trim()) {
            showError(unit || form, 'L\'unité est requise.');
            hasError = true;
        }

        if (!resource_type || !resource_type.value) {
            showError(resource_type || form, 'Le type de ressource est requis.');
            hasError = true;
        }

        if (!category || !category.value) {
            showError(category || form, 'La catégorie est requise.');
            hasError = true;
        }

        if (!priority || !priority.value) {
            showError(priority || form, 'La priorité est requise.');
            hasError = true;
        }

        if (!status || !status.value) {
            showError(status || form, 'Le statut est requis.');
            hasError = true;
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
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu de l'image
    const imageInput = document.getElementById('image');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
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

    // Calcul automatique du statut
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
    }
});
</script>
@endpush