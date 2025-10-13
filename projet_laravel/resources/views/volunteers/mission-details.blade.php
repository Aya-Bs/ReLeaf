@extends('layouts.frontend')

@section('title', 'Détails de la Mission')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-{{ $type === 'App\Models\Event' ? 'primary' : 'success' }} text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-{{ $type === 'App\Models\Event' ? 'calendar-alt' : 'bullhorn' }} me-2"></i>
                            {{ $mission->title }}
                        </h4>
                        <span class="badge bg-light text-dark">
                            {{ $type === 'App\Models\Event' ? 'Événement' : 'Campagne' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Description</h6>
                            <p>{{ $mission->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Dates</h6>
                            <p>
                                <strong>Début :</strong> {{ $mission->start_date->format('d/m/Y H:i') }}<br>
                                <strong>Fin :</strong> {{ $mission->end_date->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($type === 'App\Models\Event')
                        @if($mission->location)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-muted">Lieu</h6>
                                    <p><i class="fas fa-map-marker-alt me-2"></i>{{ $mission->location }}</p>
                                </div>
                            </div>
                        @endif
                    @else
                        @if($mission->target_audience)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-muted">Public cible</h6>
                                    <p>{{ $mission->target_audience }}</p>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($mission->objectives)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted">Objectifs</h6>
                                <p>{{ $mission->objectives }}</p>
                            </div>
                        @endif

                        @if($mission->requirements)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-muted">Exigences</h6>
                                    <p>{{ $mission->requirements }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted">Statut</h6>
                                <span class="badge bg-{{ $mission->status === 'active' ? 'success' : ($mission->status === 'approved' ? 'primary' : 'warning') }}">
                                    {{ ucfirst($mission->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-muted">Créé le</h6>
                                <p>{{ $mission->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        @if($hasApplied)
                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i>
                                Vous avez déjà postulé pour cette mission.
                            </div>
                        @else
                            <button type="button" class="btn btn-{{ $type === 'App\Models\Event' ? 'primary' : 'success' }} btn-lg" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#applyModal"
                                    data-type="{{ $type }}"
                                    data-id="{{ $mission->id }}"
                                    data-title="{{ $mission->title }}">
                                <i class="fas fa-hand-paper me-2"></i>Postuler pour cette mission
                            </button>
                        @endif
                        
                        <a href="{{ route('volunteers.available-missions') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-arrow-left me-1"></i>Retour aux missions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de candidature -->
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-hand-paper me-2"></i>Postuler pour une mission
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('volunteers.apply-mission') }}">
                @csrf
                <input type="hidden" name="assignable_type" value="{{ $type }}">
                <input type="hidden" name="assignable_id" value="{{ $mission->id }}">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Vous postulez pour : <strong>{{ $mission->title }}</strong>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Rôle souhaité <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="role" name="role" 
                                       placeholder="Ex: Coordinateur, Aide, Spécialiste..." required>
                                @error('role')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hours_committed" class="form-label">Heures disponibles <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="hours_committed" name="hours_committed" 
                                       min="1" max="40" required>
                                @error('hours_committed')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes supplémentaires</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Vos compétences, disponibilités particulières, motivations..."></textarea>
                        @error('notes')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Envoyer la candidature
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Validation des dates
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    endDateInput.min = startDate;
    if (endDateInput.value && endDateInput.value <= startDate) {
        endDateInput.value = '';
    }
});
</script>
@endpush
