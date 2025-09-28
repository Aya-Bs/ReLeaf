@extends('layouts.frontend')

@section('title', 'Gestion des Ressources')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-boxes me-3"></i>Gestion des <span class="text-success">Ressources</span>
                </h1>
                <p class="lead mb-4">
                    Gérez et suivez les ressources nécessaires à vos campagnes écologiques
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex gap-2 justify-content-lg-end">
                    <a href="{{ route('resources.high-priority') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-exclamation-triangle me-2"></i> Prioritaires
                    </a>
                    <a href="{{ route('resources.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus me-2"></i> Nouvelle Ressource
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Alerts Section -->
    @if(session('success'))
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        </div>
    @endif

    @if(session('error'))
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        </div>
    @endif

<!-- Filters Section -->
<section class="filters-section py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="filters-card">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label text-eco fw-bold">
                                <i class="fas fa-search me-1"></i>Recherche
                            </label>
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="🔍 Nom de la ressource..." value="{{ request('search') }}">
                </div>
                        <div class="col-md-3">
                            <label class="form-label text-eco fw-bold">
                                <i class="fas fa-leaf me-1"></i>Campagne
                            </label>
                            <select name="campaign_id" class="form-select form-select-lg">
                                <option value="">🌱 Toutes les campagnes</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ Str::limit($campaign->name, 25) }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-eco fw-bold">
                                <i class="fas fa-filter me-1"></i>Statut
                            </label>
                            <select name="status" class="form-select form-select-lg">
                                <option value="">🔄 Tous statuts</option>
                                <option value="needed" {{ request('status') == 'needed' ? 'selected' : '' }}>⏳ Nécessaire</option>
                                <option value="pledged" {{ request('status') == 'pledged' ? 'selected' : '' }}>📋 Promis</option>
                                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>✅ Reçu</option>
                                <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>🔄 Utilisé</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-eco fw-bold">
                                <i class="fas fa-sort me-1"></i>Tri
                            </label>
                            <select name="sort" class="form-select form-select-lg">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>📅 Plus récentes</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>📅 Plus anciennes</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>🔤 Par nom</option>
                                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>🚨 Par priorité</option>
                        </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-eco btn-lg w-100">
                                <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                        </div>
</section>

<!-- Resources List Section -->
<section class="resources-list-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <!-- Resources Grid - Creative Display -->
                <div class="row">
                        @forelse($resources as $resource)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="resource-material-card">
                            <!-- Image Header -->
                            <div class="resource-header-image">
                                    @if($resource->image_url)
                                        <img src="{{ Storage::url($resource->image_url) }}" 
                                             alt="{{ $resource->name }}"
                                         class="resource-main-image"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="resource-placeholder" style="display: none;">
                                        @switch($resource->resource_type)
                                            @case('money') <i class="fas fa-money-bill-wave"></i> @break
                                            @case('food') <i class="fas fa-apple-alt"></i> @break
                                            @case('clothing') <i class="fas fa-tshirt"></i> @break
                                            @case('medical') <i class="fas fa-medkit"></i> @break
                                            @case('equipment') <i class="fas fa-tools"></i> @break
                                            @case('human') <i class="fas fa-users"></i> @break
                                            @default <i class="fas fa-box"></i> @break
                                        @endswitch
                                    </div>
                                    @else
                                    <div class="resource-placeholder">
                                        @switch($resource->resource_type)
                                            @case('money') <i class="fas fa-money-bill-wave"></i> @break
                                            @case('food') <i class="fas fa-apple-alt"></i> @break
                                            @case('clothing') <i class="fas fa-tshirt"></i> @break
                                            @case('medical') <i class="fas fa-medkit"></i> @break
                                            @case('equipment') <i class="fas fa-tools"></i> @break
                                            @case('human') <i class="fas fa-users"></i> @break
                                            @default <i class="fas fa-box"></i> @break
                                        @endswitch
                                        </div>
                                    @endif
                                
                                <!-- Priority Badge -->
                                <div class="priority-badge priority-{{ $resource->priority }}">
                                    @switch($resource->priority)
                                        @case('urgent') 🚨 @break
                                        @case('high') ⚠️ @break
                                        @case('medium') 📊 @break
                                        @default 📌 @break
                                    @endswitch
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="status-badge status-{{ $resource->status }}">
                                    @switch($resource->status)
                                        @case('needed') ⏳ @break
                                        @case('pledged') 📋 @break
                                        @case('received') ✅ @break
                                        @case('in_use') 🔄 @break
                                        @default ❓ @break
                                    @endswitch
                                    </div>
                                </div>
                            
                            <!-- Card Content -->
                            <div class="resource-card-content">
                                <!-- Title and Type -->
                                <div class="resource-title-section">
                                    <h5 class="resource-title">{{ $resource->name }}</h5>
                                    <div class="resource-type">
                                        @switch($resource->resource_type)
                                            @case('money') 💰 Argent @break
                                            @case('food') 🍎 Nourriture @break
                                            @case('clothing') 👕 Vêtements @break
                                            @case('medical') 🏥 Médical @break
                                            @case('equipment') 🛠️ Équipement @break
                                            @case('human') 👥 Main d'œuvre @break
                                            @default 🔧 {{ ucfirst($resource->resource_type) }} @break
                                        @endswitch
                                    </div>
                                </div>
                                
                                <!-- Campaign Link -->
                                <div class="resource-campaign">
                                    <i class="fas fa-leaf text-eco me-2"></i>
                                    <a href="{{ route('campaigns.show', $resource->campaign) }}" 
                                       class="campaign-link">{{ Str::limit($resource->campaign->name, 30) }}</a>
                                </div>
                                
                                <!-- Progress Section -->
                                <div class="resource-progress-section">
                                    <div class="progress-header">
                                        <span class="progress-label">Progression</span>
                                        <span class="progress-percentage">{{ $resource->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress progress-custom">
                                        <div class="progress-bar progress-{{ $resource->progress_percentage == 100 ? 'success' : ($resource->progress_percentage > 50 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $resource->progress_percentage }}%"></div>
                                    </div>
                                    <div class="progress-stats">
                                        <div class="stat-item">
                                            <span class="stat-number text-success">{{ $resource->quantity_pledged }}</span>
                                            <span class="stat-label">Collecté</span>
                                        </div>
                                        <div class="stat-divider">/</div>
                                        <div class="stat-item">
                                            <span class="stat-number text-eco">{{ $resource->quantity_needed }}</span>
                                            <span class="stat-label">Nécessaire</span>
                                        </div>
                                        <div class="stat-unit">{{ $resource->unit }}</div>
                                    </div>
                                @if($resource->missing_quantity > 0)
                                        <div class="missing-quantity">
                                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                            Manque: <strong>{{ $resource->missing_quantity }} {{ $resource->unit }}</strong>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Provider Info -->
                                @if($resource->provider)
                                <div class="resource-provider">
                                    <i class="fas fa-user-circle me-2"></i>
                                    <span>{{ $resource->provider }}</span>
                                </div>
                                @endif
                                
                                <!-- Action Buttons -->
                                <div class="resource-actions">
                                    <a href="{{ route('resources.show', $resource) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>
                                    <a href="{{ route('resources.edit', $resource) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach(['needed' => 'Nécessaire', 'pledged' => 'Promis', 'received' => 'Reçu', 'in_use' => 'Utilisé'] as $value => $label)
                                                <li>
                                                    <form action="{{ route('resources.update-status', $resource) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="dropdown-item {{ $resource->status == $value ? 'active' : '' }}"
                                                                onclick="return confirm('Changer le statut à {{ $label }}?')">
                                                            {{ $label }}
                                                        </button>
                                                        <input type="hidden" name="status" value="{{ $value }}">
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                                </div>
                        @empty
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-box-open fa-4x text-eco mb-4"></i>
                        <h4 class="text-muted mb-3">Aucune ressource trouvée</h4>
                        <p class="text-muted mb-4">Commencez par créer votre première ressource</p>
                        <a href="{{ route('resources.create') }}" class="btn btn-eco btn-lg">
                            <i class="fas fa-plus me-2"></i>Créer une ressource
                        </a>
                    </div>
                </div>
                        @endforelse

            <!-- Pagination -->
            @if($resources->hasPages())
                <div class="pagination-wrapper mt-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="pagination-info">
                            <p class="mb-0 text-muted">
                                Affichage de <strong>{{ $resources->firstItem() }}</strong> à <strong>{{ $resources->lastItem() }}</strong> 
                                sur <strong>{{ $resources->total() }}</strong> ressources
                    </p>
                </div>
                        <div class="pagination-links">
                    {{ $resources->links() }}
                        </div>
                </div>
            </div>
            @endif
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
    
    /* Filters Section */
    .filters-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
    }
    
    .filters-card {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .form-control-lg, .form-select-lg {
        border-radius: 0.5rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: var(--eco-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 90, 39, 0.25);
    }
    
    /* Stats Bar */
    .stats-bar {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--eco-green) 0%, var(--eco-light-green) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .stat-content h4 {
        margin: 0;
        color: var(--eco-green);
        font-weight: 700;
        font-size: 2rem;
    }
    
    .stat-content p {
        margin: 0;
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    /* Material Resource Cards - Creative Design */
    .resource-material-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
        position: relative;
        height: 100%;
    }
    
    .resource-material-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
    }
    
    /* Header Image Section */
    .resource-header-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        border-radius: 1.5rem 1.5rem 0 0;
        background: #f8f9fa;
    }
    
    .resource-main-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
        display: block;
    }
    
    .resource-material-card:hover .resource-main-image {
        transform: scale(1.1);
    }
    
    .resource-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--eco-green);
        min-height: 200px;
    }
    
    /* Floating Badges */
    .priority-badge, .status-badge {
        position: absolute;
        top: 1rem;
        padding: 0.5rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .priority-badge {
        right: 1rem;
    }
    
    .status-badge {
        left: 1rem;
    }
    
    .priority-urgent { background: linear-gradient(135deg, #dc3545, #c82333); color: white; }
    .priority-high { background: linear-gradient(135deg, #fd7e14, #e55100); color: white; }
    .priority-medium { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .priority-low { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    
    .status-needed { background: linear-gradient(135deg, #6c757d, #545b62); color: white; }
    .status-pledged { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }
    .status-received { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
    .status-in_use { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
    
    /* Card Content */
    .resource-card-content {
        padding: 1.5rem;
    }
    
    .resource-title-section {
        margin-bottom: 1rem;
    }
    
    .resource-title {
        color: var(--eco-green);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
        line-height: 1.3;
    }
    
    .resource-type {
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .resource-campaign {
        margin-bottom: 1.25rem;
        padding: 0.75rem;
        background: rgba(45, 90, 39, 0.05);
        border-radius: 0.75rem;
        border-left: 3px solid var(--eco-green);
    }
    
    .campaign-link {
        color: var(--eco-green);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .campaign-link:hover {
        color: var(--eco-light-green);
        text-decoration: underline;
    }
    
    /* Progress Section */
    .resource-progress-section {
        margin-bottom: 1.25rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 1rem;
        border: 1px solid #e9ecef;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .progress-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--eco-green);
    }
    
    .progress-percentage {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--eco-green);
    }
    
    .progress-custom {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
        margin-bottom: 0.75rem;
    }
    
    .progress-custom .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
    }
    
    .progress-success { background: linear-gradient(135deg, #28a745, #20c997); }
    .progress-warning { background: linear-gradient(135deg, #ffc107, #fd7e14); }
    .progress-danger { background: linear-gradient(135deg, #dc3545, #e83e8c); }
    
    .progress-stats {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }
    
    .stat-number {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }
    
    .stat-divider {
        font-size: 1.5rem;
        font-weight: 700;
        color: #dee2e6;
    }
    
    .stat-unit {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    
    .missing-quantity {
        margin-top: 0.75rem;
        padding: 0.5rem;
        background: rgba(255, 193, 7, 0.1);
        border-radius: 0.5rem;
        text-align: center;
        font-size: 0.875rem;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    /* Provider Info */
    .resource-provider {
        margin-bottom: 1.25rem;
        padding: 0.75rem;
        background: rgba(108, 117, 125, 0.05);
        border-radius: 0.75rem;
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    /* Action Buttons */
    .resource-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .resource-actions .btn {
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .resource-actions .btn:hover {
        transform: translateY(-2px);
    }
    
    /* Progress Bar */
.progress {
    background-color: #e9ecef;
        border-radius: 0.5rem;
        height: 12px !important;
}
    
.progress-bar {
        border-radius: 0.5rem;
    transition: width 0.6s ease;
}
    
    /* Gradients */
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }
    
    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 1rem;
        padding: 4rem 2rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    /* Pagination */
    .pagination-wrapper {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .pagination-info {
        color: #6c757d;
    }
    
    .pagination-links .pagination {
        margin: 0;
    }
    
    .pagination-links .page-link {
        color: var(--eco-green);
        border-color: #e9ecef;
        border-radius: 0.5rem;
        margin: 0 0.25rem;
        padding: 0.5rem 0.75rem;
    }
    
    .pagination-links .page-link:hover {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }
    
    .pagination-links .page-item.active .page-link {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 40vh;
            text-align: center;
        }
        
        .filters-card {
            padding: 1.5rem;
        }
        
        .stats-bar {
            padding: 1.5rem;
        }
        
        .stat-item {
            flex-direction: column;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        .resource-material-card {
            margin-bottom: 2rem;
        }
        
        .resource-header-image {
            height: 180px;
        }
        
        .resource-placeholder {
            font-size: 2.5rem;
        }
        
        .resource-card-content {
            padding: 1.25rem;
        }
        
        .resource-title {
            font-size: 1.125rem;
        }
        
        .resource-actions {
            justify-content: center;
            margin-top: 1rem;
        }
        
        .resource-actions .btn {
            flex: 1;
            min-width: 80px;
        }
        
        .progress-stats {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .stat-divider {
            display: none;
        }
        
        .pagination-wrapper {
            padding: 1.5rem;
        }
        
        .pagination-wrapper .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
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
    
    .resource-card:nth-child(odd) {
        animation-delay: 0.1s;
    }
    
    .resource-card:nth-child(even) {
        animation-delay: 0.2s;
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
}
</style>
@endpush

@push('scripts')
<script>
// Confirmation pour les actions sensibles
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation pour la suppression
    const deleteForms = document.querySelectorAll('form[action*="/destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette ressource ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });

    // Tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush