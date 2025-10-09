@extends('backend.layouts.app')

@section('title', $campaign->name)
@section('page-title', 'Détails de la campagne')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.campaigns.index') }}">Campagnes</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($campaign->name, 30) }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('backend.campaigns.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>

            <!-- Campaign Card -->
            <div class="card card-eco">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-leaf me-2"></i>{{ $campaign->name }}
                        </h4>
                        <div>
                            @if($campaign->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($campaign->status === 'inactive')
                                <span class="badge badge-secondary">Inactive</span>
                            @elseif($campaign->status === 'completed')
                                <span class="badge badge-primary">Terminée</span>
                            @elseif($campaign->status === 'cancelled')
                                <span class="badge badge-danger">Annulée</span>
                            @else
                                <span class="badge badge-light">{{ $campaign->status }}</span>
                            @endif
                            @if(!$campaign->visibility)
                                <span class="badge badge-warning ms-1">
                                    <i class="fas fa-eye-slash me-1"></i>Non visible
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Campaign Image -->
                    @if($campaign->image_url)
                    <div class="mb-4">
                        <img src="{{ Storage::url($campaign->image_url) }}" 
                             alt="{{ $campaign->name }}" 
                             class="img-fluid rounded"
                             style="max-height: 300px; width: 100%; object-fit: cover;">
                    </div>
                    @endif

                    <div class="row">
                        <!-- Left Column - Campaign Details -->
                        <div class="col-md-8">
                            <!-- Description -->
                            <div class="mb-4">
                                <h5><i class="fas fa-align-left me-2 text-primary"></i>Description</h5>
                                <p class="text-muted">{{ $campaign->description ?? 'Aucune description' }}</p>
                            </div>

                            <!-- Campaign Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-folder me-2 text-primary"></i>Catégorie</h6>
                                        <p class="text-muted">
                                            @switch($campaign->category)
                                                @case('reforestation')
                                                    🌲 Reforestation
                                                    @break
                                                @case('nettoyage')
                                                    🧹 Nettoyage
                                                    @break
                                                @case('sensibilisation')
                                                    📢 Sensibilisation
                                                    @break
                                                @case('recyclage')
                                                    ♻️ Recyclage
                                                    @break
                                                @case('biodiversite')
                                                    🦋 Biodiversité
                                                    @break
                                                @case('energie_renouvelable')
                                                    ⚡ Énergie Renouvelable
                                                    @break
                                                @default
                                                    🔧 {{ $campaign->category }}
                                            @endswitch
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-calendar-plus me-2 text-primary"></i>Date de début</h6>
                                        <p class="text-muted">{{ $campaign->start_date->format('d/m/Y') }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-bullseye me-2 text-primary"></i>Objectif financier</h6>
                                        <p class="text-muted">
                                            {{ $campaign->goal ? number_format($campaign->goal, 2, ',', ' ') . ' €' : 'Non défini' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-tags me-2 text-primary"></i>Tags</h6>
                                        <p class="text-muted">
                                            @if($campaign->tags && count($campaign->tags) > 0)
                                                @foreach($campaign->tags as $tag)
                                                    <span class="badge badge-light me-1">{{ $tag }}</span>
                                                @endforeach
                                            @else
                                                Aucun tag
                                            @endif
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-calendar-check me-2 text-primary"></i>Date de fin</h6>
                                        <p class="text-muted">{{ $campaign->end_date->format('d/m/Y') }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-chart-line me-2 text-primary"></i>Montant collecté</h6>
                                        <p class="text-muted">
                                            {{ number_format($campaign->funds_raised, 2, ',', ' ') }} €
                                            @if($campaign->goal && $campaign->goal > 0)
                                                <small class="text-success">
                                                    ({{ $campaign->funds_progress_percentage }}%)
                                                </small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Environmental Impact -->
                            @if($campaign->environmental_impact)
                            <div class="mb-4">
                                <h5><i class="fas fa-seedling me-2 text-success"></i>Impact environnemental</h5>
                                <p class="text-muted">{{ $campaign->environmental_impact }}</p>
                            </div>
                            @endif

                            <!-- Statistics -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-primary mb-0">{{ $campaign->resources_count ?? 0 }}</h3>
                                            <small class="text-muted">Ressources</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-success mb-0">{{ $campaign->events_count ?? 0 }}</h3>
                                            <small class="text-muted">Événements</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="text-info mb-0">{{ $campaign->participants_count ?? 0 }}</h3>
                                            <small class="text-muted">Participants</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Statistics and Actions -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Informations</h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Organisateur</small>
                                        <div>{{ $campaign->organizer->name }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Créée le</small>
                                        <div>{{ $campaign->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Dernière modification</small>
                                        <div>{{ $campaign->updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Jours restants</small>
                                        <div>
                                            @if($campaign->end_date->isFuture())
                                                <span class="badge badge-info">J-{{ $campaign->days_remaining }}</span>
                                            @else
                                                <span class="badge badge-secondary">Terminée</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Visibilité</small>
                                        <div>
                                            @if($campaign->visibility)
                                                <span class="badge badge-success">Publique</span>
                                            @else
                                                <span class="badge badge-warning">Privée</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    @if($campaign->goal && $campaign->goal > 0)
                                    <div class="mb-3">
                                        <small class="text-muted">Progression financière</small>
                                        <div class="progress mt-1" style="height: 10px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $campaign->funds_progress_percentage }}%"
                                                 aria-valuenow="{{ $campaign->funds_progress_percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ number_format($campaign->funds_raised, 2, ',', ' ') }} € / 
                                            {{ number_format($campaign->goal, 2, ',', ' ') }} €
                                        </small>
                                    </div>
                                    @endif

                                    <!-- Admin Actions -->
                                    <div class="mt-4">
                                        <div class="d-grid gap-2">
                                            @if($campaign->visibility)
                                            <form action="{{ route('campaigns.toggle-visibility', $campaign) }}" method="POST" class="d-grid">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm w-100">
                                                    <i class="fas fa-eye-slash me-2"></i>Rendre privée
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('campaigns.toggle-visibility', $campaign) }}" method="POST" class="d-grid">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-eye me-2"></i>Rendre publique
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection