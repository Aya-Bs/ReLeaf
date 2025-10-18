@extends('layouts.frontend')

@section('title', 'Missions Disponibles')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-success"><i class="fas fa-search me-2"></i>Missions Disponibles</h2>
                @if(auth()->user()->isVolunteer())
                    <a href="{{ route('assignments.index') }}" class="btn btn-outline-success">
                        <i class="fas fa-tasks me-2"></i>Mes Missions
                    </a>
                @else
                    <a href="{{ route('volunteers.create') }}" class="btn btn-success">
                        <i class="fas fa-hands-helping me-2"></i>Devenir Volontaire
                    </a>
                @endif
            </div>

            @if(!auth()->user()->isVolunteer())
                <div class="alert alert-warning border-0 shadow-sm">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Vous n'êtes pas encore volontaire !</strong> 
                    Vous pouvez voir les événements disponibles, mais pour vous inscrire, vous devez d'abord 
                    <a href="{{ route('volunteers.create') }}" class="alert-link">devenir volontaire</a>.
                </div>
            @endif

            <!-- Filtres -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtres de recherche</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('volunteers.available-missions') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label fw-bold">Rechercher</label>
                                <input type="text" class="form-control border-success" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Titre de l'événement...">
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label fw-bold">Type</label>
                                <select class="form-select border-success" id="type" name="type">
                                    <option value="">Tous</option>
                                    <option value="events" {{ request('type') == 'events' ? 'selected' : '' }}>Événements</option>
                                    <option value="campaigns" {{ request('type') == 'campaigns' ? 'selected' : '' }}>Campagnes</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">&nbsp;</label>
                                <button type="submit" class="btn btn-success d-block w-100">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">&nbsp;</label>
                                <a href="{{ route('volunteers.available-missions') }}" class="btn btn-outline-success d-block w-100">
                                    <i class="fas fa-times"></i> Effacer
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Événements -->
            @if($events->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Événements à venir
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($events as $event)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-success shadow-sm event-card">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Événement</h6>
                                    <span class="badge bg-light text-success">{{ $event->date->format('d/m') }}</span>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-success mb-3">{{ $event->title }}</h5>
                                    <p class="card-text text-muted mb-3">
                                        {{ Str::limit($event->description, 120) }}
                                    </p>
                                    
                                    <!-- Informations principales -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="info-item">
                                                <i class="fas fa-clock text-success me-2"></i>
                                                <small class="text-muted d-block">Date & Heure</small>
                                                <div class="fw-bold text-success">{{ $event->date->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-item">
                                                <i class="fas fa-hourglass-half text-success me-2"></i>
                                                <small class="text-muted d-block">Durée</small>
                                                <div class="fw-bold text-success">{{ $event->duration ?? 'Non spécifiée' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($event->location)
                                        <div class="info-item mb-3">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            <small class="text-muted d-block">Lieu</small>
                                            <div class="fw-bold text-success">
                                                @if(is_object($event->location))
                                                    {{ $event->location->name }}
                                                @else
                                                    {{ $event->location }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Statut de l'événement -->
                                    <div class="mb-3">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-check-circle me-1"></i>Disponible
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    @php
                                        $hasApplied = isset($appliedAssignments['App\Models\Event'][$event->id]);
                                    @endphp
                                    
                                    @if($hasApplied)
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check me-1"></i>Inscrit
                                        </span>
                                    @elseif(auth()->user()->isVolunteer())
                                        <form method="POST" action="{{ route('volunteers.apply-mission') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="assignable_type" value="App\Models\Event">
                                            <input type="hidden" name="assignable_id" value="{{ $event->id }}">
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir vous inscrire à cet événement ?')">
                                                <i class="fas fa-user-plus me-1"></i>S'inscrire directement
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-lock me-1"></i>Devenir volontaire pour s'inscrire
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-outline-success btn-sm ms-2"
                                            onclick="showMissionDetails('App\Models\Event', {{ $event->id }})">
                                        <i class="fas fa-eye me-1"></i>Détails
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination pour les événements -->
                    <div class="d-flex justify-content-center">
                        {{ $events->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Campagnes -->
            @if($campaigns->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bullhorn me-2"></i>Campagnes actives
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($campaigns as $campaign)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-success shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Campagne</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title text-success">{{ $campaign->name }}</h6>
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($campaign->description, 100) }}
                                    </p>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted fw-bold">Date de début</small>
                                            <div class="fw-bold text-success">{{ $campaign->start_date->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted fw-bold">Date de fin</small>
                                            <div class="fw-bold text-success">{{ $campaign->end_date->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    @if($campaign->category)
                                        <div class="mb-2">
                                            <small class="text-muted fw-bold">Catégorie</small>
                                            <div class="fw-bold text-success">{{ $campaign->category }}</div>
                                        </div>
                                    @endif
                                    @if($campaign->goal)
                                        <div class="mb-2">
                                            <small class="text-muted fw-bold">Objectif</small>
                                            <div class="fw-bold text-success">{{ number_format($campaign->goal, 0, ',', ' ') }} €</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-light">
                                    @php
                                        $hasApplied = isset($appliedAssignments['App\Models\Campaign'][$campaign->id]);
                                    @endphp
                                    
                                    @if($hasApplied)
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check me-1"></i>Inscrit
                                        </span>
                                    @elseif(auth()->user()->isVolunteer())
                                        <form method="POST" action="{{ route('volunteers.apply-mission') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="assignable_type" value="App\Models\Campaign">
                                            <input type="hidden" name="assignable_id" value="{{ $campaign->id }}">
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir vous inscrire à cette campagne ?')">
                                                <i class="fas fa-user-plus me-1"></i>S'inscrire directement
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-lock me-1"></i>Devenir volontaire pour s'inscrire
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-outline-success btn-sm ms-2"
                                            onclick="showMissionDetails('App\Models\Campaign', {{ $campaign->id }})">
                                        <i class="fas fa-eye me-1"></i>Détails
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination pour les campagnes -->
                    <div class="d-flex justify-content-center">
                        {{ $campaigns->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif

            @if($events->count() == 0 && $campaigns->count() == 0)
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucune mission disponible pour le moment.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        transition: all 0.3s ease-in-out;
        border-radius: 12px;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(40, 167, 69, 0.2) !important;
    }
    
    .event-card {
        border: 2px solid #28a745;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .event-card .card-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        padding: 1rem 1.5rem;
    }
    
    .event-card .card-body {
        padding: 1.5rem;
    }
    
    .info-item {
        padding: 0.5rem 0;
    }
    
    .info-item i {
        font-size: 1.1rem;
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        line-height: 1.3;
    }
    
    .card-text {
        line-height: 1.5;
        font-size: 0.95rem;
    }
    
    .border-success {
        border-color: #28a745 !important;
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .bg-success {
        background-color: #28a745 !important;
    }
    
    .bg-success-subtle {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-1px);
    }
    
    .btn-outline-success {
        color: #28a745;
        border-color: #28a745;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-outline-success:hover {
        background-color: #28a745;
        border-color: #28a745;
        transform: translateY(-1px);
    }
    
    .shadow-sm {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }
    
    .badge {
        border-radius: 6px;
        font-weight: 500;
    }
    
    .card-footer {
        background-color: #f8f9fa !important;
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
// Afficher les détails d'une mission
function showMissionDetails(type, id) {
    window.open(`{{ route('volunteers.mission-details') }}?type=${type}&id=${id}`, '_blank');
}
</script>
@endpush
