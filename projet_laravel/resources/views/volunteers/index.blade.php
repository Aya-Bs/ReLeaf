@extends('layouts.frontend')

@section('title', 'Volontaires')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-hands-helping me-2"></i>Volontaires</h2>
                @auth
                    @if(!auth()->user()->isVolunteer())
                        <a href="{{ route('volunteers.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Devenir Volontaire
                        </a>
                    @else
                        <a href="{{ route('volunteers.show', auth()->user()->volunteer) }}" class="btn btn-outline-success">
                            <i class="fas fa-user me-2"></i>Mon Profil Volontaire
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('volunteers.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Nom, prénom...">
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Tous</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="experience_level" class="form-label">Niveau</label>
                                <select class="form-select" id="experience_level" name="experience_level">
                                    <option value="">Tous</option>
                                    <option value="beginner" {{ request('experience_level') == 'beginner' ? 'selected' : '' }}>Débutant</option>
                                    <option value="intermediate" {{ request('experience_level') == 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                                    <option value="advanced" {{ request('experience_level') == 'advanced' ? 'selected' : '' }}>Avancé</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="region" class="form-label">Région</label>
                                <select class="form-select" id="region" name="region">
                                    <option value="">Toutes</option>
                                    <option value="Tunis" {{ request('region') == 'Tunis' ? 'selected' : '' }}>Tunis</option>
                                    <option value="Sfax" {{ request('region') == 'Sfax' ? 'selected' : '' }}>Sfax</option>
                                    <option value="Sousse" {{ request('region') == 'Sousse' ? 'selected' : '' }}>Sousse</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="skill" class="form-label">Compétence</label>
                                <select class="form-select" id="skill" name="skill">
                                    <option value="">Toutes</option>
                                    <option value="gardening" {{ request('skill') == 'gardening' ? 'selected' : '' }}>Jardinage</option>
                                    <option value="coordination" {{ request('skill') == 'coordination' ? 'selected' : '' }}>Coordination</option>
                                    <option value="first_aid" {{ request('skill') == 'first_aid' ? 'selected' : '' }}>Premiers secours</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des volontaires -->
            <div class="row">
                @forelse($volunteers as $volunteer)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card volunteer-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $volunteer->user->avatar_url }}" 
                                     alt="{{ $volunteer->full_name }}" 
                                     class="rounded-circle me-3" 
                                     width="50" height="50">
                                <div>
                                    <h5 class="card-title mb-0">{{ $volunteer->full_name }}</h5>
                                    <small class="text-muted">{{ ucfirst($volunteer->experience_level) }}</small>
                                </div>
                            </div>

                            <p class="card-text text-muted">
                                {{ Str::limit($volunteer->bio, 100) }}
                            </p>

                            <div class="mb-3">
                                <h6 class="text-success">Compétences :</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($volunteer->skills_list as $skill)
                                        <span class="badge bg-light text-dark">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-muted">Missions</small>
                                    <div class="fw-bold">{{ $volunteer->active_assignments_count }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Heures</small>
                                    <div class="fw-bold">{{ $volunteer->total_hours_worked }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Note</small>
                                    <div class="fw-bold">
                                        @if($volunteer->rating > 0)
                                            {{ number_format($volunteer->rating, 1) }}/5
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="badge bg-{{ $volunteer->status === 'active' ? 'success' : ($volunteer->status === 'inactive' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($volunteer->status) }}
                                </span>
                                <a href="{{ route('volunteers.show', $volunteer) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun volontaire trouvé.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $volunteers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.volunteer-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.volunteer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.volunteer-card .card-body {
    padding: 1.5rem;
}
</style>
@endpush


