@extends('layouts.frontend')

@section('title', 'Mes Événements')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Mes Événements</h1>
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Input -->
                    <div class="position-relative me-2">
                        <input type="text" 
                               id="searchInput" 
                               class="form-control form-control-sm" 
                               placeholder="Recherche..." 
                               style="width: 200px;"
                               value="{{ request('search') }}">
                        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-muted" style="font-size: 0.8rem;"></i>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="me-2">
                        <select id="statusFilter" class="form-select form-select-sm" style="width: 180px;">
                            <option value="">Tous les statuts</option>
                            <option value="draft">Brouillon</option>
                            <option value="pending">En attente</option>
                            <option value="published">Publié</option>
                            <option value="cancelled">Annulé</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                    </div>
                    
                    
                    
                    <!-- Create Event Button -->
                    <a href="{{ route('events.create') }}" class="btn btn-eco">
                        <i class="fas fa-plus me-2"></i>Créer un événement
                    </a>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-eco" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Filtrage des événements...</p>
            </div>

            <!-- Content Container -->
            <div id="contentContainer">
                <!-- Pending Events -->
                @if($pendingEvents->count() > 0 && !request()->has('status') && !request()->has('search'))
                <div class="card mb-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>En attente d'approbation ({{ $pendingEvents->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($pendingEvents as $event)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $event->title }}</h6>
                                        <p class="card-text small text-muted">
                                            <i class="fas fa-calendar me-1"></i>{{ $event->date->format('d/m/Y H:i') }}<br>
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location ? $event->location->name : '' }}
                                        </p>
                                        <span class="badge bg-warning">En attente</span>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" disabled>
                                            <i class="fas fa-trash"></i>
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            Tous mes événements 
                            <small id="filterInfo" class="text-muted"></small>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($otherEvents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Date</th>
                                        <th>Lieu</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($otherEvents as $event)
                                    <tr>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->date->format('d/m/Y H:i') }}</td>
                                        <td>{{ $event->location ? $event->location->name : '' }}</td>
                                        <td>
                                            @if($event->isDraft())
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @elseif($event->isPublished())
                                                <span class="badge bg-success">Publié</span>
                                            @elseif($event->isCancelled())
                                                <span class="badge bg-danger">Annulé</span>
                                            @elseif($event->status === 'rejected')
                                                <span class="badge bg-danger">Rejeté</span>
                                            @elseif($event->isPending())
                                                <span class="badge bg-warning">En attente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <!-- View Button - Always visible -->
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-outline-info">
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
                                                <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif

                                                <!-- Submit Button - Only for draft events -->
                                                @if($event->isDraft())
                                                <form action="{{ route('events.submit', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                <!-- Cancel Button - Only for published events -->
                                                @if($event->isPublished())
                                                <form action="{{ route('events.cancel', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                <!-- Delete Button - Hide for published events, show for others if they can be deleted -->
                                                @if(!$event->isPublished())
                                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')"
                                                            {{ !$event->canBeDeleted() ? 'disabled' : '' }}>
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
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted" id="emptyMessage">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const clearFilters = document.getElementById('clearFilters');
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

    // Clear filters event
    clearFilters.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        updateFilters();
    });

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