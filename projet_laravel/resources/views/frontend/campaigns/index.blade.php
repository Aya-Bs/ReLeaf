@extends('layouts.frontend')

@section('title', 'Gestion des Campagnes')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Gestion des Campagnes</h1>
                    <p class="text-muted mb-0">Gérez et organisez vos campagnes écologiques pour maximiser leur impact</p>
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
                        
                        <!-- Category Filter -->
                        <div>
                            <select id="categoryFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Toutes les catégories</option>
                                @foreach(['reforestation' => '🌲 Reforestation', 'nettoyage' => '🧹 Nettoyage', 'sensibilisation' => '📢 Sensibilisation', 'recyclage' => '♻️ Recyclage', 'biodiversite' => '🦋 Biodiversité', 'energie_renouvelable' => '⚡ Énergie Renouvelable', 'autre' => '🔧 Autre'] as $category => $label)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Terminée</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>⏸️ En pause</option>
                            </select>
                        </div>
                        
                        <!-- Sort Filter -->
                        <div>
                            <select id="sortFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Par nom</option>
                                <option value="funds" {{ request('sort') == 'funds' ? 'selected' : '' }}>Par financement</option>
                            </select>
                        </div>
                        
                        <!-- Clear Filters Button -->
                        <button id="clearFilters" class="btn btn-outline-eco btn-sm">
                            <i class="fas fa-times me-1"></i>Effacer
                        </button>
                        
                        <!-- Create Campaign Button -->
                        <a href="{{ route('campaigns.create') }}" class="btn btn-eco btn-sm">
                            <i class="fas fa-plus me-2"></i>Créer une campagne
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
        <p class="mt-2 text-muted">Filtrage des campagnes...</p>
    </div>

    <!-- Content Container -->
    <div id="contentContainer">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    Liste des campagnes 
                    <small id="filterInfo" class="text-muted"></small>
                </h5>
            </div>
            <div class="card-body">
                @if($campaigns->count() > 0)
                    <!-- Campaigns Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Statut</th>
                                    <th>Dates</th>
                                    <th>Financement</th>
                                    <th>Participants</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($campaigns as $campaign)
                                <tr>
                                    <td>
                                        @if($campaign->image_url)
                                            <img src="{{ Storage::url($campaign->image_url) }}" 
                                                 alt="{{ $campaign->name }}" 
                                                 class="campaign-image-table"
                                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjYwIiBmaWxsPSIjNkM3NTdEIi8+CjxwYXRoIGQ9Ik0zMCAzN0MzMy44NjYgMzcgMzcgMzMuODY2IDM3IDMwQzM3IDI2LjEzNCAzMy44NjYgMjMgMzAgMjNDMjYuMTM0IDIzIDIzIDI2LjEzNCAyMyAzMEMyMyAzMy44NjYgMjYuMTM0IDM3IDMwIDM3Wk0zMCAxNkMzNS41MjIgMTYgNDAgMTguMjM4IDQwIDIxVjM5QzQwIDQxLjc2MiAzNS41MjIgNDQgMzAgNDRDMjQuNDc4IDQ0IDIwIDQxLjc2MiAyMCAzOVYyMUMyMCAxOC4yMzggMjQuNDc4IDE2IDMwIDE2WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+'">
                                        @else
                                            <div class="campaign-image-placeholder-table">
                                                <i class="fas fa-leaf text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $campaign->name }}</strong>
                                        @if(!$campaign->visibility)
                                            <br><small class="text-warning"><i class="fas fa-eye-slash me-1"></i>Privée</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
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
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $campaign->status == 'active' ? 'eco' : ($campaign->status == 'completed' ? 'eco' : ($campaign->status == 'eco' ? 'eco-danger' : 'eco')) }}">
                                            @switch($campaign->status)
                                                @case('active') 🟢 @break
                                                @case('completed') ✅ @break
                                                @case('cancelled') ❌ @break
                                                @default ⏸️ @break
                                            @endswitch
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            <strong>{{ $campaign->start_date->format('d/m/Y') }}</strong><br>
                                            <span class="text-muted">au {{ $campaign->end_date->format('d/m/Y') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar bg-eco" style="width: {{ $campaign->funds_progress_percentage }}%"></div>
                                        </div>
                                        <small>
                                            <strong>{{ number_format($campaign->funds_raised, 0, ',', ' ') }} €</strong>
                                            @if($campaign->goal)
                                                / {{ number_format($campaign->goal, 0, ',', ' ') }} €
                                            @endif
                                            <br>
                                            <span class="text-muted">{{ $campaign->funds_progress_percentage }}% atteint</span>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-eco">{{ $campaign->participants_count }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-outline-eco" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline-eco" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('campaigns.toggle-visibility', $campaign) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-eco" title="{{ $campaign->visibility ? 'Rendre privée' : 'Rendre publique' }}">
                                                    <i class="fas fa-{{ $campaign->visibility ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-eco-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?')" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($campaigns->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
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
                    @endif
                @else
                <div class="text-center py-5">
                    <i class="fas fa-leaf fa-3x text-eco mb-3"></i>
                    <p class="text-muted" id="emptyMessage">
                        @if(request()->has('search') || request()->has('category') || request()->has('status') || request()->has('sort'))
                            Aucune campagne trouvée avec les critères de recherche.
                        @else
                            Aucune campagne créée pour le moment.
                        @endif
                    </p>
                    <a href="{{ route('campaigns.create') }}" class="btn btn-eco">Créer votre première campagne</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .campaign-image-table {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    
    .campaign-image-placeholder-table {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #2d5a27 0%, #3a7c30 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid #e9ecef;
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
    
    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        height: 6px !important;
        min-width: 80px;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        margin: 0 2px;
    }
    
    /* Boutons cohérents avec le thème écologique */
    .btn-eco {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }
    
    .btn-eco:hover {
        background-color: var(--eco-green-dark);
        border-color: var(--eco-green-dark);
        color: white;
    }
    
    .btn-outline-eco {
        border-color: var(--eco-green);
        color: var(--eco-green);
    }
    
    .btn-outline-eco:hover {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }
    
    .btn-outline-eco-danger {
        border-color: #dc3545;
        color: #dc3545;
    }
    
    .btn-outline-eco-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    /* Badges écologiques */
    .bg-eco {
        background-color: var(--eco-green) !important;
    }
    
    .bg-eco-light {
        background-color: #e8f5e8 !important;
    }
    
    .bg-eco-success {
        background-color: #28a745 !important;
    }
    
    .bg-eco-danger {
        background-color: #dc3545 !important;
    }
    
    .bg-eco-secondary {
        background-color: #6c757d !important;
    }
    
    .text-eco {
        color: var(--eco-green) !important;
    }
    
    .text-eco-dark {
        color: #2d5a27 !important;
    }
    
    .bg-eco {
        background-color: var(--eco-green) !important;
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
            margin: 0 1px;
        }
        
        .campaign-image-table,
        .campaign-image-placeholder-table {
            width: 40px;
            height: 40px;
        }
        
        .pagination-links .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
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
    if (urlParams.get('category')) {
        categoryFilter.value = urlParams.get('category');
    }
    if (urlParams.get('status')) {
        statusFilter.value = urlParams.get('status');
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
    categoryFilter.addEventListener('change', updateFilters);
    statusFilter.addEventListener('change', updateFilters);
    sortFilter.addEventListener('change', updateFilters);

    // Clear filters event
    clearFilters.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
        statusFilter.value = '';
        sortFilter.value = 'latest';
        updateFilters();
    });

    function updateFilters() {
        // Show loading
        loadingIndicator.style.display = 'block';
        contentContainer.style.opacity = '0.5';

        const filters = {
            search: searchInput.value,
            category: categoryFilter.value,
            status: statusFilter.value,
            sort: sortFilter.value
        };

        // Update URL without page reload
        const url = new URL(window.location);
        if (filters.search) {
            url.searchParams.set('search', filters.search);
        } else {
            url.searchParams.delete('search');
        }
        if (filters.category) {
            url.searchParams.set('category', filters.category);
        } else {
            url.searchParams.delete('category');
        }
        if (filters.status) {
            url.searchParams.set('status', filters.status);
        } else {
            url.searchParams.delete('status');
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
        const category = categoryFilter.value;
        const status = statusFilter.value;
        const sort = sortFilter.value;
        
        let info = '';
        if (search || category || status || sort !== 'latest') {
            info = '(Filtrés: ';
            const filters = [];
            
            if (search) filters.push(`"${search}"`);
            if (category) filters.push(`Catégorie: ${categoryFilter.options[categoryFilter.selectedIndex].text}`);
            if (status) filters.push(`Statut: ${statusFilter.options[statusFilter.selectedIndex].text}`);
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
        categoryFilter.value = urlParams.get('category') || '';
        statusFilter.value = urlParams.get('status') || '';
        sortFilter.value = urlParams.get('sort') || 'latest';
        updateFilters();
    });
});
</script>
@endpush