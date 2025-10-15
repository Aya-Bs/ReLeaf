@extends('backend.layouts.app')

@section('title', 'Gestion des Ressources')
@section('page-title', 'Gestion des Ressources')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Ressources</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-eco">
                <div class="card-header">
                    <h3 class="card-title">Liste des ressources</h3>
                </div>

                <div class="card-body">
                    @if($resources->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Campagne</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Statut</th>
                                    <th>Priorité</th>
                                    <th>Progression</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resources as $resource)
                                <tr onclick="window.location='{{ route('backend.resources.show', $resource) }}'" style="cursor: pointer;">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($resource->image_url)
                                                <img src="{{ Storage::url($resource->image_url) }}" 
                                                     alt="{{ $resource->name }}" 
                                                     class="rounded me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $resource->name }}</strong>
                                                @if($resource->provider)
                                                    <br><small class="text-muted">Fournisseur: {{ $resource->provider }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($resource->campaign)
                                            {{ $resource->campaign->name }}
                                        @else
                                            <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($resource->resource_type)
                                            @case('money')
                                                <span class="badge badge-success">💰 Argent</span>
                                                @break
                                            @case('food')
                                                <span class="badge badge-warning">🍕 Nourriture</span>
                                                @break
                                            @case('clothing')
                                                <span class="badge badge-info">👕 Vêtements</span>
                                                @break
                                            @case('medical')
                                                <span class="badge badge-danger">🏥 Médical</span>
                                                @break
                                            @case('equipment')
                                                <span class="badge badge-primary">🔧 Équipement</span>
                                                @break
                                            @case('human')
                                                <span class="badge badge-secondary">👥 Main d'œuvre</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">📦 Autre</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $resource->quantity_pledged }} / {{ $resource->quantity_needed }} {{ $resource->unit }}
                                        </small>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ $resource->progress_percentage }}%"
                                                     aria-valuenow="{{ $resource->progress_percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $resource->progress_percentage }}%</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $resources->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune ressource trouvée</h4>
                        <p class="text-muted">Aucune ressource n'a été créée pour le moment.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection