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
                        
                        <!-- Type Filter -->
                        <div>
                            <select id="typeFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Tous les types</option>
                                <option value="money" {{ request('resource_type') == 'money' ? 'selected' : '' }}>💰 Argent</option>
                                <option value="food" {{ request('resource_type') == 'food' ? 'selected' : '' }}>🍎 Nourriture</option>
                                <option value="clothing" {{ request('resource_type') == 'clothing' ? 'selected' : '' }}>👕 Vêtements</option>
                                <option value="medical" {{ request('resource_type') == 'medical' ? 'selected' : '' }}>🏥 Médical</option>
                                <option value="equipment" {{ request('resource_type') == 'equipment' ? 'selected' : '' }}>🛠️ Équipement</option>
                                <option value="human" {{ request('resource_type') == 'human' ? 'selected' : '' }}>👥 Main d'œuvre</option>
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

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-4" style="display: none;">
        <div class="spinner-border text-eco" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="mt-2 text-muted">Filtrage des ressources...</p>
    </div>

    <!-- Content Container -->
    <div id="contentContainer">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    Liste des ressources 
                    <small id="filterInfo" class="text-muted"></small>
                </h5>
            </div>
            <div class="card-body">
                @if($resources->count() > 0)
                    <!-- Resources Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Nom</th>
                                    <th>Campagne</th>
                                    <th>Statut</th>
                                    <th>Priorité</th>
                                    <th>Progression</th>
                                    <th>Quantité</th>
                                    <th>Fournisseur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resources as $resource)
                                <tr>
                                    <td>
                                        <span class="resource-type-icon">
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
                                    </td>
                                    <td>
                                        <strong>{{ $resource->name }}</strong>
                                        @if($resource->description)
                                            <br><small class="text-muted">{{ Str::limit($resource->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('campaigns.show', $resource->campaign) }}" class="campaign-link">
                                            {{ Str::limit($resource->campaign->name, 25) }}
                                        </a>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $resource->priority == 'urgent' ? 'danger' : ($resource->priority == 'high' ? 'warning' : ($resource->priority == 'medium' ? 'info' : 'success')) }}">
                                            @switch($resource->priority)
                                                @case('urgent') 🚨 @break
                                                @case('high') ⚠️ @break
                                                @case('medium') 📊 @break
                                                @default 📌 @break
                                            @endswitch
                                            {{ ucfirst($resource->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : ($resource->progress_percentage > 50 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $resource->progress_percentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $resource->progress_percentage }}%</small>
                                    </td>
                                    <td>
                                        <div class="quantity-info">
                                            <strong class="text-success">{{ $resource->quantity_pledged }}</strong>
                                            <span class="text-muted">/</span>
                                            <strong class="text-eco">{{ $resource->quantity_needed }}</strong>
                                            <small class="text-muted d-block">{{ $resource->unit }}</small>
                                        </div>
                                        @if($resource->missing_quantity > 0)
                                            <small class="text-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Manque {{ $resource->missing_quantity }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($resource->provider)
                                            <small>{{ Str::limit($resource->provider, 20) }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('resources.show', $resource) }}" class="btn btn-outline-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('resources.edit', $resource) }}" class="btn btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle" 
                                                        type="button" data-bs-toggle="dropdown"
                                                        title="Changer statut">
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .resource-type-icon {
        font-size: 1.25rem;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #2d5a27;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    .campaign-link {
        color: var(--eco-green);
        text-decoration: none;
        font-weight: 500;
    }
    
    .campaign-link:hover {
        color: var(--eco-light-green);
        text-decoration: underline;
    }
    
    .quantity-info {
        font-size: 0.9rem;
        text-align: center;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        height: 6px !important;
        min-width: 80px;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .dropdown-menu {
        min-width: 150px;
    }
    
    .dropdown-item.active {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
    }
    
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
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group-sm > .btn {
            padding: 0.125rem 0.25rem;
        }
        
        .quantity-info {
            font-size: 0.8rem;
        }
        
        .pagination-links .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .table th:nth-child(4),
        .table td:nth-child(4),
        .table th:nth-child(8),
        .table td:nth-child(8) {
            display: none;
        }
    }
    
    @media (max-width: 576px) {
        .table th:nth-child(5),
        .table td:nth-child(5),
        .table th:nth-child(7),
        .table td:nth-child(7) {
            display: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const campaignFilter = document.getElementById('campaignFilter');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const sortFilter = document.getElementById('sortFilter');
    const clearFilters = document.getElementById('clearFilters');
    const contentContainer = document.getElementById('contentContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const filterInfo = document.getElementById('filterInfo');

    let searchTimeout;

    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.get('campaign_id')) {
        campaignFilter.value = urlParams.get('campaign_id');
    }
    if (urlParams.get('status')) {
        statusFilter.value = urlParams.get('status');
    }
    if (urlParams.get('resource_type')) {
        typeFilter.value = urlParams.get('resource_type');
    }
    if (urlParams.get('sort')) {
        sortFilter.value = urlParams.get('sort');
    }
    updateFilterInfo();

    // Search input event
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateFilters, 500);
    });

    // Filter events
    campaignFilter.addEventListener('change', updateFilters);
    statusFilter.addEventListener('change', updateFilters);
    typeFilter.addEventListener('change', updateFilters);
    sortFilter.addEventListener('change', updateFilters);

    // Clear filters event
    clearFilters.addEventListener('click', function() {
        searchInput.value = '';
        campaignFilter.value = '';
        statusFilter.value = '';
        typeFilter.value = '';
        sortFilter.value = 'latest';
        updateFilters();
    });

    function updateFilters() {
        // Show loading
        loadingIndicator.style.display = 'block';
        contentContainer.style.opacity = '0.5';

        const filters = {
            search: searchInput.value,
            campaign_id: campaignFilter.value,
            status: statusFilter.value,
            resource_type: typeFilter.value,
            sort: sortFilter.value
        };

        // Update URL without page reload
        const url = new URL(window.location);
        if (filters.search) {
            url.searchParams.set('search', filters.search);
        } else {
            url.searchParams.delete('search');
        }
        if (filters.campaign_id) {
            url.searchParams.set('campaign_id', filters.campaign_id);
        } else {
            url.searchParams.delete('campaign_id');
        }
        if (filters.status) {
            url.searchParams.set('status', filters.status);
        } else {
            url.searchParams.delete('status');
        }
        if (filters.resource_type) {
            url.searchParams.set('resource_type', filters.resource_type);
        } else {
            url.searchParams.delete('resource_type');
        }
        if (filters.sort) {
            url.searchParams.set('sort', filters.sort);
        } else {
            url.searchParams.delete('sort');
        }
        window.history.pushState({}, '', url);

        // Fetch updated content
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extract the content container from the response
            const newContent = doc.getElementById('contentContainer');
            if (newContent) {
                contentContainer.innerHTML = newContent.innerHTML;
            }
            
            updateFilterInfo();
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Hide loading
            loadingIndicator.style.display = 'none';
            contentContainer.style.opacity = '1';
        });
    }

    function updateFilterInfo() {
        const search = searchInput.value;
        const campaign = campaignFilter.value;
        const status = statusFilter.value;
        const type = typeFilter.value;
        const sort = sortFilter.value;
        
        let info = '';
        if (search || campaign || status || type || sort !== 'latest') {
            info = '(Filtrés: ';
            const filters = [];
            
            if (search) filters.push(`"${search}"`);
            if (campaign) filters.push(`Campagne: ${campaignFilter.options[campaignFilter.selectedIndex].text}`);
            if (status) filters.push(`Statut: ${statusFilter.options[statusFilter.selectedIndex].text}`);
            if (type) filters.push(`Type: ${typeFilter.options[typeFilter.selectedIndex].text}`);
            if (sort !== 'latest') filters.push(`Tri: ${sortFilter.options[sortFilter.selectedIndex].text}`);
            
            info += filters.join(' | ');
            info += ')';
        }
        
        filterInfo.textContent = info;
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        searchInput.value = urlParams.get('search') || '';
        campaignFilter.value = urlParams.get('campaign_id') || '';
        statusFilter.value = urlParams.get('status') || '';
        typeFilter.value = urlParams.get('resource_type') || '';
        sortFilter.value = urlParams.get('sort') || 'latest';
        updateFilters();
    });

    // Confirmation pour les actions sensibles
    const deleteForms = document.querySelectorAll('form[action*="/destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette ressource ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush