@extends('layouts.frontend')

@section('title', 'Mes Événements')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title">Mes Événements</h1>
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Input -->
                    <div class="position-relative me-2">
                        <input type="text" 
                               id="searchInput" 
                               class="form-control form-control-sm search-input" 
                               placeholder="Recherche..." 
                               value="{{ request('search') }}">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="me-2">
                        <select id="statusFilter" class="form-select form-select-sm filter-select">
                            <option value="">Tous les statuts</option>
                            <option value="draft">Brouillon</option>
                            <option value="pending">En attente</option>
                            <option value="published">Publié</option>
                            <option value="cancelled">Annulé</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                    </div>
                    
                    <!-- Create Event Button -->
                    <a href="{{ route('events.create') }}" class="btn btn-eco btn-create">
                        <i class="fas fa-plus me-2"></i>Créer un événement
                    </a>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="loading-indicator" style="display: none;">
                <div class="spinner-border text-eco" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Filtrage des événements...</p>
            </div>

            <!-- Content Container -->
            <div id="contentContainer">
                <!-- Pending Events -->
                <!-- Pending Events -->
@if($pendingEvents->count() > 0 && !request()->has('status') && !request()->has('search'))
<div class="card mb-4 border-warning" style="transform: scale(0.95); transform-origin: top center;">
    <div class="card-header bg-warning text-dark py-2">
        <h5 class="mb-0" style="font-size: 0.9rem;">
            <i class="fas fa-clock me-2"></i>En attente d'approbation ({{ $pendingEvents->count() }})
        </h5>
    </div>
    <div class="card-body py-2">
        <div class="row">
            @foreach($pendingEvents as $event)
            <div class="col-md-3 mb-2">
                <div class="card h-100" style="font-size: 0.85rem;">
                    <div class="card-body py-2">
                        <h6 class="card-title mb-1" style="font-size: 0.9rem;">{{ $event->title }}</h6>
                        <p class="card-text small text-muted mb-1">
                            <i class="fas fa-calendar me-1"></i>{{ $event->date->format('d/m/Y H:i') }}<br>
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location ? $event->location->name : '' }}
                        </p>
                        <span class="badge bg-warning" style="font-size: 0.7rem;">En attente</span>
                    </div>
                    <div class="card-footer py-1">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-info" style="padding: 0.15rem 0.4rem; margin-left:170px;">
                            <i class="fas fa-eye" style="font-size: 0.7rem;"></i>
                        </a>
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-primary" style="padding: 0.15rem 0.4rem;">
                            <i class="fas fa-edit" style="font-size: 0.7rem;"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger" disabled style="padding: 0.15rem 0.4rem;">
                            <i class="fas fa-trash" style="font-size: 0.7rem;"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

                <!-- Other Events -->
                <div class="events-table-section">
                    
                    <div class="table-container">
                        @if($otherEvents->count() > 0)
                        <div class="table-responsive">
                            <table class="table cute-table">
                                <thead>
                                    <tr>
                                        <th class="col-title">Titre</th>
                                        <th class="col-date">Date</th>
                                        <th class="col-location">Lieu</th>
                                        <th class="col-status">Statut</th>
                                        <th class="col-actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($otherEvents as $event)
                                    <tr class="event-row">
                                        <td class="event-title">
                                            <div class="title-wrapper">
                                                <span class="title-text">{{ $event->title }}</span>
                                            </div>
                                        </td>
                                        <td class="event-date">
                                            <div class="date-wrapper">
                                                <i class="fas fa-calendar me-1"></i>
                                                <span>{{ $event->date->format('d/m/Y H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="event-location">
                                            <div class="location-wrapper">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <span>{{ $event->location ? $event->location->name : 'Non spécifié' }}</span>
                                            </div>
                                        </td>
                                        <td class="event-status">
                                            @if($event->isDraft())
                                                <span class="status-badge badge-draft">Brouillon</span>
                                            @elseif($event->isPublished())
                                                <span class="status-badge badge-published">Publié</span>
                                            @elseif($event->isCancelled())
                                                <span class="status-badge badge-cancelled">Annulé</span>
                                            @elseif($event->status === 'rejected')
                                                <span class="status-badge badge-rejected">Rejeté</span>
                                            @elseif($event->isPending())
                                                <span class="status-badge badge-pending">En attente</span>
                                            @endif
                                        </td>
                                        <td class="event-actions">
                                            <div class="action-buttons">
                                                <!-- View Button - Always visible -->
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-action btn-view" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Reserve Button - Only show for published events -->
                                                @if($event->isPublished())
                                                    @php
                                                        // Vérifier si l'utilisateur a une réservation pour cet événement
                                                        $userReservation = $event->reservations->first();
                                                    @endphp
                                                    
                                                    @if($userReservation)
                                                        <!-- Bouton "Voir réservation" si l'utilisateur a déjà réservé -->
                                                        <a href="{{ route('reservations.confirmation', $userReservation) }}" 
                                                           class="btn btn-outline-primary" 
                                                           title="Voir ma réservation">
                                                            <i class="fas fa-ticket-alt"></i>
                                                        </a>
                                                    @else
                                                        <!-- Bouton "Réserver" si l'utilisateur n'a pas encore réservé -->
                                                        <a href="/events/{{ $event->id }}/seats" 
                                                           class="btn btn-outline-success" 
                                                           title="Réserver">
                                                            <i class="fas fa-calendar-check"></i>
                                                        </a>
                                                    @endif
                                                @endif

                                                <!-- Edit Button - Only show for draft and pending status -->
                                                @if($event->isDraft() || $event->isPending())
                                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-action btn-edit" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                <!-- Submit Button - Only for draft events -->
                                                @if($event->isDraft())
                                                <form action="{{ route('events.submit', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-action btn-submit" title="Soumettre">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                <!-- Cancel Button - Only for published events -->
                                                @if($event->isPublished())
                                                <form action="{{ route('events.cancel', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-action btn-cancel" title="Annuler">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                <!-- Delete Button - Hide for published events, show for others if they can be deleted -->
                                                @if(!$event->isPublished())
                                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-action btn-delete" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')"
                                                            {{ !$event->canBeDeleted() ? 'disabled' : '' }}
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times empty-icon"></i>
                            <p class="empty-message" id="emptyMessage">
                                @if(request()->has('search') || request()->has('status'))
                                    Aucun événement trouvé avec les critères de recherche.
                                @else
                                    Aucun événement créé pour le moment.
                                @endif
                            </p>
                            <a href="{{ route('events.create') }}" class="btn btn-eco">Créer votre premier événement</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles for Events Page */
.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d5a27;
    margin: 0;
}

/* Search and Filter Styles */
.search-input {
    width: 200px;
    border-radius: 20px;
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    border: 1.5px solid #e2e8f0;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #2d5a27;
    box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.1);
}

.search-icon {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    font-size: 0.8rem;
    color: #6b7280;
}

.filter-select {
    width: 180px;
    border-radius: 20px;
    padding: 0.5rem 1rem;
    border: 1.5px solid #e2e8f0;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.filter-select:focus {
    border-color: #2d5a27;
    box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.1);
}

.btn-create {
    border-radius: 20px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45, 90, 39, 0.2);
}

/* Loading Indicator */
.loading-indicator {
    text-align: center;
    padding: 2rem;
}

/* Pending Events Section */
.pending-events-section {
    background: linear-gradient(135deg, #fff9ed 0%, #fff3e0 100%);
    border: 1px solid #ffd54f;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(255, 193, 7, 0.1);
}

.pending-header {
    background: linear-gradient(135deg, #ffb300 0%, #ffa000 100%);
    padding: 1rem 1.5rem;
    color: white;
}

.pending-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.pending-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
    margin-left: 0.5rem;
}

.pending-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.pending-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #ffe082;
    transition: all 0.3s ease;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.1);
}

.pending-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
}

.pending-card-body {
    padding: 1.25rem;
}

.pending-card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.pending-card-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #6b7280;
}

.meta-item i {
    width: 14px;
    color: #ffb300;
}

.pending-card-footer {
    padding: 1rem 1.25rem;
    background: #fffdf6;
    border-top: 1px solid #ffe082;
    display: flex;
    gap: 0.5rem;
}

/* Status Badges */
.status-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pending-badge {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.badge-draft {
    background: #e2e8f0;
    color: #4a5568;
    border: 1px solid #cbd5e0;
}

.badge-published {
    background: #c6f6d5;
    color: #22543d;
    border: 1px solid #9ae6b4;
}

.badge-cancelled {
    background: #fed7d7;
    color: #c53030;
    border: 1px solid #feb2b2;
}

.badge-rejected {
    background: #fed7d7;
    color: #c53030;
    border: 1px solid #feb2b2;
}

.badge-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

/* Table Section */
.events-table-section {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

.table-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.table-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    display: flex;
    align-items: center;
}

.filter-info {
    font-size: 0.9rem;
    font-weight: 400;
    margin-left: 0.5rem;
}

.table-container {
    padding: 0;
}

/* Cute Table Styles */
.cute-table {
    margin: 0;
    font-size: 0.85rem;
    border: none;
}

.cute-table thead th {
    background: #f7fafc;
    border-bottom: 2px solid #e2e8f0;
    padding: 1rem 1.25rem;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.8rem;
}

.cute-table tbody td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.event-row:hover {
    background: #f8fafc;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Table Columns */
.col-title { width: 25%; }
.col-date { width: 20%; }
.col-location { width: 20%; }
.col-status { width: 15%; }
.col-actions { width: 20%; }

.event-title .title-wrapper {
    display: flex;
    align-items: center;
}

.title-text {
    font-weight: 500;
    color: #2d3748;
    line-height: 1.4;
}

.event-date .date-wrapper,
.event-location .location-wrapper {
    display: flex;
    align-items: center;
    color: #6b7280;
    font-size: 0.85rem;
}

.event-date i,
.event-location i {
    width: 14px;
    color: #2d5a27;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.35rem;
    flex-wrap: wrap;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid;
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-view {
    border-color: #63b3ed;
    color: #63b3ed;
}

.btn-view:hover {
    background: #63b3ed;
    color: white;
}

.btn-edit {
    border-color: #68d391;
    color: #68d391;
}

.btn-edit:hover {
    background: #68d391;
    color: white;
}

.btn-submit {
    border-color: #f6ad55;
    color: #f6ad55;
}

.btn-submit:hover {
    background: #f6ad55;
    color: white;
}

.btn-cancel {
    border-color: #fc8181;
    color: #fc8181;
}

.btn-cancel:hover {
    background: #fc8181;
    color: white;
}

.btn-delete {
    border-color: #feb2b2;
    color: #feb2b2;
}

.btn-delete:hover:not(:disabled) {
    background: #fc8181;
    color: white;
}

.btn-delete:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 3rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-message {
    color: #6b7280;
    margin-bottom: 1.5rem;
    font-size: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .d-flex.align-items-center.gap-3 {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .search-input,
    .filter-select {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .pending-grid {
        grid-template-columns: 1fr;
    }
    
    .cute-table {
        font-size: 0.8rem;
    }
    
    .cute-table thead th,
    .cute-table tbody td {
        padding: 0.75rem 1rem;
    }
    
    .action-buttons {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-action {
        width: 28px;
        height: 28px;
        font-size: 0.7rem;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const contentContainer = document.getElementById('contentContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const filterInfo = document.getElementById('filterInfo');
    const emptyMessage = document.getElementById('emptyMessage');

    let searchTimeout;

    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.get('status')) {
        statusFilter.value = urlParams.get('status');
    }
    updateFilterInfo();

    // Search input event
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateFilters, 500);
    });

    // Status filter event
    statusFilter.addEventListener('change', updateFilters);

    function updateFilters() {
        // Show loading
        loadingIndicator.style.display = 'block';
        contentContainer.style.opacity = '0.5';

        const filters = {
            search: searchInput.value,
            status: statusFilter.value
        };

        // Update URL without page reload
        const url = new URL(window.location);
        if (filters.search) {
            url.searchParams.set('search', filters.search);
        } else {
            url.searchParams.delete('search');
        }
        if (filters.status) {
            url.searchParams.set('status', filters.status);
        } else {
            url.searchParams.delete('status');
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
        const status = statusFilter.value;
        
        let info = '';
        if (search || status) {
            info = '(Filtrés: ';
            if (search) info += `"${search}"`;
            if (search && status) info += ' | ';
            if (status) info += `Statut: ${status.charAt(0).toUpperCase() + status.slice(1)}`;
            info += ')';
        }
        
        filterInfo.textContent = info;
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        searchInput.value = urlParams.get('search') || '';
        statusFilter.value = urlParams.get('status') || '';
        updateFilters();
    });
});
</script>
@endpush
@endsection