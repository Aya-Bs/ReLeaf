@extends('layouts.frontend')

@section('title', 'Profil Volontaire')

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

            <div class="card mb-4">
                <div class="card-body d-flex align-items-center">
                    <img src="{{ $volunteer->user->avatar_url }}" alt="Avatar" class="rounded-circle me-3" width="64" height="64">
                    <div>
                        <h4 class="mb-1">{{ $volunteer->full_name }}</h4>
                        <div class="text-muted">
                            {{ $volunteer->user->email }} · {{ $volunteer->phone ?: '—' }}
                        </div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-{{ $volunteer->status === 'active' ? 'success' : ($volunteer->status === 'inactive' ? 'warning' : 'danger') }}">
                            {{ ucfirst($volunteer->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <strong>À propos</strong>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Niveau:</strong> {{ ucfirst($volunteer->experience_level) }}</p>
                    <p class="mb-2"><strong>Régions préférées:</strong> {{ implode(', ', $volunteer->preferred_regions ?? []) ?: '—' }}</p>
                    <p class="mb-2"><strong>Heures max/semaine:</strong> {{ $volunteer->max_hours_per_week }}</p>
                    <p class="mb-2"><strong>Compétences:</strong>
                        @forelse(($volunteer->skills_list) as $skill)
                            <span class="badge bg-light text-dark me-1">{{ $skill }}</span>
                        @empty
                            —
                        @endforelse
                    </p>
                    <p class="mb-0"><strong>Bio:</strong><br>{{ $volunteer->bio ?: '—' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light d-flex align-items-center">
                    <strong class="me-auto">Statistiques</strong>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-muted">Missions</div>
                            <div class="h5 mb-0">{{ $statistics['total_assignments'] ?? 0 }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted">Terminées</div>
                            <div class="h5 mb-0">{{ $statistics['completed_assignments'] ?? 0 }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted">Heures</div>
                            <div class="h5 mb-0">{{ $statistics['total_hours_worked'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                @can('update', $volunteer)
                    <a href="{{ route('volunteers.edit', $volunteer) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </a>
                @endcan

                @can('delete', $volunteer)
                    <form method="POST" action="{{ route('volunteers.destroy', $volunteer) }}" onsubmit="return confirm('Supprimer votre profil volontaire ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-1"></i>Supprimer le profil volontaire
                        </button>
                    </form>
                @endcan

                <a href="{{ route('volunteers.index') }}" class="btn btn-secondary ms-auto">
                    Retour
                </a>
            </div>
        </div>
    </div>
    
    @if(isset($recentAssignments) && $recentAssignments->count())
    <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-light">
                    <strong>Missions récentes</strong>
                </div>
                <div class="card-body">
                    @foreach($recentAssignments as $assignment)
                        <div class="d-flex align-items-center py-2 border-bottom">
                            <div class="me-3">
                                <span class="badge bg-{{ $assignment->getStatusBadgeClass() }}">{{ $assignment->getStatusLabel() }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $assignment->roleLabel }}</div>
                                <div class="text-muted small">
                                    {{ optional($assignment->start_date)->format('d/m/Y') }} → {{ optional($assignment->end_date)->format('d/m/Y') }} · {{ class_basename($assignment->assignable_type) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection




