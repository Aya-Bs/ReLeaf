@extends('backend.layouts.app')

@section('title', $resource->name)
@section('page-title', 'Détails de la ressource')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.resources.index') }}">Ressources</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($resource->name, 30) }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('backend.resources.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>

            <!-- Resource Card -->
            <div class="card card-eco">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-box me-2"></i>{{ $resource->name }}
                        </h4>
                        <div>
                            @if($resource->status === 'needed')
                                <span class="badge badge-warning">Nécessaire</span>
                            @elseif($resource->status === 'pledged')
                                <span class="badge badge-info">Promis</span>
                            @elseif($resource->status === 'received')
                                <span class="badge badge-success">Reçu</span>
                            @elseif($resource->status === 'in_use')
                                <span class="badge badge-primary">En utilisation</span>
                            @else
                                <span class="badge badge-light">{{ $resource->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Resource Image -->
                    @if($resource->image_url)
                    <div class="mb-4">
                        <img src="{{ Storage::url($resource->image_url) }}" 
                             alt="{{ $resource->name }}" 
                             class="img-fluid rounded"
                             style="max-height: 300px; width: 100%; object-fit: cover;">
                    </div>
                    @endif

                    <div class="row">
                        <!-- Left Column - Resource Details -->
                        <div class="col-md-8">
                            <!-- Description -->
                            <div class="mb-4">
                                <h5><i class="fas fa-align-left me-2 text-primary"></i>Description</h5>
                                <p class="text-muted">{{ $resource->description ?? 'Aucune description' }}</p>
                            </div>

                            <!-- Resource Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-cube me-2 text-primary"></i>Type de ressource</h6>
                                        <p class="text-muted">
                                            @switch($resource->resource_type)
                                                @case('money')
                                                    💰 Argent
                                                    @break
                                                @case('food')
                                                    🍕 Nourriture
                                                    @break
                                                @case('clothing')
                                                    👕 Vêtements
                                                    @break
                                                @case('medical')
                                                    🏥 Médical
                                                    @break
                                                @case('equipment')
                                                    🔧 Équipement
                                                    @break
                                                @case('human')
                                                    👥 Main d'œuvre
                                                    @break
                                                @default
                                                    📦 {{ $resource->resource_type }}
                                            @endswitch
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-tags me-2 text-primary"></i>Catégorie</h6>
                                        <p class="text-muted">
                                            @switch($resource->category)
                                                @case('materiel')
                                                    📦 Matériel
                                                    @break
                                                @case('financier')
                                                    💰 Financier
                                                    @break
                                                @case('humain')
                                                    👥 Humain
                                                    @break
                                                @case('technique')
                                                    🔧 Technique
                                                    @break
                                                @default
                                                    {{ $resource->category }}
                                            @endswitch
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-sort-amount-up me-2 text-primary"></i>Priorité</h6>
                                        <p class="text-muted">
                                            @if($resource->priority === 'urgent')
                                                <span class="badge badge-danger">Urgent</span>
                                            @elseif($resource->priority === 'high')
                                                <span class="badge badge-warning">Haute</span>
                                            @elseif($resource->priority === 'medium')
                                                <span class="badge badge-info">Moyenne</span>
                                            @elseif($resource->priority === 'low')
                                                <span class="badge badge-success">Basse</span>
                                            @else
                                                <span class="badge badge-light">{{ $resource->priority }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-balance-scale me-2 text-primary"></i>Quantité</h6>
                                        <p class="text-muted">
                                            {{ $resource->quantity_pledged }} / {{ $resource->quantity_needed }} {{ $resource->unit }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-truck me-2 text-primary"></i>Fournisseur</h6>
                                        <p class="text-muted">{{ $resource->provider ?? 'Non spécifié' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-info-circle me-2 text-primary"></i>Manquant</h6>
                                        <p class="text-muted">
                                            {{ $resource->missing_quantity }} {{ $resource->unit }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($resource->notes)
                            <div class="mb-4">
                                <h5><i class="fas fa-sticky-note me-2 text-primary"></i>Notes</h5>
                                <p class="text-muted">{{ $resource->notes }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Right Column - Statistics and Actions -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Informations</h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Campagne</small>
                                        <div>
                                            @if($resource->campaign)
                                                <strong>{{ $resource->campaign->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    Organisateur: {{ $resource->campaign->organizer->name }}
                                                </small>
                                            @else
                                                <span class="text-muted">Aucune campagne</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Créée le</small>
                                        <div>{{ $resource->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Dernière modification</small>
                                        <div>{{ $resource->updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <small class="text-muted">Progression</small>
                                        <div class="progress mt-1" style="height: 10px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 style="width: {{ $resource->progress_percentage }}%"
                                                 aria-valuenow="{{ $resource->progress_percentage }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $resource->quantity_pledged }} / {{ $resource->quantity_needed }} {{ $resource->unit }}
                                            ({{ $resource->progress_percentage }}%)
                                        </small>
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