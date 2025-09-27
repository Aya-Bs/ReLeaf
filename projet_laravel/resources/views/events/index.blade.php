@extends('events.app')

@section('title', 'Mes Événements')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Mes Événements</h1>
                <a href="{{ route('events.create') }}" class="btn btn-eco">
                    <i class="fas fa-plus me-2"></i>Créer un événement
                </a>
            </div>

            <!-- Pending Events -->
            @if($pendingEvents->count() > 0)
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
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }}
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
                    <h5 class="mb-0">Tous mes événements</h5>
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
                                    <td>{{ $event->location }}</td>
                                    <td>
                                        @if($event->isDraft())
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @elseif($event->isPublished())
                                            <span class="badge bg-success">Publié</span>
                                        @elseif($event->isCancelled())
                                            <span class="badge bg-danger">Annulé</span>
                                        @elseif($event->status === 'rejected')
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <!-- View Button - Always visible -->
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>

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
                        <p class="text-muted">Aucun événement créé pour le moment.</p>
                        <a href="{{ route('events.create') }}" class="btn btn-eco">Créer votre premier événement</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection