@extends('layouts.frontend')

@section('title', 'Mes Missions')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tasks me-2"></i>Mes Missions</h2>
                <a href="{{ route('volunteers.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-users me-2"></i>Voir tous les volontaires
                </a>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('assignments.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Tous</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">Tous</option>
                                    <option value="App\Models\Event" {{ request('type') == 'App\Models\Event' ? 'selected' : '' }}>Événement</option>
                                    <option value="App\Models\Campaign" {{ request('type') == 'App\Models\Campaign' ? 'selected' : '' }}>Campagne</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="role" class="form-label">Rôle</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">Tous</option>
                                    <option value="coordinator" {{ request('role') == 'coordinator' ? 'selected' : '' }}>Coordinateur</option>
                                    <option value="helper" {{ request('role') == 'helper' ? 'selected' : '' }}>Aide</option>
                                    <option value="specialist" {{ request('role') == 'specialist' ? 'selected' : '' }}>Spécialiste</option>
                                    <option value="supervisor" {{ request('role') == 'supervisor' ? 'selected' : '' }}>Superviseur</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block w-100">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des missions -->
            <div class="row">
                @forelse($assignments as $assignment)
                <div class="col-lg-6 mb-4">
                    <div class="card assignment-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title">{{ $assignment->roleLabel }}</h5>
                                    <p class="text-muted mb-0">
                                        {{ class_basename($assignment->assignable_type) }}: 
                                        {{ $assignment->assignable->title ?? 'N/A' }}
                                    </p>
                                </div>
                                <span class="badge bg-{{ $assignment->getStatusBadgeClass() }}">
                                    {{ $assignment->getStatusLabel() }}
                                </span>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Date de début</small>
                                    <div class="fw-bold">{{ $assignment->start_date->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Date de fin</small>
                                    <div class="fw-bold">{{ $assignment->end_date->format('d/m/Y') }}</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Heures engagées</small>
                                    <div class="fw-bold">{{ $assignment->hours_committed }}h</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Heures travaillées</small>
                                    <div class="fw-bold">{{ $assignment->hours_worked }}h</div>
                                </div>
                            </div>

                            @if($assignment->notes)
                                <div class="mb-3">
                                    <small class="text-muted">Notes</small>
                                    <div class="small">{{ Str::limit($assignment->notes, 100) }}</div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Créé le {{ $assignment->created_at->format('d/m/Y') }}
                                </div>
                                <div>
                                    <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune mission trouvée.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.assignment-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.assignment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.assignment-card .card-body {
    padding: 1.5rem;
}
</style>
@endpush
