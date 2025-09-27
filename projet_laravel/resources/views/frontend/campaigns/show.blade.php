@extends('layouts.frontend')

@section('title', $campaign->name)

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-leaf me-3"></i>{{ $campaign->name }}
                </h1>
                <p class="lead mb-4">
                    {{ $campaign->description ?? 'Campagne écologique pour un avenir durable' }}
                </p>
                <div class="d-flex gap-3">
                    <span class="badge bg-success fs-6">
                        @switch($campaign->category)
                            @case('reforestation') 🌲 Reforestation @break
                            @case('nettoyage') 🧹 Nettoyage @break
                            @case('sensibilisation') 📢 Sensibilisation @break
                            @case('recyclage') ♻️ Recyclage @break
                            @case('biodiversite') 🦋 Biodiversité @break
                            @case('energie_renouvelable') ⚡ Énergie Renouvelable @break
                            @default 🔧 {{ ucfirst($campaign->category) }} @break
                        @endswitch
                    </span>
                    <span class="badge bg-{{ $campaign->status == 'active' ? 'success' : ($campaign->status == 'completed' ? 'info' : 'secondary') }} fs-6">
                        @switch($campaign->status)
                            @case('active') 🟢 Active @break
                            @case('completed') ✅ Terminée @break
                            @case('cancelled') ❌ Annulée @break
                            @default ⏸️ {{ ucfirst($campaign->status) }} @break
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-warning btn-lg me-2">
                    <i class="fas fa-edit me-2"></i> Modifier
                </a>
                <a href="{{ route('campaigns.index') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                        </a>
                    </div>
                </div>
    </div>
</section>

<div class="container my-5">
                    <div class="row">
        <div class="col-lg-8">
            <!-- Campaign Details Card -->
            <div class="campaign-detail-card mb-4">
                <div class="campaign-header-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="campaign-header-info">
                                <h2 class="campaign-detail-title">{{ $campaign->name }}</h2>
                                <div class="campaign-badges">
                                    <span class="campaign-category-badge">
                                        @switch($campaign->category)
                                            @case('reforestation') 🌲 Reforestation @break
                                            @case('nettoyage') 🧹 Nettoyage @break
                                            @case('sensibilisation') 📢 Sensibilisation @break
                                            @case('recyclage') ♻️ Recyclage @break
                                            @case('biodiversite') 🦋 Biodiversité @break
                                            @case('energie_renouvelable') ⚡ Énergie Renouvelable @break
                                            @default 🔧 {{ ucfirst($campaign->category) }} @break
                                        @endswitch
                                    </span>
                                    <span class="status-badge status-{{ $campaign->status }}">
                                        @switch($campaign->status)
                                            @case('active') 🟢 Active @break
                                            @case('completed') ✅ Terminée @break
                                            @case('cancelled') ❌ Annulée @break
                                            @default ⏸️ {{ ucfirst($campaign->status) }} @break
                                        @endswitch
                                    </span>
                                    <span class="visibility-badge visibility-{{ $campaign->visibility ? 'public' : 'private' }}">
                                        {{ $campaign->visibility ? '🌐 Publique' : '🔒 Privée' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="campaign-actions-header">
                                <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="campaign-content-section">
                            <div class="row">
                        <!-- Progress Visualization -->
                        <div class="col-12 mb-4">
                            <div class="progress-visualization">
                                <div class="progress-header-large">
                                    <h4 class="progress-title">
                                        <i class="fas fa-chart-pie me-2"></i>Progression financière
                                    </h4>
                                    <div class="progress-percentage-large">{{ $campaign->funds_progress_percentage }}%</div>
                                </div>
                                <div class="progress-bar-large">
                                    <div class="progress-fill progress-{{ $campaign->funds_progress_percentage == 100 ? 'success' : ($campaign->funds_progress_percentage > 50 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $campaign->funds_progress_percentage }}%"></div>
                                </div>
                                <div class="progress-stats-large">
                                    <div class="stat-card">
                                        <div class="stat-icon"><i class="fas fa-euro-sign text-success"></i></div>
                                        <div class="stat-content">
                                            <div class="stat-number">{{ number_format($campaign->funds_raised, 0, ',', ' ') }}€</div>
                                            <div class="stat-label">Collecté</div>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon"><i class="fas fa-target text-eco"></i></div>
                                        <div class="stat-content">
                                            <div class="stat-number">{{ $campaign->goal ? number_format($campaign->goal, 0, ',', ' ') . '€' : 'N/A' }}</div>
                                            <div class="stat-label">Objectif</div>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon"><i class="fas fa-clock text-warning"></i></div>
                                        <div class="stat-content">
                                            <div class="stat-number">{{ $campaign->days_remaining }}</div>
                                            <div class="stat-label">Jours restants</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campaign Information -->
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
                                        <div class="info-value">{{ $campaign->name }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-user me-2"></i>Organisateur
                                        </div>
                                        <div class="info-value">{{ $campaign->organizer->name }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-plus me-2"></i>Début
                                        </div>
                                        <div class="info-value">{{ $campaign->start_date->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calendar-check me-2"></i>Fin
                                        </div>
                                        <div class="info-value">{{ $campaign->end_date->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                                </div>
                        
                        <!-- Campaign Status & Stats -->
                                <div class="col-md-6">
                            <div class="info-section">
                                <h5 class="section-title">
                                    <i class="fas fa-chart-line me-2"></i>Statut et statistiques
                                </h5>
                                <div class="status-stats-grid">
                                    <div class="status-card status-{{ $campaign->status }}">
                                        <div class="status-icon">
                                            @switch($campaign->status)
                                                @case('active') <i class="fas fa-play-circle"></i> @break
                                                @case('completed') <i class="fas fa-check-circle"></i> @break
                                                @case('cancelled') <i class="fas fa-times-circle"></i> @break
                                                @default <i class="fas fa-pause-circle"></i> @break
                                            @endswitch
                                        </div>
                                        <div class="status-content">
                                            <div class="status-label">Statut</div>
                                            <div class="status-value">{{ ucfirst($campaign->status) }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="stats-card">
                                        <div class="stats-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="stats-content">
                                            <div class="stats-label">Participants</div>
                                            <div class="stats-value">{{ $campaign->participants_count }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </div>
                            </div>

                    <!-- Description and Content -->
                    @if($campaign->description || $campaign->environmental_impact || $campaign->tags)
                    <div class="col-12">
                        <div class="content-section">
                            @if($campaign->description)
                            <div class="description-card">
                                <h5 class="section-title">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </h5>
                                <div class="description-content">
                                    {{ $campaign->description }}
                                </div>
                            </div>
                            @endif

                            @if($campaign->environmental_impact)
                            <div class="impact-card">
                                <h5 class="section-title">
                                    <i class="fas fa-globe-europe me-2"></i>Impact environnemental
                                </h5>
                                <div class="impact-content">
                                    <i class="fas fa-leaf me-2 text-success"></i>{{ $campaign->environmental_impact }}
                                </div>
                            </div>
                            @endif

                            @if($campaign->tags)
                            <div class="tags-card">
                                <h5 class="section-title">
                                    <i class="fas fa-tags me-2"></i>Tags
                                </h5>
                                <div class="tags-list">
                                    @foreach($campaign->tags as $tag)
                                        <span class="tag-item">
                                            <i class="fas fa-hashtag me-1"></i>{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                            @endif
                                        </div>
                                    </div>

            <!-- Associated Resources -->
            <div class="resources-section">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="fas fa-boxes me-2"></i>Ressources associées 
                        <span class="badge bg-eco">{{ $campaign->resources->count() }}</span>
                    </h4>
                                </div>
                
                @if($campaign->resources->count() > 0)
                    <div class="resources-grid">
                        @foreach($campaign->resources as $resource)
                        <div class="resource-mini-card">
                            <div class="resource-mini-header">
                                <h6 class="resource-mini-title">{{ $resource->name }}</h6>
                                <div class="resource-mini-badges">
                                    <span class="resource-type-mini">
                                        @switch($resource->resource_type)
                                            @case('money') 💰 @break
                                            @case('food') 🍎 @break
                                            @case('clothing') 👕 @break
                                            @case('medical') 🏥 @break
                                            @case('equipment') 🛠️ @break
                                            @case('human') 👥 @break
                                            @default 🔧 @break
                                        @endswitch
                                    </span>
                                    <span class="status-mini status-{{ $resource->status }}">
                                        @switch($resource->status)
                                            @case('received') ✅ @break
                                            @case('pledged') 📋 @break
                                            @case('needed') ⏳ @break
                                            @default 🔄 @break
                                        @endswitch
                                    </span>
                        </div>
                    </div>

                            <div class="resource-mini-progress">
                                <div class="progress-mini">
                                    <div class="progress-fill-mini progress-{{ $resource->progress_percentage == 100 ? 'success' : 'warning' }}" 
                                         style="width: {{ $resource->progress_percentage }}%"></div>
                                                </div>
                                <div class="progress-text">{{ $resource->progress_percentage }}%</div>
                                            </div>
                            
                            <div class="resource-mini-stats">
                                <span class="stat-mini">
                                    <strong>{{ $resource->quantity_pledged }}</strong> / {{ $resource->quantity_needed }} {{ $resource->unit }}
                                            </span>
                                <span class="priority-mini priority-{{ $resource->priority }}">
                                    @switch($resource->priority)
                                        @case('urgent') 🚨 @break
                                        @case('high') ⚠️ @break
                                        @case('medium') 📊 @break
                                        @default 📌 @break
                                    @endswitch
                                            </span>
                            </div>
                            
                            <div class="resource-mini-actions">
                                <a href="{{ route('resources.show', $resource) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                            </div>
                        </div>
                                    @endforeach
                        </div>
                    @else
                    <div class="empty-resources">
                        <i class="fas fa-box-open fa-3x text-eco mb-3"></i>
                        <h5>Aucune ressource associée</h5>
                        <p class="text-muted">Commencez par ajouter des ressources nécessaires à votre campagne</p>
                        </div>
                    @endif
                        </div>
                    </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Campaign Image -->
            @if($campaign->image_url)
            <div class="campaign-image-card mb-4">
                <img src="{{ Storage::url($campaign->image_url) }}" 
                     alt="{{ $campaign->name }}" 
                     class="campaign-detail-image"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="campaign-detail-image-placeholder" style="display: none;">
                    <i class="fas fa-leaf text-eco fa-4x"></i>
                    <p class="text-muted mt-2">Image non disponible</p>
                </div>
            </div>
            @else
            <div class="campaign-image-card mb-4">
                <div class="campaign-detail-image-placeholder">
                    <i class="fas fa-leaf text-eco fa-4x"></i>
                    <p class="text-muted mt-2">Aucune image</p>
                </div>
            </div>
            @endif
            
            <!-- Campaign Summary -->
            <div class="campaign-summary-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Résumé de la campagne
                    </h5>
                </div>
                <div class="card-body">
                    <div class="summary-stats">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Durée</div>
                                <div class="summary-value">{{ $campaign->start_date->diffInDays($campaign->end_date) }} jours</div>
                                                </div>
                                            </div>
                        
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Participants</div>
                                <div class="summary-value">{{ $campaign->participants_count }}</div>
                            </div>
                        </div>
                        
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="summary-content">
                                <div class="summary-label">Ressources</div>
                                <div class="summary-value">{{ $campaign->resources->count() }}</div>
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
    
    /* Campaign Detail Card */
    .campaign-detail-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .campaign-header-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 2rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .campaign-detail-title {
        color: var(--eco-green);
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 2rem;
    }
    
    .campaign-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .campaign-category-badge {
        background: linear-gradient(135deg, var(--eco-green), var(--eco-light-green));
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
    
    .status-active { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    .status-completed { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .status-cancelled { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
    
    .visibility-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .visibility-public { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    .visibility-private { background: linear-gradient(135deg, #ffc107, #e0a800); color: white; }
    
    .campaign-content-section {
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
    
    /* Status & Stats Grid */
    .status-stats-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .status-card, .stats-card {
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
    
    .stats-card {
        background: rgba(23, 162, 184, 0.05);
        border-left: 4px solid #17a2b8;
    }
    
    .status-icon, .stats-icon {
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
    
    .stats-icon {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }
    
    .status-label, .stats-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .status-value, .stats-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--eco-green);
    }
    
    /* Content Sections */
    .content-section {
        margin-top: 2rem;
    }
    
    .description-card, .impact-card, .tags-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        margin-bottom: 1rem;
    }
    
    .impact-card {
        border-left: 4px solid #28a745;
        background: rgba(40, 167, 69, 0.05);
    }
    
    .description-content, .impact-content {
        color: #495057;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .tag-item {
        background: rgba(108, 117, 125, 0.1);
        color: #495057;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid #e9ecef;
    }
    
    /* Resources Section */
    .resources-section {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    
    .section-header {
        background: linear-gradient(135deg, var(--eco-green), var(--eco-light-green));
        color: white;
        padding: 1.5rem 2rem;
    }
    
    .section-header .section-title {
        color: white;
        margin: 0;
        font-size: 1.5rem;
    }
    
    .resources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
        padding: 2rem;
    }
    
    .resource-mini-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .resource-mini-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .resource-mini-header {
        margin-bottom: 1rem;
    }
    
    .resource-mini-title {
        color: var(--eco-green);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }
    
    .resource-mini-badges {
        display: flex;
        gap: 0.5rem;
    }
    
    .resource-type-mini, .status-mini {
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .resource-type-mini {
        background: rgba(108, 117, 125, 0.1);
        color: #495057;
    }
    
    .status-needed { background: linear-gradient(135deg, #6c757d, #545b62); color: white; }
    .status-pledged { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .status-received { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    .status-in_use { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
    
    .resource-mini-progress {
        margin-bottom: 1rem;
    }
    
    .progress-mini {
        height: 6px;
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .progress-fill-mini {
        height: 100%;
        border-radius: 3px;
        transition: width 0.6s ease;
    }
    
    .progress-text {
        font-size: 0.875rem;
        color: var(--eco-green);
        font-weight: 600;
        text-align: center;
    }
    
    .resource-mini-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .stat-mini {
        font-size: 0.875rem;
        color: #495057;
    }
    
    .priority-mini {
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
    }
    
    .priority-urgent { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
    .priority-high { background: linear-gradient(135deg, #fd7e14, #e55100); color: white; }
    .priority-medium { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .priority-low { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    
    .empty-resources {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
    }
    
    /* Sidebar Cards */
    .campaign-image-card, .campaign-summary-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }
    
    .campaign-detail-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 1rem;
        display: block;
        background: #f8f9fa;
    }
    
    .campaign-detail-image-placeholder {
        width: 100%;
        height: 250px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
    }
    
    .campaign-summary-card .card-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: none;
        padding: 1rem 1.5rem;
    }
    
    .summary-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .summary-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(45, 90, 39, 0.05);
        border-radius: 0.75rem;
        border-left: 3px solid var(--eco-green);
    }
    
    .summary-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--eco-green), var(--eco-light-green));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .summary-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 600;
    }
    
    .summary-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--eco-green);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 40vh;
            text-align: center;
        }
        
        .campaign-detail-title {
            font-size: 1.5rem;
        }
        
        .progress-stats-large {
            flex-direction: column;
            gap: 1rem;
        }
        
        .progress-header-large {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .campaign-header-section {
            padding: 1.5rem;
        }
        
        .campaign-content-section {
            padding: 1.5rem;
        }
        
        .resources-grid {
            grid-template-columns: 1fr;
            padding: 1.5rem;
        }
    }
</style>
@endpush