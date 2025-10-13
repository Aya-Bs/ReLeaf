@extends('backend.layouts.app')

@section('title', 'Détails du Volontaire')
@section('page-title', 'Détails du Volontaire')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.volunteers.index') }}">Volontaires</a></li>
    <li class="breadcrumb-item active">{{ $volunteer->user->name }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Informations du volontaire -->
    <div class="col-md-8">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user mr-1"></i>
                    Informations du Volontaire
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informations personnelles</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td>{{ $volunteer->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email :</strong></td>
                                <td>{{ $volunteer->user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    <span class="badge badge-{{ $volunteer->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($volunteer->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Inscrit le :</strong></td>
                                <td>{{ $volunteer->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informations volontaire</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Niveau d'expérience :</strong></td>
                                <td>{{ ucfirst($volunteer->experience_level) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Heures max/semaine :</strong></td>
                                <td>{{ $volunteer->max_hours_per_week }}h</td>
                            </tr>
                            <tr>
                                <td><strong>Contact d'urgence :</strong></td>
                                <td>{{ $volunteer->emergency_contact ?? 'Non renseigné' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($volunteer->bio)
                    <div class="mt-3">
                        <h5>Bio</h5>
                        <p class="text-muted">{{ $volunteer->bio }}</p>
                    </div>
                @endif

                @if($volunteer->motivation)
                    <div class="mt-3">
                        <h5>Motivation</h5>
                        <p class="text-muted">{{ $volunteer->motivation }}</p>
                    </div>
                @endif

                @if($volunteer->skills && count($volunteer->skills) > 0)
                    <div class="mt-3">
                        <h5>Compétences</h5>
                        <div class="d-flex flex-wrap">
                            @foreach($volunteer->skills as $skill)
                                <span class="badge badge-info mr-2 mb-2">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($volunteer->preferred_regions && count($volunteer->preferred_regions) > 0)
                    <div class="mt-3">
                        <h5>Régions préférées</h5>
                        <div class="d-flex flex-wrap">
                            @foreach($volunteer->preferred_regions as $region)
                                <span class="badge badge-secondary mr-2 mb-2">{{ $region }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('backend.volunteers.edit', $volunteer) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
                <form method="POST" action="{{ route('backend.volunteers.destroy', $volunteer) }}" 
                      style="display: inline;" 
                      onsubmit="return confirm('Supprimer ce volontaire ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="col-md-4">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Statistiques
                </h3>
            </div>
            <div class="card-body">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-tasks"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Missions totales</span>
                        <span class="info-box-number">{{ $statistics['total_assignments'] }}</span>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Missions terminées</span>
                        <span class="info-box-number">{{ $statistics['completed_assignments'] }}</span>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Heures travaillées</span>
                        <span class="info-box-number">{{ $statistics['total_hours_worked'] }}h</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Missions récentes -->
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Missions récentes
                </h3>
            </div>
            <div class="card-body">
                @if($recentAssignments->count() > 0)
                    @foreach($recentAssignments as $assignment)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $assignment->role }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ class_basename($assignment->assignable_type) }}: 
                                    {{ $assignment->assignable->title ?? 'N/A' }}
                                </small>
                            </div>
                            <span class="badge badge-{{ $assignment->getStatusBadgeClass() }}">
                                {{ $assignment->getStatusLabel() }}
                            </span>
                        </div>
                        <hr>
                    @endforeach
                @else
                    <p class="text-muted">Aucune mission récente.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
