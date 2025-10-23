@extends('backend.layouts.app')

@section('title', 'Gestion des Missions')
@section('page-title', 'Gestion des Missions')

@section('breadcrumb')
    <li class="breadcrumb-item active">Missions</li>
@endsection

@section('content')
<!-- Statistiques -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Missions</p>
            </div>
            <div class="icon">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending'] }}</h3>
                <p>En attente</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['approved'] }}</h3>
                <p>Approuvées</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $stats['completed'] }}</h3>
                <p>Terminées</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="card card-eco">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-1"></i>
            Filtres
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('backend.assignments.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Statut</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvée</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetée</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">Tous</option>
                            <option value="App\Models\Event" {{ request('type') == 'App\Models\Event' ? 'selected' : '' }}>Événement</option>
                            <option value="App\Models\Campaign" {{ request('type') == 'App\Models\Campaign' ? 'selected' : '' }}>Campagne</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">Rechercher</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nom du volontaire...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Liste des missions -->
<div class="card card-eco">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-1"></i>
            Liste des Missions
        </h3>
    </div>
    <div class="card-body">
        @if($assignments->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Volontaire</th>
                            <th>Rôle</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Heures</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->id }}</td>
                                <td>
                                    <strong>{{ $assignment->volunteer->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $assignment->volunteer->user->email }}</small>
                                </td>
                                <td>{{ $assignment->role }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ class_basename($assignment->assignable_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $assignment->getStatusBadgeClass() }}">
                                        {{ $assignment->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $assignment->start_date->format('d/m/Y') }}</td>
                                <td>{{ $assignment->end_date->format('d/m/Y') }}</td>
                                <td>{{ $assignment->hours_committed }}h</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('backend.assignments.show', $assignment) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.assignments.edit', $assignment) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($assignment->status === 'pending')
                                            <form method="POST" action="{{ route('backend.assignments.approve', $assignment) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('backend.assignments.reject', $assignment) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @elseif($assignment->status === 'approved')
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#completeModal{{ $assignment->id }}">
                                                <i class="fas fa-flag-checkered"></i> Terminer
                                            </button>
                                        @endif
                                        <form method="POST" action="{{ route('backend.assignments.destroy', $assignment) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Supprimer cette mission ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
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
            <div class="d-flex justify-content-center">
                {{ $assignments->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle mr-2"></i>
                Aucune mission trouvée.
            </div>
        @endif
    </div>
</div>

<!-- Complete Assignment Modals -->
@foreach($assignments as $assignment)
    @if($assignment->status === 'approved')
    <div class="modal fade" id="completeModal{{ $assignment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terminer la mission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('backend.assignments.complete', $assignment) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="hours_worked_{{ $assignment->id }}" class="form-label">Heures travaillées *</label>
                            <input type="number" class="form-control" id="hours_worked_{{ $assignment->id }}" 
                                   name="hours_worked" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="rating_{{ $assignment->id }}" class="form-label">Note du volontaire *</label>
                            <select class="form-select" id="rating_{{ $assignment->id }}" name="rating" required>
                                <option value="">Sélectionner une note</option>
                                <option value="5">⭐⭐⭐⭐⭐ Excellent (10 points)</option>
                                <option value="4">⭐⭐⭐⭐ Très bien (8 points)</option>
                                <option value="3">⭐⭐⭐ Bien (6 points)</option>
                                <option value="2">⭐⭐ Moyen (3 points)</option>
                                <option value="1">⭐ Insuffisant (1 point)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="feedback_{{ $assignment->id }}" class="form-label">Commentaires</label>
                            <textarea class="form-control" id="feedback_{{ $assignment->id }}" 
                                      name="feedback" rows="3" placeholder="Commentaires sur la performance du volontaire..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Terminer la mission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection
