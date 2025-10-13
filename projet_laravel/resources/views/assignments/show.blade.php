@extends('layouts.frontend')

@section('title', 'Détails de la Mission')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-tasks me-2"></i>{{ $assignment->roleLabel }}
                        </h4>
                        <span class="badge bg-{{ $assignment->getStatusBadgeClass() }} fs-6">
                            {{ $assignment->getStatusLabel() }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Type de mission</h6>
                            <p class="fw-bold">{{ class_basename($assignment->assignable_type) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Titre</h6>
                            <p class="fw-bold">{{ $assignment->assignable->title ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <h6 class="text-muted">Date de début</h6>
                            <p class="fw-bold">{{ $assignment->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Date de fin</h6>
                            <p class="fw-bold">{{ $assignment->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Durée</h6>
                            <p class="fw-bold">{{ $assignment->duration_in_days }} jours</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Progression</h6>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $assignment->progress_percentage }}%"
                                     aria-valuenow="{{ $assignment->progress_percentage }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                    {{ $assignment->progress_percentage }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Heures engagées</h6>
                            <p class="fw-bold">{{ $assignment->hours_committed }} heures</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Heures travaillées</h6>
                            <p class="fw-bold">{{ $assignment->hours_worked }} heures</p>
                        </div>
                    </div>

                    @if($assignment->notes)
                        <div class="mb-4">
                            <h6 class="text-muted">Notes</h6>
                            <p class="fw-bold">{{ $assignment->notes }}</p>
                        </div>
                    @endif

                    @if($assignment->rating)
                        <div class="mb-4">
                            <h6 class="text-muted">Note reçue</h6>
                            <div class="d-flex align-items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $assignment->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2 fw-bold">{{ $assignment->rating }}/5</span>
                            </div>
                        </div>
                    @endif

                    @if($assignment->feedback)
                        <div class="mb-4">
                            <h6 class="text-muted">Feedback</h6>
                            <p class="fw-bold">{{ $assignment->feedback }}</p>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                        
                        @if($assignment->canBeEdited())
                            <a href="{{ route('assignments.edit', $assignment) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>Modifier
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
