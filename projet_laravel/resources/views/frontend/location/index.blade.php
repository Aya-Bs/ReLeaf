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
    </div>

    <!-- Search and Filter Bar -->
    <div class="search-filter-bar bg-white rounded-4 shadow-sm p-3 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <form id="location-search-form" class="search-form position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="location-search-input" class="form-control search-input ps-5" 
                           placeholder="Rechercher un lieu..." value="{{ request('search') }}">
                </form>
            </div>
            <div class="col-md-3">
                <div class="position-relative">
                    <i class="fas fa-filter position-absolute top-50 start-0 translate-middle-y ms-3 text-muted z-3"></i>
                    <select class="form-select filter-select ps-5" id="location-status-filter">
                        <option value="">Tous les statuts</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="in_repair" {{ request('status') == 'in_repair' ? 'selected' : '' }}>En Réparation</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Réservé</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="position-relative">
                    <i class="fas fa-sort position-absolute top-50 start-0 translate-middle-y ms-3 text-muted z-3"></i>
                    <select class="form-select filter-select ps-5" id="location-sort">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('locations.create') }}" class="btn btn-success btn-add rounded-pill px-3">
                    <i class="fas fa-plus me-2"></i>Créer un lieu
                </a>
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
    <div class="locations-table-container bg-white rounded-4 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 cute-table">
                <thead class="table-light">
                    <tr>
                        <th>Lieu</th>
                        <th>Localisation</th>
                        <th>Capacité</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                    <tr class="location-row">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="location-image me-2">
                                    @if($location->images && count($location->images))
                                        <img src="{{ asset('storage/' . $location->images[0]) }}" alt="{{ $location->name }}" class="rounded">
                                    @else
                                        <div class="image-placeholder rounded">
                                            <i class="fas fa-home"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 table-location-name">{{ $location->name }}</h6>
                                    <p class="text-muted small mb-0">{{ Str::limit($location->description, 40) }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                <span class="table-text">{{ $location->city }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-muted me-1"></i>
                                <span class="table-text">{{ $location->capacity ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave text-muted me-1"></i>
                                <span class="table-text">{{ $location->price ? $location->price . ' TND' : 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($location->in_repair)
                                <span class="badge status-badge bg-warning">
                                    <i class="fas fa-tools me-1"></i>En Réparation
                                </span>
                            @elseif($location->reserved)
                                <span class="badge status-badge bg-info">
                                    <i class="fas fa-calendar-check me-1"></i>Réservé
                                </span>
                            @else
                                <span class="badge status-badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Disponible
                                </span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted table-date">{{ $location->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group action-buttons">
                                <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-outline-secondary rounded-circle me-1" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger rounded-circle" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $location->id }}">
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
                        <td colspan="7" class="text-center py-5">
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
{{ $locations->links('frontend.location.custom') }}
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

    /* Search and Filter Bar */
    .search-filter-bar {
        border: 1px solid #e9ecef;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .search-input, .filter-select {
        background-color: #f8f9fa;
        border-radius: 50px;
        padding: 0.6rem 1rem 0.6rem 3rem;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .search-input:focus, .filter-select:focus {
        background-color: #ffffff;
        border-color: #88a096;
        box-shadow: 0 0 0 3px rgba(136, 160, 150, 0.1);
    }

    .search-input {
        padding-left: 3rem;
    }

    .filter-select {
        padding-left: 2.8rem;
        appearance: none;
    }

    /* Table Styles */
    .locations-table-container {
        border: 1px solid #e9ecef;
    }

    .cute-table {
        font-size: 0.85rem;
    }

    .cute-table th {
        border-top: none;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.8rem 0.6rem;
        background-color: #f7fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .cute-table td {
        padding: 0.7rem 0.6rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .location-row:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Location Image */
    .location-image img, .image-placeholder {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }

    .image-placeholder {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #718096;
        font-size: 0.9rem;
    }

    /* Table Text Elements */
    .table-location-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
    }

    .table-text {
        font-size: 0.85rem;
        color: #4a5568;
    }

    .table-date {
        font-size: 0.8rem;
    }

    /* Status Badge */
    .status-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        border-radius: 50px;
        font-weight: 500;
    }

    .status-badge.bg-success {
        background-color: #c6f6d5 !important;
        color: #22543d !important;
    }

    .status-badge.bg-warning {
        background-color: #feebc8 !important;
        color: #744210 !important;
    }

    .status-badge.bg-info {
        background-color: #bee3f8 !important;
        color: #1a365d !important;
    }

    /* Action Buttons */
    .action-buttons .btn {
        padding: 0.3rem;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-width: 1.5px;
        transition: all 0.2s ease;
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* Create Button */
    .btn-add {
        background: linear-gradient(135deg, #2d5a27 0%, #3a7c30 100%);
        border: none;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(45, 90, 39, 0.3);
    }

    /* Table Footer */
    .table-footer {
        background-color: #f7fafc;
        font-size: 0.85rem;
    }

    /* Empty State */
    .empty-state {
        padding: 2rem 0;
    }

    
    /* Responsive */
    @media (max-width: 768px) {
        .search-filter-bar .row > div {
            margin-bottom: 1rem;
        }
        
        .cute-table {
            font-size: 0.8rem;
        }
        
        .action-buttons .btn {
            width: 28px;
            height: 28px;
            padding: 0.25rem;
        }
        
        .location-image img, .image-placeholder {
            width: 35px;
            height: 35px;
        }
    }



    /* Pagination Styles - CONSISTENT HORIZONTAL */
.pagination {
    display: flex !important;
    list-style: none !important;
    padding: 0 !important;
    margin-left: 400px !important;
    margin-top: 20px !important;
    gap: 6px !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
    font-size: 0.85rem !important;
}

.pagination li {
    display: inline-block !important;
    margin: 400px !important;
    margin-top: 20px !important;
    padding: 0 !important;
}

/* TARGET ALL PAGINATION ELEMENTS - FORCE CONSISTENT STYLING */
.pagination a,
.pagination span,
.pagination .page-link,
.pagination [rel="prev"],
.pagination [rel="next"],
.pagination .disabled span,
.pagination .active span {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 8px 12px !important;
    border: 2px solid #e2e8f0 !important;
    border-radius: 8px !important;
    color: #2d5a27 !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    background: white !important;
    transition: all 0.3s ease !important;
    min-width: 40px !important;
    height: 40px !important;
    line-height: 1 !important;
    text-align: center !important;
    box-sizing: border-box !important;
}

/* Hover states for clickable links */
.pagination a:hover {
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%) !important;
    color: white !important;
    border-color: #2d5a27 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(45, 90, 39, 0.2) !important;
}

/* Active page */
.pagination .active a,
.pagination .active span,
.pagination [aria-current="page"] {
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%) !important;
    border-color: #2d5a27 !important;
    color: white !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3) !important;
}

/* Disabled states */
.pagination .disabled a,
.pagination .disabled span,
.pagination [aria-disabled="true"] {
    background: #f8f9fa !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Make sure all elements have the same dimensions */
.pagination li:first-child a,
.pagination li:first-child span,
.pagination li:last-child a,
.pagination li:last-child span,
.pagination [rel="prev"],
.pagination [rel="next"] {
    min-width: 70px !important;
    font-weight: 700 !important;
}

/* Page numbers - ensure consistent size */
.pagination li:not(:first-child):not(:last-child) a,
.pagination li:not(:first-child):not(:last-child) span {
    min-width: 40px !important;
}

/* Hide any text elements that break the layout */
.pagination > div:first-child {
    display: none !important;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination {
        gap: 4px !important;
    }
    
    .pagination a,
    .pagination span,
    .pagination .page-link {
        padding: 6px 10px !important;
        min-width: 35px !important;
        height: 35px !important;
        font-size: 0.8rem !important;
    }
    
    .pagination li:first-child a,
    .pagination li:first-child span,
    .pagination li:last-child a,
    .pagination li:last-child span {
        min-width: 60px !important;
    }
}

@media (max-width: 576px) {
    .pagination a,
    .pagination span,
    .pagination .page-link {
        padding: 5px 8px !important;
        min-width: 32px !important;
        height: 32px !important;
        font-size: 0.75rem !important;
    }
    
    .pagination li:first-child a,
    .pagination li:first-child span,
    .pagination li:last-child a,
    .pagination li:last-child span {
        min-width: 55px !important;
    }
}

</style>

<script>


// Force consistent pagination styling for locations page
document.addEventListener('DOMContentLoaded', function() {
    function forceConsistentPagination() {
        const paginations = document.querySelectorAll('.pagination');
        
        paginations.forEach(pagination => {
            const items = pagination.querySelectorAll('li');
            
            items.forEach(item => {
                // Force inline display
                item.style.cssText = `
                    display: inline-block !important;
                    margin: 0 3px !important;
                    padding: 0 !important;
                    vertical-align: middle !important;
                `;
                
                // Style all links and spans
                const elements = item.querySelectorAll('a, span');
                elements.forEach(el => {
                    el.style.cssText = `
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        padding: 8px 12px !important;
                        border: 2px solid #e2e8f0 !important;
                        border-radius: 8px !important;
                        color: #2d5a27 !important;
                        text-decoration: none !important;
                        font-weight: 600 !important;
                        font-size: 0.85rem !important;
                        background: white !important;
                        transition: all 0.3s ease !important;
                        min-width: 40px !important;
                        height: 40px !important;
                        line-height: 1 !important;
                        text-align: center !important;
                        box-sizing: border-box !important;
                    `;
                    
                    // Check if it's active
                    if (item.classList.contains('active') || el.getAttribute('aria-current') === 'page') {
                        el.style.cssText += `
                            background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%) !important;
                            border-color: #2d5a27 !important;
                            color: white !important;
                            transform: translateY(-1px) !important;
                            box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3) !important;
                        `;
                    }
                    
                    // Check if it's disabled
                    if (item.classList.contains('disabled') || el.getAttribute('aria-disabled') === 'true') {
                        el.style.cssText += `
                            background: #f8f9fa !important;
                            border-color: #dee2e6 !important;
                            color: #6c757d !important;
                            cursor: not-allowed !important;
                            opacity: 0.6 !important;
                            transform: none !important;
                            box-shadow: none !important;
                        `;
                    }
                    
                    // Style previous/next buttons
                    const text = el.textContent?.trim().toLowerCase() || '';
                    if (text.includes('prev') || text.includes('next') || text.includes('‹') || text.includes('›')) {
                        el.style.cssText += `
                            min-width: 70px !important;
                            font-weight: 700 !important;
                        `;
                    }
                    
                    // Add hover effect for clickable links
                    if (el.tagName === 'A' && !item.classList.contains('disabled')) {
                        el.addEventListener('mouseenter', function() {
                            if (!item.classList.contains('active')) {
                                this.style.cssText += `
                                    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%) !important;
                                    color: white !important;
                                    border-color: #2d5a27 !important;
                                    transform: translateY(-2px) !important;
                                    box-shadow: 0 4px 12px rgba(45, 90, 39, 0.2) !important;
                                `;
                            }
                        });
                        
                        el.addEventListener('mouseleave', function() {
                            if (!item.classList.contains('active')) {
                                const isNav = text.includes('prev') || text.includes('next') || text.includes('‹') || text.includes('›');
                                this.style.cssText = `
                                    display: inline-flex !important;
                                    align-items: center !important;
                                    justify-content: center !important;
                                    padding: 8px 12px !important;
                                    border: 2px solid #e2e8f0 !important;
                                    border-radius: 8px !important;
                                    color: #2d5a27 !important;
                                    text-decoration: none !important;
                                    font-weight: ${isNav ? '700' : '600'} !important;
                                    font-size: 0.85rem !important;
                                    background: white !important;
                                    transition: all 0.3s ease !important;
                                    min-width: ${isNav ? '70' : '40'}px !important;
                                    height: 40px !important;
                                    line-height: 1 !important;
                                    text-align: center !important;
                                    box-sizing: border-box !important;
                                `;
                            }
                        });
                    }
                });
            });
        });
    }
    
    // Run multiple times
    forceConsistentPagination();
    setTimeout(forceConsistentPagination, 100);
    setTimeout(forceConsistentPagination, 500);
});


document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('location-search-input');
    const statusFilter = document.getElementById('location-status-filter');
    const sortFilter = document.getElementById('location-sort');
    let searchTimer = null;

    // Real-time search with debouncing
    searchInput && searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(updateFilters, 350);
    });

    // Real-time filter changes
    statusFilter && statusFilter.addEventListener('change', updateFilters);
    sortFilter && sortFilter.addEventListener('change', updateFilters);

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