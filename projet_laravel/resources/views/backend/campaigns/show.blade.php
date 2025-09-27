@extends('backend.layouts.app')

@section('title', $campaign->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Détails de la Campagne: {{ $campaign->name }}</h5>
                    <div>
                        <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Informations générales</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Nom:</strong></td>
                                            <td>{{ $campaign->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Catégorie:</strong></td>
                                            <td><span class="badge bg-info">{{ $campaign->category }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Statut:</strong></td>
                                            <td><span class="badge bg-{{ $campaign->status == 'active' ? 'success' : 'secondary' }}">{{ $campaign->status }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Visibilité:</strong></td>
                                            <td><span class="badge bg-{{ $campaign->visibility ? 'success' : 'warning' }}">{{ $campaign->visibility ? 'Publique' : 'Privée' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Organisateur:</strong></td>
                                            <td>{{ $campaign->organizer->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Dates et financement</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Début:</strong></td>
                                            <td>{{ $campaign->start_date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fin:</strong></td>
                                            <td>{{ $campaign->end_date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jours restants:</strong></td>
                                            <td><span class="badge bg-{{ $campaign->days_remaining > 7 ? 'success' : 'warning' }}">{{ $campaign->days_remaining }} jours</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Objectif:</strong></td>
                                            <td>{{ $campaign->goal ? number_format($campaign->goal, 0, ',', ' ') . ' €' : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Collecté:</strong></td>
                                            <td class="text-success"><strong>{{ number_format($campaign->funds_raised, 0, ',', ' ') }} €</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($campaign->description)
                            <div class="mb-4">
                                <h6>Description</h6>
                                <p class="text-muted">{{ $campaign->description }}</p>
                            </div>
                            @endif

                            @if($campaign->environmental_impact)
                            <div class="mb-4">
                                <h6>Impact environnemental</h6>
                                <p class="text-success">{{ $campaign->environmental_impact }}</p>
                            </div>
                            @endif

                            @if($campaign->tags)
                            <div class="mb-4">
                                <h6>Tags</h6>
                                <div>
                                    @foreach($campaign->tags as $tag)
                                        <span class="badge bg-light text-dark border me-1">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            @if($campaign->image_url)
                                <img src="{{ Storage::url($campaign->image_url) }}" alt="{{ $campaign->name }}" 
                                     class="img-fluid rounded mb-3" style="max-height: 300px; width: 100%; object-fit: cover;">
                            @endif
                            
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Progression financière</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $campaign->funds_progress_percentage }}%">
                                            {{ $campaign->funds_progress_percentage }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ number_format($campaign->funds_raised, 0, ',', ' ') }} € sur 
                                        {{ $campaign->goal ? number_format($campaign->goal, 0, ',', ' ') . ' €' : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5>Ressources associées ({{ $campaign->resources->count() }})</h5>
                    @if($campaign->resources->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Quantité</th>
                                        <th>Progression</th>
                                        <th>Statut</th>
                                        <th>Priorité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($campaign->resources as $resource)
                                    <tr>
                                        <td>{{ $resource->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $resource->resource_type }}</span></td>
                                        <td>
                                            {{ $resource->quantity_pledged }} / {{ $resource->quantity_needed }} {{ $resource->unit }}
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : 'warning' }}" 
                                                     style="width: {{ $resource->progress_percentage }}%">
                                                </div>
                                            </div>
                                            <small>{{ $resource->progress_percentage }}%</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $resource->status == 'received' ? 'success' : ($resource->status == 'pledged' ? 'info' : 'secondary') }}">
                                                {{ $resource->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $resource->priority == 'urgent' ? 'danger' : ($resource->priority == 'high' ? 'warning' : 'info') }}">
                                                {{ $resource->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('resources.show', $resource) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Aucune ressource associée à cette campagne.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection