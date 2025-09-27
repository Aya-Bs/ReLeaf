@extends('layouts.frontend')

@section('title', $resource->name)

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-box me-3"></i>{{ $resource->name }}
                </h1>
                <p class="lead mb-4">
                    {{ $resource->description ?? 'Ressource pour campagne écologique' }}
                </p>
                <div class="d-flex gap-3">
                    <span class="badge bg-success fs-6">
                        @switch($resource->resource_type)
                            @case('money') 💰 Argent @break
                            @case('food') 🍎 Nourriture @break
                            @case('clothing') 👕 Vêtements @break
                            @case('medical') 🏥 Médical @break
                            @case('equipment') 🛠️ Équipement @break
                            @case('human') 👥 Main d'œuvre @break
                            @default 🔧 {{ ucfirst($resource->resource_type) }} @break
                        @endswitch
                    </span>
                    <span class="badge bg-{{ $resource->status == 'received' ? 'success' : ($resource->status == 'pledged' ? 'info' : 'secondary') }} fs-6">
                        @switch($resource->status)
                            @case('received') ✅ Reçu @break
                            @case('pledged') 📋 Promis @break
                            @case('needed') ⏳ Nécessaire @break
                            @default 🔄 {{ ucfirst($resource->status) }} @break
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('resources.edit', $resource) }}" class="btn btn-warning btn-lg me-2">
                    <i class="fas fa-edit me-2"></i> Modifier
                </a>
                <a href="{{ route('resources.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                        </a>
                    </div>
                </div>
    </div>
</section>

<div class="container my-5">
                    <div class="row">
        <div class="col-lg-8">
            <!-- Resource Details Card -->
            <div class="resource-detail-card mb-4">
                <div class="resource-header-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="resource-header-info">
                                <h2 class="resource-detail-title">{{ $resource->name }}</h2>
                                <div class="resource-badges">
                                    <span class="resource-type-badge">
                                        @switch($resource->resource_type)
                                            @case('money') 💰 Argent @break
                                            @case('food') 🍎 Nourriture @break
                                            @case('clothing') 👕 Vêtements @break
                                            @case('medical') 🏥 Médical @break
                                            @case('equipment') 🛠️ Équipement @break
                                            @case('human') 👥 Main d'œuvre @break
                                            @default 🔧 {{ ucfirst($resource->resource_type) }} @break
                                        @endswitch
                                    </span>
                                    <span class="status-badge status-{{ $resource->status }}">
                                        @switch($resource->status)
                                            @case('received') ✅ Reçu @break
                                            @case('pledged') 📋 Promis @break
                                            @case('needed') ⏳ Nécessaire @break
                                            @default 🔄 {{ ucfirst($resource->status) }} @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="resource-actions-header">
                                <a href="{{ route('resources.edit', $resource) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="resource-content-section">
                            <div class="row">
                        <!-- Progress Visualization -->
                        <div class="col-12 mb-4">
                            <div class="progress-visualization">
                                <div class="progress-header-large">
                                    <h4 class="progress-title">
                                        <i class="fas fa-chart-pie me-2"></i>Progression de la collecte
                                    </h4>
                                    <div class="progress-percentage-large">{{ $resource->progress_percentage }}%</div>
                                </div>
                                <div class="progress-bar-large">
                                    <div class="progress-fill progress-{{ $resource->progress_percentage == 100 ? 'success' : ($resource->progress_percentage > 50 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $resource->progress_percentage }}%"></div>
                                </div>
                                <div class="progress-stats-large">
                                    <div class="stat-card">
                                        <div class="stat-icon"><i class="fas fa-target text-eco"></i></div>
                                        <div class="stat-content">
                                            <div class="stat-number">{{ $resource->quantity_needed }}</div>
                                            <div class="stat-label">Nécessaire</div>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon"><i class="fas fa-check-circle text-success"></i></div>
                                        <div class="stat-content">
                                            <div class="stat-number">{{ $resource->quantity_pledged }}</div>
                                            <div class="stat-label">Collecté</div>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon"><i class="fas fa-exclamation-triangle text-warning"></i></div>
                                        <div class="stat-content">
                                            <div class="stat-number">{{ $resource->missing_quantity }}</div>
                                            <div class="stat-label">Manquant</div>
                                        </div>
                                    </div>
                                    <div class="stat-unit-large">{{ $resource->unit }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resource Information -->
                                <div class="col-md-6">
                            <div class="info-section">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>Informations générales
                                </h5>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-tag me-2"></i>Nom
                                        </div>
                                        <div class="info-value">{{ $resource->name }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-leaf me-2"></i>Campagne
                                        </div>
                                        <div class="info-value">
                                                <a href="{{ route('campaigns.show', $resource->campaign) }}" 
                                               class="campaign-link-detail">
                                                    {{ $resource->campaign->name }}
                                                </a>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-user me-2"></i>Fournisseur
                                        </div>
                                        <div class="info-value">{{ $resource->provider ?? 'Non spécifié' }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-ruler me-2"></i>Unité
                                        </div>
                                        <div class="info-value">{{ $resource->unit }}</div>
                                    </div>
                                </div>
                                </div>
                            </div>

                        <!-- Status & Priority -->
                        <div class="col-md-6">
                            <div class="info-section">
                                <h5 class="section-title">
                                    <i class="fas fa-chart-line me-2"></i>Statut et priorité
                                </h5>
                                <div class="status-priority-grid">
                                    <div class="status-card status-{{ $resource->status }}">
                                        <div class="status-icon">
                                            @switch($resource->status)
                                                @case('received') <i class="fas fa-check-circle"></i> @break
                                                @case('pledged') <i class="fas fa-handshake"></i> @break
                                                @case('needed') <i class="fas fa-clock"></i> @break
                                                @default <i class="fas fa-sync-alt"></i> @break
                                            @endswitch
                            </div>
                                        <div class="status-content">
                                            <div class="status-label">Statut</div>
                                            <div class="status-value">{{ ucfirst($resource->status) }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="priority-card priority-{{ $resource->priority }}">
                                        <div class="priority-icon">
                                            @switch($resource->priority)
                                                @case('urgent') <i class="fas fa-exclamation-triangle"></i> @break
                                                @case('high') <i class="fas fa-arrow-up"></i> @break
                                                @case('medium') <i class="fas fa-minus"></i> @break
                                                @default <i class="fas fa-arrow-down"></i> @break
                                            @endswitch
                                        </div>
                                        <div class="priority-content">
                                            <div class="priority-label">Priorité</div>
                                            <div class="priority-value">{{ ucfirst($resource->priority) }}</div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- Description and Notes -->
                    @if($resource->description || $resource->notes)
                    <div class="col-12">
                        <div class="content-section">
                            @if($resource->description)
                            <div class="description-card">
                                <h5 class="section-title">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </h5>
                                <div class="description-content">
                                    {{ $resource->description }}
                                </div>
                                </div>
                            @endif

                            @if($resource->notes)
                            <div class="notes-card">
                                <h5 class="section-title">
                                    <i class="fas fa-sticky-note me-2"></i>Notes supplémentaires
                                </h5>
                                <div class="notes-content">
                                    <i class="fas fa-info-circle me-2 text-warning"></i>{{ $resource->notes }}
                                </div>
                            </div>
                            @endif
                        </div>
                                </div>
                                        @endif
                </div>
                                </div>
                            </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Resource Image -->
            @if($resource->image_url)
            <div class="resource-image-card mb-4">
                <img src="{{ Storage::url($resource->image_url) }}" 
                     alt="{{ $resource->name }}"
                     class="resource-detail-image"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="resource-detail-image-placeholder" style="display: none;">
                    @switch($resource->resource_type)
                        @case('money') <i class="fas fa-money-bill-wave fa-4x"></i> @break
                        @case('food') <i class="fas fa-apple-alt fa-4x"></i> @break
                        @case('clothing') <i class="fas fa-tshirt fa-4x"></i> @break
                        @case('medical') <i class="fas fa-medkit fa-4x"></i> @break
                        @case('equipment') <i class="fas fa-tools fa-4x"></i> @break
                        @case('human') <i class="fas fa-users fa-4x"></i> @break
                        @default <i class="fas fa-box fa-4x"></i> @break
                    @endswitch
                    <p class="text-muted mt-2">Image non disponible</p>
                </div>
            </div>
            @else
            <div class="resource-image-card mb-4">
                <div class="resource-detail-image-placeholder">
                    @switch($resource->resource_type)
                        @case('money') <i class="fas fa-money-bill-wave fa-4x"></i> @break
                        @case('food') <i class="fas fa-apple-alt fa-4x"></i> @break
                        @case('clothing') <i class="fas fa-tshirt fa-4x"></i> @break
                        @case('medical') <i class="fas fa-medkit fa-4x"></i> @break
                        @case('equipment') <i class="fas fa-tools fa-4x"></i> @break
                        @case('human') <i class="fas fa-users fa-4x"></i> @break
                        @default <i class="fas fa-box fa-4x"></i> @break
                    @endswitch
                    <p class="text-muted mt-2">Aucune image</p>
                </div>
            </div>
            @endif
            
            <!-- Quick Actions -->
            <div class="quick-actions-card mb-4">
                                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h5>
                                </div>
                                <div class="card-body">
                    <!-- Add Pledge Form -->
                    <form action="{{ route('resources.pledge', $resource) }}" method="POST" class="mb-4">
                                        @csrf
                        <div class="form-group">
                            <label class="form-label fw-bold">
                                <i class="fas fa-hand-holding-heart me-2 text-eco"></i>Ajouter une promesse
                            </label>
                                        <div class="input-group">
                                <input type="number" name="quantity" class="form-control form-control-lg" 
                                                   placeholder="Quantité" min="1" required>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                            <input type="text" name="provider" class="form-control form-control-lg mt-2" 
                                               placeholder="Fournisseur (optionnel)">
                        </div>
                                    </form>

                    <!-- Status Change Form -->
                                    <form action="{{ route('resources.update-status', $resource) }}" method="POST">
                                        @csrf
                        <div class="form-group">
                            <label class="form-label fw-bold">
                                <i class="fas fa-sync-alt me-2 text-eco"></i>Changer le statut
                            </label>
                                        <div class="input-group">
                                <select name="status" class="form-select form-select-lg" required>
                                    @foreach(['needed' => '⏳ Nécessaire', 'pledged' => '📋 Promis', 'received' => '✅ Reçu', 'in_use' => '🔄 Utilisé'] as $value => $label)
                                                    <option value="{{ $value }}" {{ $resource->status == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                <button type="submit" class="btn btn-eco btn-lg">
                                    <i class="fas fa-check"></i>
                                            </button>
                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

            <!-- Metadata -->
            <div class="metadata-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Métadonnées
                    </h5>
                </div>
                                <div class="card-body">
                    <div class="metadata-list">
                        <div class="metadata-item">
                            <i class="fas fa-calendar-plus me-2 text-eco"></i>
                            <span class="metadata-label">Créée le:</span>
                            <span class="metadata-value">{{ $resource->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="metadata-item">
                            <i class="fas fa-calendar-check me-2 text-eco"></i>
                            <span class="metadata-label">Modifiée le:</span>
                            <span class="metadata-value">{{ $resource->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                                        @if($resource->provider)
                        <div class="metadata-item">
                            <i class="fas fa-user me-2 text-eco"></i>
                            <span class="metadata-label">Fournisseur:</span>
                            <span class="metadata-value">{{ $resource->provider }}</span>
                        </div>
                                        @endif
                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        min-height: 60vh;
        display: flex;
        align-items: center;
    }
    
    /* Resource Detail Card */
    .resource-detail-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    
    .resource-header-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 2rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .resource-detail-title {
        color: var(--eco-green);
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 2rem;
    }
    
    .resource-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .resource-type-badge {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .status-needed { background: linear-gradient(135deg, #6c757d, #545b62); color: white; }
    .status-pledged { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .status-received { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    .status-in_use { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
    
    .resource-content-section {
        padding: 2rem;
    }
    
    /* Progress Visualization */
    .progress-visualization {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 2rem;
        border-radius: 1rem;
        border: 1px solid #e9ecef;
        margin-bottom: 2rem;
    }
    
    .progress-header-large {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .progress-title {
        color: var(--eco-green);
        font-weight: 700;
        margin: 0;
    }
    
    .progress-percentage-large {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--eco-green);
    }
    
    .progress-bar-large {
        height: 20px;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }
    
    .progress-success { background: linear-gradient(135deg, #28a745, #20c997); }
    .progress-warning { background: linear-gradient(135deg, #ffc107, #fd7e14); }
    .progress-danger { background: linear-gradient(135deg, #dc3545, #e83e8c); }
    
    .progress-stats-large {
        display: flex;
        justify-content: space-around;
        align-items: center;
        position: relative;
    }
    
    .stat-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--eco-green), var(--eco-light-green));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--eco-green);
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 600;
    }
    
    .stat-unit-large {
        position: absolute;
        top: -0.5rem;
        right: -1rem;
        background: var(--eco-green);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    /* Info Sections */
    .info-section {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        height: 100%;
    }
    
    .section-title {
        color: var(--eco-green);
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.125rem;
    }
    
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--eco-green);
    }
    
    .info-value {
        font-size: 1rem;
        color: #495057;
        font-weight: 500;
    }
    
    .campaign-link-detail {
        color: var(--eco-green);
        text-decoration: none;
        font-weight: 600;
    }
    
    .campaign-link-detail:hover {
        color: var(--eco-light-green);
        text-decoration: underline;
    }
    
    /* Status & Priority Grid */
    .status-priority-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .status-card, .priority-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e9ecef;
    }
    
    .status-card {
        background: rgba(45, 90, 39, 0.05);
        border-left: 4px solid var(--eco-green);
    }
    
    .priority-card {
        background: rgba(255, 193, 7, 0.05);
        border-left: 4px solid #ffc107;
    }
    
    .status-icon, .priority-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .status-icon {
        background: linear-gradient(135deg, var(--eco-green), var(--eco-light-green));
    }
    
    .priority-icon {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
    }
    
    .status-label, .priority-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .status-value, .priority-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--eco-green);
    }
    
    /* Content Sections */
    .content-section {
        margin-top: 2rem;
    }
    
    .description-card, .notes-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        margin-bottom: 1rem;
    }
    
    .notes-card {
        border-left: 4px solid #ffc107;
        background: rgba(255, 193, 7, 0.05);
    }
    
    .description-content, .notes-content {
        color: #495057;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    /* Sidebar Cards */
    .resource-image-card, .quick-actions-card, .metadata-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    
    .resource-detail-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 1rem;
        display: block;
        background: #f8f9fa;
    }
    
    .resource-detail-image-placeholder {
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
        color: var(--eco-green);
    }
    
    .quick-actions-card .card-header {
        background: linear-gradient(135deg, var(--eco-green), var(--eco-light-green));
        color: white;
        border: none;
        padding: 1rem 1.5rem;
    }
    
    .metadata-card .card-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: none;
        padding: 1rem 1.5rem;
    }
    
    .metadata-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .metadata-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: rgba(45, 90, 39, 0.05);
        border-radius: 0.5rem;
        border-left: 3px solid var(--eco-green);
    }
    
    .metadata-label {
        font-weight: 600;
        color: var(--eco-green);
        min-width: 80px;
    }
    
    .metadata-value {
        color: #495057;
        font-weight: 500;
    }
    
    /* Form Styling */
    .form-control-lg, .form-select-lg {
        border-radius: 0.5rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: var(--eco-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 40vh;
            text-align: center;
        }
        
        .resource-detail-title {
            font-size: 1.5rem;
        }
        
        .progress-stats-large {
            flex-direction: column;
            gap: 1rem;
        }
        
        .stat-unit-large {
            position: static;
            margin-top: 1rem;
        }
        
        .progress-header-large {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .resource-header-section {
            padding: 1.5rem;
        }
        
        .resource-content-section {
            padding: 1.5rem;
        }
    }
</style>
@endpush