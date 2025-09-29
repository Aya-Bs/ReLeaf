@extends('backend.layouts.app')

@section('title', 'Gestion des Campagnes')
@section('page-title', 'Gestion des Campagnes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Campagnes</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-eco">
                <div class="card-header">
                    <h3 class="card-title">Liste des campagnes</h3>
                </div>

                <div class="card-body">
                    @if($campaigns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Organisateur</th>
                                    <th>Catégorie</th>
                                    <th>Date de début</th>
                                    <th>Date de fin</th>
                                    <th>Statut</th>
                                    <th>Progression</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($campaigns as $campaign)
                                <tr onclick="window.location='{{ route('backend.campaigns.show', $campaign) }}'" style="cursor: pointer;">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($campaign->image_url)
                                                <img src="{{ Storage::url($campaign->image_url) }}" 
                                                     alt="{{ $campaign->name }}" 
                                                     class="rounded me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-leaf text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $campaign->name }}</strong>
                                                @if(!$campaign->visibility)
                                                    <i class="fas fa-eye-slash text-warning ms-1" title="Non visible publiquement"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $campaign->organizer->name }}</td>
                                    <td>
                                        @switch($campaign->category)
                                            @case('reforestation')
                                                <span class="badge badge-success">🌲 Reforestation</span>
                                                @break
                                            @case('nettoyage')
                                                <span class="badge badge-info">🧹 Nettoyage</span>
                                                @break
                                            @case('sensibilisation')
                                                <span class="badge badge-warning">📢 Sensibilisation</span>
                                                @break
                                            @case('recyclage')
                                                <span class="badge badge-primary">♻️ Recyclage</span>
                                                @break
                                            @case('biodiversite')
                                                <span class="badge badge-success">🦋 Biodiversité</span>
                                                @break
                                            @case('energie_renouvelable')
                                                <span class="badge badge-warning">⚡ Énergie</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">🔧 Autre</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="badge badge-light">
                                            {{ $campaign->start_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">
                                            {{ $campaign->end_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ $campaign->funds_progress_percentage }}%"
                                                     aria-valuenow="{{ $campaign->funds_progress_percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $campaign->funds_progress_percentage }}%</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $campaigns->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-leaf fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune campagne trouvée</h4>
                        <p class="text-muted">Aucune campagne n'a été créée pour le moment.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection