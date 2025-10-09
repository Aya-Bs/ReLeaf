@extends('layouts.frontend')

@section('title', 'Lieux')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            
            <h1 class="page-title mb-0">Mes Lieux</h1>
            <p class="text-muted mb-0">Gérez vos espaces de location</p>
        </div>
        <a href="{{ route('locations.create') }}" class="btn btn-success btn-md rounded-pill px-3 py-2">
            <i class="fas fa-plus me-2"></i>Créer un lieu
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-primary text-white rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $totalLocations }}</h4>
                        <small>Total Lieux</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-success text-white rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $availableLocations }}</h4>
                        <small>Disponibles</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-warning text-white rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-tools fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $inRepairLocations }}</h4>
                        <small>En Réparation</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-info text-white rounded-3 p-3">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $reservedLocations }}</h4>
                        <small>Réservés</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="search-filter-bar bg-white rounded-3 shadow-sm p-3 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <form id="location-search-form" class="search-form position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="location-search-input" class="form-control border-0 ps-5" 
                           placeholder="Rechercher un lieu..." value="{{ request('search') }}">
                </form>
            </div>
            <div class="col-md-3">
                <select class="form-select border-0" id="location-status-filter">
                    <option value="">Tous les statuts</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="in_repair" {{ request('status') == 'in_repair' ? 'selected' : '' }}>En Réparation</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Réservé</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select border-0" id="location-sort">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" id="clear-filters" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-times me-1"></i>Effacer
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Locations Table -->
    <div class="locations-table-container bg-white rounded-3 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        
                        <th>Lieu</th>
                        <th>Localisation</th>
                        <th>Capacité</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                    <tr class="location-row">
                       
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="location-image me-3">
                                    @if($location->images && count($location->images))
                                        <img src="{{ asset('storage/' . $location->images[0]) }}" alt="{{ $location->name }}" class="rounded">
                                    @else
                                        <div class="image-placeholder rounded">
                                            <i class="fas fa-home"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $location->name }}</h6>
                                    <p class="text-muted small mb-0">{{ Str::limit($location->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <span>{{ $location->city }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-muted me-2"></i>
                                <span>{{ $location->capacity ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-euro-sign text-muted me-2"></i>
                                <span>{{ $location->price ? $location->price . ' €' : 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($location->in_repair)
                                <span class="badge status-badge bg-warning">
                                    <i class="fas fa-tools me-1 small"></i>En Réparation
                                </span>
                            @elseif($location->reserved)
                                <span class="badge status-badge bg-info">
                                    <i class="fas fa-calendar-check me-1 small"></i>Réservé
                                </span>
                            @else
                                <span class="badge status-badge bg-success">
                                    <i class="fas fa-check-circle me-1 small"></i>Disponible
                                </span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $location->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-outline-secondary rounded-pill me-1" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger rounded-pill" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $location->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $location->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmer la suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Êtes-vous sûr de vouloir supprimer le lieu "{{ $location->name }}" ? Cette action est irréversible.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form action="{{ route('locations.destroy', $location) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Aucun lieu enregistré</h4>
                                <p class="text-muted mb-4">Commencez par créer votre premier lieu de location.</p>
                                <a href="{{ route('locations.create') }}" class="btn btn-success rounded-pill px-4">
                                    <i class="fas fa-plus me-2"></i>Créer un lieu
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer -->
        @if($locations->count() > 0)
        <div class="table-footer d-flex justify-content-between align-items-center p-3 border-top">
            
            <div>
                {{ $locations->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
       /* Custom Styles */
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2d5a27;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-item a {
        color: #6c757d;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #2d5a27;
        font-weight: 500;
    }

    .btn-success {
        background-color: #2d5a27;
        border-color: #2d5a27;
        font-weight: 600;
    }

    .btn-success:hover {
        background-color: #234a21;
        border-color: #234a21;
    }

    /* Stat Cards */
    .stat-card {
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        opacity: 0.8;
    }

    /* Search and Filter */
    .search-filter-bar {
        border: 1px solid #e9ecef;
    }

    .search-form .form-control {
        background-color: #f8f9fa;
        border-radius: 50px;
        padding: 0.75rem 1rem 0.75rem 3rem;
    }

    /* Table Styles */
    .locations-table-container {
        border: 1px solid #e9ecef;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
    }

    .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .location-row:hover {
        background-color: #f8f9fa;
    }

    /* Location Image */
    .location-image img, .image-placeholder {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .image-placeholder {
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    /* Status Badge */
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
    }

    .status-badge.bg-success {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }

    .status-badge.bg-warning {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }

    .status-badge.bg-info {
        background-color: #dbeafe !important;
        color: #1e40af !important;
    }

    /* Action Buttons */
    .btn-group .btn {
        padding: 0.375rem 0.75rem;
    }

    /* Table Footer */
    .table-footer {
        background-color: #f8f9fa;
    }

    .bulk-actions {
        min-width: 160px;
    }

    /* Empty State */
    .empty-state {
        padding: 2rem 0;
    }

    /* Loading State */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .search-filter-bar .row > div {
            margin-bottom: 1rem;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('location-search-input');
    const statusFilter = document.getElementById('location-status-filter');
    const sortFilter = document.getElementById('location-sort');
    const clearFiltersBtn = document.getElementById('clear-filters');
    let searchTimer = null;

    // Real-time search with debouncing
    searchInput && searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(updateFilters, 350);
    });

    // Real-time filter changes
    statusFilter && statusFilter.addEventListener('change', updateFilters);
    sortFilter && sortFilter.addEventListener('change', updateFilters);

    // Clear all filters
    clearFiltersBtn && clearFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        sortFilter.value = 'newest';
        updateFilters();
    });

    function updateFilters() {
        const searchValue = searchInput.value.trim();
        const statusValue = statusFilter.value;
        const sortValue = sortFilter.value;

        const url = new URL(window.location.href);
        
        // Update search parameter
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        
        // Update status parameter
        if (statusValue) {
            url.searchParams.set('status', statusValue);
        } else {
            url.searchParams.delete('status');
        }
        
        // Update sort parameter
        if (sortValue && sortValue !== 'newest') {
            url.searchParams.set('sort', sortValue);
        } else {
            url.searchParams.delete('sort');
        }
        
        // Always reset to first page when filters change
        url.searchParams.delete('page');
        
        // Navigate to new URL
        window.location.href = url.toString();
    }
});
</script>
@endsection