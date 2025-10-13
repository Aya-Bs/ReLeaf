@extends('layouts.frontend')

@section('title', 'Gestion des Ressources')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Gestion des Ressources</h1>
                    <p class="text-muted mb-0">Gérez et suivez les ressources nécessaires à vos campagnes écologiques</p>
                </div>
                <div>
                    <a href="{{ route('resources.high-priority') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-exclamation-triangle me-2"></i> Prioritaires
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Search and Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <!-- Search Input -->
                        <div class="position-relative">
                            <input type="text" 
                                   id="searchInput" 
                                   class="form-control form-control-sm" 
                                   placeholder="Recherche..." 
                                   style="width: 200px;"
                                   value="{{ request('search') }}">
                            <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-muted" style="font-size: 0.8rem;"></i>
                        </div>
                        
                        <!-- Campaign Filter -->
                        <div>
                            <select id="campaignFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Toutes les campagnes</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                        {{ Str::limit($campaign->name, 25) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Tous les statuts</option>
                                <option value="needed" {{ request('status') == 'needed' ? 'selected' : '' }}>⏳ Nécessaire</option>
                                <option value="pledged" {{ request('status') == 'pledged' ? 'selected' : '' }}>📋 Promis</option>
                                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>✅ Reçu</option>
                                <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>🔄 Utilisé</option>
                            </select>
                        </div>
                        
                        <!-- Sort Filter -->
                        <div>
                            <select id="sortFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Par nom</option>
                                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Par priorité</option>
                            </select>
                        </div>
                        
                        <!-- Clear Filters Button -->
                        <button id="clearFilters" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Effacer
                        </button>
                        
                        <!-- Create Resource Button -->
                        <a href="{{ route('resources.create') }}" class="btn btn-eco btn-sm">
                            <i class="fas fa-plus me-2"></i>Créer une ressource
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Type Filter Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-center flex-wrap gap-2" id="typeFilterBar">
                        <button class="resource-type-filter btn btn-outline-eco btn-sm active" data-type="">
                            <span class="me-2">📦</span>Tous
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="money">
                            <span class="me-2">💰</span>Argent
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="food">
                            <span class="me-2">🍎</span>Nourriture
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="clothing">
                            <span class="me-2">👕</span>Vêtements
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="medical">
                            <span class="me-2">🏥</span>Médical
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="equipment">
                            <span class="me-2">🛠️</span>Équipement
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="human">
                            <span class="me-2">👥</span>Main d'œuvre
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-4" style="display: none;">
        <div class="spinner-border text-eco" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="mt-2 text-muted">Filtrage des ressources...</p>
    </div>

    <!-- Content Container -->
    <div id="contentContainer">
        @php
            $userResources = $resources->filter(function($resource) {
                return $resource->campaign && $resource->campaign->organizer_id === auth()->id();
            });
        @endphp

        @if($userResources->count() > 0)
            <!-- Resources Cards -->
            <div class="row g-4" id="resourcesGrid">
                @foreach($userResources as $resource)
                <div class="col-12 col-md-6 col-lg-4 resource-card" data-type="{{ $resource->resource_type }}">
                    <div class="card h-100 resource-card-item shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <span class="resource-type-icon me-2 fs-4">
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
                                <div>
                                    <h6 class="mb-0">{{ Str::limit($resource->name, 20) }}</h6>
                                    <small class="text-muted">{{ Str::limit($resource->campaign->name, 25) }}</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown"
                                        title="Actions">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('resources.show', $resource) }}" class="dropdown-item">
                                            <i class="fas fa-eye me-2"></i>Voir
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('resources.edit', $resource) }}" class="dropdown-item">
                                            <i class="fas fa-edit me-2"></i>Modifier
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
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
                        
                        <div class="card-body">
                            @if($resource->description)
                                <p class="card-text">{{ Str::limit($resource->description, 100) }}</p>
                            @endif
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted">Progression</span>
                                    <span class="fw-bold">{{ $resource->progress_percentage }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : ($resource->progress_percentage > 50 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $resource->progress_percentage }}%"></div>
                                </div>
                            </div>
                            
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="border-end">
                                        <div class="fw-bold text-success">{{ $resource->quantity_pledged }}</div>
                                        <small class="text-muted">Promis</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-eco">{{ $resource->quantity_needed }}</div>
                                    <small class="text-muted">Nécessaire</small>
                                </div>
                            </div>
                            
                            @if($resource->missing_quantity > 0)
                                <div class="alert alert-warning py-2 mb-3">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Manque {{ $resource->missing_quantity }} {{ $resource->unit }}
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $resource->status == 'received' ? 'success' : ($resource->status == 'pledged' ? 'info' : ($resource->status == 'in_use' ? 'primary' : 'secondary')) }}">
                                    @switch($resource->status)
                                        @case('needed') ⏳ @break
                                        @case('pledged') 📋 @break
                                        @case('received') ✅ @break
                                        @case('in_use') 🔄 @break
                                        @default ❓ @break
                                    @endswitch
                                    {{ ucfirst($resource->status) }}
                                </span>
                                
                                <span class="badge bg-{{ $resource->priority == 'urgent' ? 'danger' : ($resource->priority == 'high' ? 'warning' : ($resource->priority == 'medium' ? 'info' : 'success')) }}">
                                    @switch($resource->priority)
                                        @case('urgent') 🚨 @break
                                        @case('high') ⚠️ @break
                                        @case('medium') 📊 @break
                                        @default 📌 @break
                                    @endswitch
                                    {{ ucfirst($resource->priority) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    @if($resource->provider)
                                        Fourni par: {{ Str::limit($resource->provider, 15) }}
                                    @else
                                        Aucun fournisseur
                                    @endif
                                </small>
                                <small class="text-muted">{{ $resource->unit }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($resources->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
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
            @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted" id="emptyMessage">
                @if(request()->has('search') || request()->has('campaign_id') || request()->has('status') || request()->has('resource_type') || request()->has('sort'))
                    Aucune ressource trouvée avec les critères de recherche.
                @else
                    Aucune ressource créée pour le moment.
                @endif
            </p>
            <a href="{{ route('resources.create') }}" class="btn btn-eco">Créer votre première ressource</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .resource-type-icon {
        font-size: 1.25rem;
    }
    
    /* Type Filter Bar */
    .resource-type-filter {
        transition: all 0.3s ease;
        border-radius: 20px;
        padding: 0.5rem 1rem;
    }
    
    .resource-type-filter.active {
        background-color: var(--eco-green);
        color: white;
        border-color: var(--eco-green);
    }
    
    /* Resource Cards */
    .resource-card-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .resource-card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .resource-card {
        transition: all 0.5s ease;
    }
    
    .resource-card.hidden {
        opacity: 0;
        transform: scale(0.9);
        height: 0;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    
    /* Card Header */
    .card-header h6 {
        color: #2d5a27;
    }
    
    /* Progress Bar */
    .progress {
        background-color: #e9ecef;
        border-radius: 0.5rem;
    }
    
    /* Badges */
    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.6em;
    }
    
    /* Pagination */
    .pagination-links .pagination {
        margin: 0;
    }
    
    .pagination-links .page-link {
        color: var(--eco-green);
        border-color: #e9ecef;
        border-radius: 0.375rem;
        margin: 0 0.125rem;
        padding: 0.375rem 0.75rem;
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
        .d-flex.flex-wrap {
            gap: 1rem !important;
        }
        
        .d-flex.flex-wrap > div {
            flex: 1 1 100%;
        }
        
        .resource-type-filter {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
        }
        
        .pagination-links .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 576px) {
        .resource-type-filter span {
            display: none;
        }
        
        .resource-type-filter {
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Fonction pour initialiser les gestionnaires d'événements
function initializeEventHandlers() {
    // Récupérer les éléments du DOM
    const searchInput = document.getElementById('searchInput');
    const campaignFilter = document.getElementById('campaignFilter');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const sortFilter = document.getElementById('sortFilter');
    const clearFilters = document.getElementById('clearFilters');
    const typeFilterBar = document.getElementById('typeFilterBar');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const contentContainer = document.getElementById('contentContainer');
    
    // Variables pour le contrôle des timeouts
    let searchTimeout = null;
    
    // Vérifier si les éléments existent avant d'ajouter les écouteurs
    if (searchInput) {
        searchInput.addEventListener('input', handleSearchInput);
    }
    
    if (campaignFilter) {
        campaignFilter.addEventListener('change', updateFilters);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', updateFilters);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', updateFilters);
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', updateFilters);
    }
    
    if (clearFilters) {
        clearFilters.addEventListener('click', handleClearFilters);
    }
    
    if (typeFilterBar) {
        typeFilterBar.addEventListener('click', handleTypeFilterClick);
    }
    
    // Initialiser les filtres depuis l'URL
    initializeFiltersFromURL();
    
    // Fonction pour gérer la recherche avec délai
    function handleSearchInput() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateFilters, 500);
    }
    
    // Fonction pour gérer le clic sur les filtres de type
    function handleTypeFilterClick(event) {
        const target = event.target.closest('.resource-type-filter');
        if (!target) return;
        
        // Mettre à jour les boutons actifs
        document.querySelectorAll('.resource-type-filter').forEach(btn => {
            btn.classList.remove('active');
        });
        target.classList.add('active');
        
        // Mettre à jour le filtre de type
        if (typeFilter) {
            typeFilter.value = target.dataset.type;
            updateFilters();
        }
    }
    
    // Fonction pour effacer tous les filtres
    function handleClearFilters() {
        if (searchInput) searchInput.value = '';
        if (campaignFilter) campaignFilter.value = '';
        if (statusFilter) statusFilter.value = '';
        if (typeFilter) typeFilter.value = '';
        if (sortFilter) sortFilter.value = 'latest';
        
        // Réinitialiser les boutons de type
        document.querySelectorAll('.resource-type-filter').forEach(btn => {
            btn.classList.remove('active');
        });
        const allTypesBtn = document.querySelector('.resource-type-filter[data-type=""]');
        if (allTypesBtn) allTypesBtn.classList.add('active');
        
        updateFilters();
    }
    
    // Fonction pour initialiser les filtres depuis l'URL
    function initializeFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (searchInput && urlParams.get('search')) {
            searchInput.value = urlParams.get('search');
        }
        
        if (campaignFilter && urlParams.get('campaign_id')) {
            campaignFilter.value = urlParams.get('campaign_id');
        }
        
        if (statusFilter && urlParams.get('status')) {
            statusFilter.value = urlParams.get('status');
        }
        
        if (typeFilter && urlParams.get('resource_type')) {
            typeFilter.value = urlParams.get('resource_type');
            // Activer le bouton correspondant
            document.querySelectorAll('.resource-type-filter').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.type === urlParams.get('resource_type')) {
                    btn.classList.add('active');
                }
            });
        } else {
            // Activer le bouton "Tous" par défaut
            const allTypesBtn = document.querySelector('.resource-type-filter[data-type=""]');
            if (allTypesBtn) allTypesBtn.classList.add('active');
        }
        
        if (sortFilter && urlParams.get('sort')) {
            sortFilter.value = urlParams.get('sort');
        }
    }
    
    // Fonction principale pour mettre à jour les filtres
    function updateFilters() {
        // Afficher l'indicateur de chargement
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (contentContainer) contentContainer.style.opacity = '0.5';
        
        // Récupérer les valeurs des filtres
        const filters = {
            search: searchInput ? searchInput.value : '',
            campaign_id: campaignFilter ? campaignFilter.value : '',
            status: statusFilter ? statusFilter.value : '',
            resource_type: typeFilter ? typeFilter.value : '',
            sort: sortFilter ? sortFilter.value : 'latest'
        };
        
        // Mettre à jour l'URL sans recharger la page
        const url = new URL(window.location);
        
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                url.searchParams.set(key, filters[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        
        window.history.pushState({}, '', url);
        
        // Récupérer le contenu mis à jour
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // Parser la réponse HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extraire le conteneur de contenu de la réponse
            const newContent = doc.getElementById('contentContainer');
            if (newContent && contentContainer) {
                contentContainer.innerHTML = newContent.innerHTML;
                
                // Réinitialiser les gestionnaires d'événements pour les nouveaux éléments
                initializeEventHandlers();
            }
            
            updateFilterInfo();
        })
        .catch(error => {
            console.error('Error:', error);
            // En cas d'erreur, recharger la page complète
            window.location.href = url;
        })
        .finally(() => {
            // Masquer l'indicateur de chargement
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (contentContainer) contentContainer.style.opacity = '1';
        });
    }
    
    // Fonction pour mettre à jour les informations de filtre
    function updateFilterInfo() {
        const filterInfo = document.getElementById('filterInfo');
        if (!filterInfo) return;
        
        const search = searchInput ? searchInput.value : '';
        const campaign = campaignFilter ? campaignFilter.value : '';
        const status = statusFilter ? statusFilter.value : '';
        const type = typeFilter ? typeFilter.value : '';
        const sort = sortFilter ? sortFilter.value : 'latest';
        
        let info = '';
        if (search || campaign || status || type || sort !== 'latest') {
            info = '(Filtrés: ';
            const filters = [];
            
            if (search) filters.push(`"${search}"`);
            if (campaign) {
                const selectedOption = campaignFilter.options[campaignFilter.selectedIndex];
                if (selectedOption) filters.push(`Campagne: ${selectedOption.text}`);
            }
            if (status) {
                const selectedOption = statusFilter.options[statusFilter.selectedIndex];
                if (selectedOption) filters.push(`Statut: ${selectedOption.text}`);
            }
            if (type) {
                const selectedOption = typeFilter.options[typeFilter.selectedIndex];
                if (selectedOption) filters.push(`Type: ${selectedOption.text}`);
            }
            if (sort !== 'latest') {
                const selectedOption = sortFilter.options[sortFilter.selectedIndex];
                if (selectedOption) filters.push(`Tri: ${selectedOption.text}`);
            }
            
            info += filters.join(' | ');
            info += ')';
        }
        
        filterInfo.textContent = info;
    }
    
    // Gérer les boutons de navigation du navigateur
    window.addEventListener('popstate', function() {
        initializeFiltersFromURL();
        updateFilters();
    });
    
    // Confirmation pour les actions de suppression
    document.addEventListener('submit', function(event) {
        const form = event.target;
        if (form.action && form.action.includes('/destroy')) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette ressource ? Cette action est irréversible.')) {
                event.preventDefault();
            }
        }
    });
}

// Initialiser lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    initializeEventHandlers();
});

// Réinitialiser après les chargements AJAX
document.addEventListener('ajaxComplete', function() {
    initializeEventHandlers();
});
</script>
@endpush