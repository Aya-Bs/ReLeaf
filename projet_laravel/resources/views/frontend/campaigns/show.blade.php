@extends('layouts.frontend')

@section('title', $campaign->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb Navigation -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('campaigns.index') }}" class="text-eco">
                            <i class="fas fa-leaf me-1"></i>Campagnes
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($campaign->name, 30) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2 text-eco">{{ $campaign->name }}</h1>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge bg-eco">{{ ucfirst($campaign->category) }}</span>
                        <span class="badge bg-{{ $campaign->status == 'active' ? 'success' : ($campaign->status == 'completed' ? 'info' : 'secondary') }}">
                            {{ ucfirst($campaign->status) }}
                        </span>
                        <span class="badge bg-{{ $campaign->visibility ? 'primary' : 'warning' }}">
                            {{ $campaign->visibility ? 'Publique' : 'Privée' }}
                        </span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline-eco">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>

                    <!-- Bouton de demande de suppression -->
                    @if(!$campaign->pendingDeletionRequest)
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteRequestModal">
                        <i class="fas fa-trash-alt me-2"></i>Demander la suppression
                    </button>
                    @else
                    <button type="button" class="btn btn-outline-warning" disabled>
                        <i class="fas fa-clock me-2"></i>Demande en attente
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Le reste du contenu de votre page -->
    <div class="row">
        <!-- Left Column - Campaign Details -->
        <div class="col-lg-8">
            <!-- Campaign Image -->
            @if($campaign->image_url)
            <div class="card mb-4">
                <img src="{{ Storage::url($campaign->image_url) }}"
                    alt="{{ $campaign->name }}"
                    class="card-img-top campaign-main-image"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="card-body text-center bg-light" style="display: none;">
                    <i class="fas fa-image text-muted fa-3x mb-2"></i>
                    <p class="text-muted mb-0">Image non disponible</p>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-body text-center bg-light py-5">
                    <i class="fas fa-leaf text-eco fa-3x mb-3"></i>
                    <p class="text-muted mb-0">Aucune image</p>
                </div>
            </div>
            @endif

            <!-- Progress Section -->
            <div class="card mb-4">
                <div class="card-header bg-eco text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Progression de la campagne
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <div class="progress mb-2" style="height: 12px;">
                                <div class="progress-bar bg-eco"
                                    style="width: {{ $campaign->funds_progress_percentage }}%">
                                </div>
                            </div>
                            <small class="text-muted">{{ $campaign->funds_progress_percentage }}% de l'objectif atteint</small>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <h4 class="text-eco mb-0">{{ $campaign->funds_progress_percentage }}%</h4>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="border rounded p-3">
                                <div class="h5 text-eco mb-1">{{ number_format($campaign->funds_raised, 0, ',', ' ') }} €</div>
                                <small class="text-muted">Montant collecté</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="border rounded p-3">
                                <div class="h5 text-eco mb-1">{{ $campaign->goal ? number_format($campaign->goal, 0, ',', ' ') . ' €' : 'Non défini' }}</div>
                                <small class="text-muted">Objectif financier</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="h5 text-eco mb-1">{{ $campaign->days_remaining ?? 0 }}</div>
                                <small class="text-muted">Jours restants</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations de la campagne
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 40%;"><i class="fas fa-calendar-plus me-2"></i>Date de début</td>
                                    <td><strong>{{ $campaign->start_date->format('d/m/Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-calendar-check me-2"></i>Date de fin</td>
                                    <td><strong>{{ $campaign->end_date->format('d/m/Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Lieu</td>
                                    <td><strong>{{ $campaign->location ?? 'Non spécifié' }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 40%;"><i class="fas fa-users me-2"></i>Participants</td>
                                    <td><strong>{{ $campaign->participants_count ?? 0 }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-boxes me-2"></i>Ressources</td>
                                    <td><strong>{{ $campaign->resources->count() ?? 0 }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-clock me-2"></i>Durée</td>
                                    <td><strong>{{ $campaign->start_date->diffInDays($campaign->end_date) }} jours</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            @if($campaign->description)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-align-left me-2"></i>Description
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $campaign->description }}</p>
                </div>
            </div>
            @endif

            <!-- Environmental Impact -->
            @if($campaign->environmental_impact)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-globe-europe me-2"></i>Impact environnemental
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-leaf text-success mt-1 me-3"></i>
                        <p class="mb-0">{{ $campaign->environmental_impact }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tags -->
            @if($campaign->tags && is_array($campaign->tags) && count($campaign->tags) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tags me-2"></i>Mots-clés
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($campaign->tags as $tag)
                        <span class="badge bg-light text-dark border">
                            {{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Resources Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>Ressources nécessaires
                    </h5>
                    <span class="badge bg-eco">{{ $campaign->resources->count() ?? 0 }}</span>
                </div>
                <div class="card-body">
                    @if($campaign->resources && $campaign->resources->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Type</th>
                                    <th>Progression</th>
                                    <th>Statut</th>
                                    <th>Priorité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($campaign->resources as $resource)
                                <tr>
                                    <td>
                                        <strong>{{ $resource->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $resource->quantity_pledged ?? 0 }}/{{ $resource->quantity_needed ?? 0 }} {{ $resource->unit }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark text-capitalize">
                                            {{ $resource->resource_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : 'warning' }}"
                                                    style="width: {{ $resource->progress_percentage ?? 0 }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted" style="min-width: 35px;">
                                                {{ $resource->progress_percentage ?? 0 }}%
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                        $statusColors = [
                                        'needed' => 'secondary',
                                        'pledged' => 'info',
                                        'received' => 'success',
                                        'in_use' => 'primary'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$resource->status] ?? 'secondary' }} text-capitalize">
                                            {{ $resource->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                        $priorityColors = [
                                        'urgent' => 'danger',
                                        'high' => 'warning',
                                        'medium' => 'info',
                                        'low' => 'success'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $priorityColors[$resource->priority] ?? 'secondary' }} text-capitalize">
                                            {{ $resource->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('resources.show', $resource) }}" class="btn btn-sm btn-outline-eco">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune ressource associée</h5>
                        <p class="text-muted mb-3">Ajoutez des ressources nécessaires à votre campagne</p>
                        <a href="{{ route('resources.create') }}?campaign_id={{ $campaign->id }}" class="btn btn-eco">
                            <i class="fas fa-plus me-2"></i>Ajouter une ressource
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Taux de réussite</span>
                            <span class="fw-bold text-eco">{{ $campaign->funds_progress_percentage }}%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Jours écoulés</span>
                            <span class="fw-bold text-eco">
                                {{ max(0, $campaign->start_date->diffInDays(now())) }} / {{ $campaign->start_date->diffInDays($campaign->end_date) }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Ressources urgentes</span>
                            <span class="fw-bold text-danger">
                                {{ $campaign->resources->where('priority', 'urgent')->count() }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Ressources complétées</span>
                            <span class="fw-bold text-success">
                                {{ $campaign->resources->where('status', 'received')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline-eco">
                            <i class="fas fa-edit me-2"></i>Modifier la campagne
                        </a>
                        <a href="{{ route('resources.create') }}?campaign_id={{ $campaign->id }}" class="btn btn-outline-eco">
                            <i class="fas fa-plus me-2"></i>Ajouter une ressource
                        </a>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux campagnes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Campaign Status -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>État de la campagne
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if($campaign->status == 'active')
                            <i class="fas fa-play-circle text-success fa-2x"></i>
                            @elseif($campaign->status == 'completed')
                            <i class="fas fa-check-circle text-info fa-2x"></i>
                            @elseif($campaign->status == 'cancelled')
                            <i class="fas fa-times-circle text-danger fa-2x"></i>
                            @else
                            <i class="fas fa-pause-circle text-warning fa-2x"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Statut: {{ ucfirst($campaign->status) }}</h6>
                            <p class="text-muted mb-0 small">
                                @if($campaign->status == 'active')
                                Campagne en cours d'exécution
                                @elseif($campaign->status == 'completed')
                                Campagne terminée avec succès
                                @elseif($campaign->status == 'cancelled')
                                Campagne annulée
                                @else
                                Campagne en pause
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            @if($campaign->visibility)
                            <i class="fas fa-eye text-primary fa-2x"></i>
                            @else
                            <i class="fas fa-eye-slash text-warning fa-2x"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Visibilité: {{ $campaign->visibility ? 'Publique' : 'Privée' }}</h6>
                            <p class="text-muted mb-0 small">
                                @if($campaign->visibility)
                                Visible par tous les utilisateurs
                                @else
                                Visible uniquement par vous
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- MODAL PLACÉ EN DEHORS DE LA SECTION CONTENT -->
<div class="modal fade" id="deleteRequestModal" tabindex="-1" aria-labelledby="deleteRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRequestModalLabel">Demander la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('campaigns.request-deletion', $campaign) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir demander la suppression de la campagne <strong>"{{ $campaign->name }}"</strong> ?</p>
                    <p class="text-muted small">Cette demande devra être approuvée par un administrateur avant que la campagne ne soit supprimée.</p>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Raison (optionnelle)</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"
                            placeholder="Pourquoi souhaitez-vous supprimer cette campagne ?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: var(--eco-green);
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    .campaign-main-image {
        height: 300px;
        object-fit: cover;
        width: 100%;
    }

    .card {
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 600;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: var(--eco-green);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .list-group-item {
        border-color: #f8f9fa;
    }

    .progress {
        background-color: #e9ecef;
    }

    .bg-eco {
        background-color: var(--eco-green) !important;
    }

    .text-eco {
        color: var(--eco-green) !important;
    }

    .btn-outline-eco {
        border-color: var(--eco-green);
        color: var(--eco-green);
    }

    .btn-outline-eco:hover {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }

    /* Styles pour garantir que le modal fonctionne */
    .modal {
        z-index: 1060 !important;
    }

    .modal-backdrop {
        z-index: 1050 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .campaign-main-image {
            height: 200px;
        }

        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .d-flex.gap-2.align-items-center {
            margin-top: 0.5rem;
        }

        .modal-dialog {
            margin: 1rem;
        }
    }
</style>
@endpush