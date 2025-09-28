@extends('layouts.frontend')

@section('title', $event->title)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à mes événements
                </a>
            </div>

            <!-- Event Card -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>{{ $event->title }}
                        </h4>
                        <div>
                            @if($event->isDraft())
                                <span class="badge bg-secondary">Brouillon</span>
                            @elseif($event->isPending())
                                <span class="badge bg-warning">En attente</span>
                            @elseif($event->isPublished())
                                <span class="badge bg-success">Publié</span>
                            @elseif($event->isCancelled())
                                <span class="badge bg-danger">Annulé</span>
                            @elseif($event->status === 'rejected')
                                <span class="badge bg-danger">Rejeté</span>
                            @endif
                        </div>
                    </div>
                </div>




                <div class="card-body">
<!-- Event Images -->
@if($event->images && is_array($event->images) && count($event->images) > 0)
<div class="mb-4">
    <div class="row">
        @foreach($event->images as $image)
            @if(!empty($image))
            <div class="col-md-3 mb-3">
                <div class="card">
                    <!-- Try both methods to see which one works -->
                    <img src="{{ asset('storage/' . $image) }}" 
                         class="card-img-top" 
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
                    
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endif

                    <div class="row">
                        <!-- Left Column - Event Details -->
                        <div class="col-md-8">
                            <!-- Description -->
                            <div class="mb-4">
                                <h5><i class="fas fa-align-left me-2 text-primary"></i>Description</h5>
                                <p class="text-muted">{{ $event->description }}</p>
                            </div>

                            <!-- Event Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-calendar-day me-2 text-primary"></i>Date et heure</h6>
                                        <p class="text-muted">
                                            {{ $event->date->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-clock me-2 text-primary"></i>Durée</h6>
                                        <p class="text-muted">{{ $event->duration }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-map-marker-alt me-2 text-primary"></i>Lieu</h6>
                                        <p class="text-muted">{{ $event->location }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-users me-2 text-primary"></i>Participants maximum</h6>
                                        <p class="text-muted">
                                            {{ $event->max_participants ? $event->max_participants . ' personnes' : 'Illimité' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Statistics and Actions -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Informations</h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Créé le</small>
                                        <div>{{ $event->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Dernière modification</small>
                                        <div>{{ $event->updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Statut</small>
                                        <div>
                                            @if($event->isDraft())
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @elseif($event->isPending())
                                                <span class="badge bg-warning">En attente d'approbation</span>
                                            @elseif($event->isPublished())
                                                <span class="badge bg-success">Publié et visible</span>
                                            @elseif($event->isCancelled())
                                                <span class="badge bg-danger">Événement annulé</span>
                                            @elseif($event->status === 'rejected')
                                                <span class="badge bg-danger">Rejeté par l'admin</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Campaign Information -->
@if($event->campaign)
<div class="mb-3">
    <small class="text-muted">Campagne associée</small>
    <div>
        <strong>{{ $event->campaign->name }}</strong>
        <br>
        <small class="text-muted">
            {{ $event->campaign->description }}
            @if($event->campaign->end_date->isFuture())
                <br><span class="badge bg-info">J-{{ $event->campaign->days_remaining }}</span>
            @endif
        </small>
    </div>
</div>
@endif

                                    <!-- Action Buttons -->
                                    <div class="mt-4">
                                        @if($event->canBeEdited())
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                            <i class="fas fa-edit me-2"></i>Modifier
                                        </a>
                                        @endif

                                        @if($event->isDraft())
                                        <form action="{{ route('events.submit', $event) }}" method="POST" class="d-grid">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                                <i class="fas fa-paper-plane me-2"></i>Soumettre pour approbation
                                            </button>
                                        </form>
                                        @endif

                                        @if($event->isPublished())
                                        <form action="{{ route('events.cancel', $event) }}" method="POST" class="d-grid mt-2">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                <i class="fas fa-times me-2"></i>Annuler l'événement
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Delete Button -->
                @if($event->canBeDeleted())
                <div class="card-footer text-end">
                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-outline-danger btn-sm" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.')">
                            <i class="fas fa-trash me-2"></i>Supprimer l'événement
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush