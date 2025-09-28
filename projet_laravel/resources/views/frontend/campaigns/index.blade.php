@extends('layouts.frontend')

@section('title', 'Gestion des Campagnes')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-leaf me-3"></i>Gestion des <span class="text-success">Campagnes</span>
                </h1>
                <p class="lead mb-4">
                    Gérez et organisez vos campagnes écologiques pour maximiser leur impact
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('campaigns.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i> Nouvelle Campagne
                </a>
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

<!-- Filters Section -->
<section class="filters-section py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="filters-card">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label text-eco fw-bold">
                                <i class="fas fa-search me-1"></i>Recherche
                            </label>
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="🔍 Nom de la campagne..." value="{{ request('search') }}">
                </div>
                        <div class="col-md-3">
                            <label class="form-label text-eco fw-bold">
                                <i class="fas fa-filter me-1"></i>Catégorie
                            </label>
                            <select name="category" class="form-select form-select-lg">
                                <option value="">🌱 Toutes les catégories</option>
                                @foreach(['reforestation' => '🌲 Reforestation', 'nettoyage' => '🧹 Nettoyage', 'sensibilisation' => '📢 Sensibilisation', 'recyclage' => '♻️ Recyclage', 'biodiversite' => '🦋 Biodiversité', 'energie_renouvelable' => '⚡ Énergie Renouvelable', 'autre' => '🔧 Autre'] as $category => $label)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-eco fw-bold">
                                <i class="fas fa-sort me-1"></i>Tri
                            </label>
                            <select name="sort" class="form-select form-select-lg">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>📅 Plus récentes</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>📅 Plus anciennes</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>🔤 Par nom</option>
                                <option value="funds" {{ request('sort') == 'funds' ? 'selected' : '' }}>💰 Par financement</option>
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

<!-- Campaigns List Section -->
<section class="campaigns-list-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Stats Bar -->
                <div class="stats-bar mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-number">{{ $campaigns->total() }}</h4>
                                    <p class="stat-label">Campagnes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-euro-sign"></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-number">{{ number_format($campaigns->sum('funds_raised'), 0, ',', ' ') }}€</h4>
                                    <p class="stat-label">Total levé</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-number">{{ $campaigns->sum('participants_count') }}</h4>
                                    <p class="stat-label">Participants</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-content">
                                    <h4 class="stat-number">{{ round($campaigns->avg('funds_progress_percentage'), 1) }}%</h4>
                                    <p class="stat-label">Moyenne financement</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campaigns Grid -->
                <!-- Campaigns Grid -->
                        @forelse($campaigns as $campaign)
                <div class="campaign-card mb-4">
                        <div class="row g-0">
                            <!-- Image de la campagne -->
                            <div class="col-md-3">
                                <div class="campaign-image">
                                @if($campaign->image_url)
                                        <img src="{{ Storage::url($campaign->image_url) }}" 
                                             alt="{{ $campaign->name }}" 
                                             class="campaign-image-content">
                                @else
                                        <div class="campaign-image-placeholder">
                                            <i class="fas fa-leaf text-white fa-3x"></i>
                                    </div>
                                @endif
                                </div>
                            </div>
                            
                            <!-- Contenu de la carte -->
                            <div class="col-md-9">
                                <div class="campaign-content">
                                    <!-- En-tête de la carte -->
                                    <div class="campaign-header">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="campaign-title">{{ $campaign->name }}</h5>
                                                <div class="campaign-meta">
                                                    <span class="badge bg-success me-2">
                                                        @switch($campaign->category)
                                                            @case('reforestation') 🌲 @break
                                                            @case('nettoyage') 🧹 @break
                                                            @case('sensibilisation') 📢 @break
                                                            @case('recyclage') ♻️ @break
                                                            @case('biodiversite') 🦋 @break
                                                            @case('energie_renouvelable') ⚡ @break
                                                            @default 🔧 @break
                                                        @endswitch
                                                        {{ ucfirst($campaign->category) }}
                                                    </span>
                                                    <span class="badge bg-{{ $campaign->status == 'active' ? 'success' : ($campaign->status == 'completed' ? 'info' : 'secondary') }}">
                                                        @switch($campaign->status)
                                                            @case('active') 🟢 @break
                                                            @case('completed') ✅ @break
                                                            @case('cancelled') ❌ @break
                                                            @default ⏸️ @break
                                                        @endswitch
                                                        {{ ucfirst($campaign->status) }}
                                                    </span>
                                @if(!$campaign->visibility)
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-eye-slash me-1"></i>Privée
                                                        </span>
                                @endif
                                                </div>
                                </div>
                                            <div class="campaign-actions">
                                <div class="btn-group" role="group">
                                                    <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                                    <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('campaigns.toggle-visibility', $campaign) }}" method="POST" class="d-inline">
                                        @csrf
                                                        <button type="submit" class="btn btn-{{ $campaign->visibility ? 'outline-secondary' : 'outline-success' }} btn-sm" title="{{ $campaign->visibility ? 'Rendre privée' : 'Rendre publique' }}">
                                            <i class="fas fa-{{ $campaign->visibility ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Corps de la carte -->
                                    <div class="campaign-body">
                                        <div class="row">
                                            <!-- Dates -->
                                            <div class="col-md-3">
                                                <div class="campaign-info">
                                                    <h6 class="info-label">
                                                        <i class="fas fa-calendar-alt me-1"></i>Dates
                                                    </h6>
                                                    <p class="info-value mb-0">
                                                        <strong>{{ $campaign->start_date->format('d/m/Y') }}</strong><br>
                                                        <small class="text-muted">au {{ $campaign->end_date->format('d/m/Y') }}</small>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <!-- Financement -->
                                            <div class="col-md-4">
                                                <div class="campaign-info">
                                                    <h6 class="info-label">
                                                        <i class="fas fa-euro-sign me-1"></i>Financement
                                                    </h6>
                                                    <div class="progress mb-2" style="height: 8px;">
                                                        <div class="progress-bar bg-gradient-success" 
                                                             style="width: {{ $campaign->funds_progress_percentage }}%"></div>
                                                    </div>
                                                    <p class="info-value mb-0">
                                                        <strong>{{ number_format($campaign->funds_raised, 0, ',', ' ') }} €</strong>
                                                        @if($campaign->goal)
                                                            / {{ number_format($campaign->goal, 0, ',', ' ') }} €
                                                        @endif
                                                        <small class="text-muted d-block">{{ $campaign->funds_progress_percentage }}% atteint</small>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <!-- Participants -->
                                            <div class="col-md-2">
                                                <div class="campaign-info">
                                                    <h6 class="info-label">
                                                        <i class="fas fa-users me-1"></i>Participants
                                                    </h6>
                                                    <p class="info-value mb-0">
                                                        <strong class="text-primary">{{ $campaign->participants_count }}</strong>
                                                    </p>
                                                </div>
            </div>

                                            <!-- ID -->
                                            <div class="col-md-3">
                                                <div class="campaign-info">
                                                    <h6 class="info-label">
                                                        <i class="fas fa-hashtag me-1"></i>ID
                                                    </h6>
                                                    <p class="info-value mb-0">
                                                        <strong>#{{ $campaign->id }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
                @empty
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-leaf fa-4x text-eco mb-4"></i>
                        <h4 class="text-muted mb-3">Aucune campagne trouvée</h4>
                        <p class="text-muted mb-4">Commencez par créer votre première campagne écologique</p>
                        <a href="{{ route('campaigns.create') }}" class="btn btn-eco btn-lg">
                            <i class="fas fa-plus me-2"></i>Créer une campagne
                        </a>
                    </div>
                </div>
                @endforelse

                <!-- Pagination -->
                @if($campaigns->hasPages())
                <div class="pagination-wrapper mt-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="pagination-info">
                            <p class="mb-0 text-muted">
                                Affichage de <strong>{{ $campaigns->firstItem() }}</strong> à <strong>{{ $campaigns->lastItem() }}</strong> 
                                sur <strong>{{ $campaigns->total() }}</strong> campagnes
                            </p>
                        </div>
                        <div class="pagination-links">
                            {{ $campaigns->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
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
    
    /* Campaign Cards */
    .campaign-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }
    
    .campaign-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .campaign-image {
        min-height: 200px;
        border-radius: 1rem 0 0 1rem;
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
    }
    
    .campaign-image-content {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
        display: block;
    }
    
    .campaign-image-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
    }
    
    .campaign-card:hover .campaign-image-content {
        transform: scale(1.05);
    }
    
    .campaign-content {
        padding: 2rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .campaign-header {
        margin-bottom: 1.5rem;
    }
    
    .campaign-title {
        color: var(--eco-green);
        font-weight: 700;
        margin-bottom: 0.75rem;
        font-size: 1.5rem;
        line-height: 1.3;
    }
    
    .campaign-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }
    
    .campaign-meta .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
    }
    
    .campaign-actions {
        flex-shrink: 0;
    }
    
    .campaign-body {
        flex-grow: 1;
    }
    
    .campaign-info {
        margin-bottom: 1.25rem;
    }
    
    .info-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--eco-green);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 1rem;
        color: #495057;
        line-height: 1.4;
    }
    
    .info-value strong {
        color: var(--eco-green);
        font-weight: 700;
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
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    /* Gradients */
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        
        .campaign-card .row {
            flex-direction: column;
        }
        
        .campaign-image {
            min-height: 250px;
            border-radius: 1rem 1rem 0 0;
        }
        
        .campaign-image-content,
        .campaign-image-placeholder {
            border-radius: 1rem 1rem 0 0 !important;
        }
        
        .campaign-content {
            padding: 1.5rem;
        }
        
        .campaign-title {
            font-size: 1.25rem;
        }
        
        .campaign-actions {
            margin-top: 1rem;
        }
        
        .campaign-actions .btn-group {
            width: 100%;
            justify-content: center;
        }
        
        .campaign-meta {
            justify-content: center;
            margin-top: 0.5rem;
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
    
    .campaign-card:nth-child(odd) {
        animation-delay: 0.1s;
    }
    
    .campaign-card:nth-child(even) {
        animation-delay: 0.2s;
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
}
</style>
@endpush