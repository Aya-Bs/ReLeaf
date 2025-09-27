@extends('backend.layouts.app')

@section('title', 'Gestion des Ressources')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Gestion des Ressources</h1>
                <div>
                    <a href="{{ route('resources.high-priority') }}" class="btn btn-warning me-2">
                        <i class="fas fa-exclamation-triangle"></i> Ressources Prioritaires
                    </a>
                    <a href="{{ route('resources.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nouvelle Ressource
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">Liste des Ressources</h5>
                    <p class="text-muted mb-0">Total: {{ $resources->total() }} ressources</p>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par nom..." value="{{ request('search') }}">
                        <select name="campaign_id" class="form-control" style="width: 200px;">
                            <option value="">Toutes les campagnes</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ Str::limit($campaign->name, 25) }}
                                </option>
                            @endforeach
                        </select>
                        <select name="status" class="form-control" style="width: 150px;">
                            <option value="">Tous statuts</option>
                            <option value="needed" {{ request('status') == 'needed' ? 'selected' : '' }}>Nécessaire</option>
                            <option value="pledged" {{ request('status') == 'pledged' ? 'selected' : '' }}>Promis</option>
                            <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Reçu</option>
                            <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>Utilisé</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h4>{{ $totalResources }}</h4>
                            <p class="mb-0">Total Ressources</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <h4>{{ $urgentResources }}</h4>
                            <p class="mb-0">Urgentes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4>{{ $completedResources }}</h4>
                            <p class="mb-0">Complétées</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4>{{ $neededResources }}</h4>
                            <p class="mb-0">À pourvoir</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Campagne</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Progression</th>
                            <th>Statut</th>
                            <th>Priorité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resources as $resource)
                        <tr>
                            <td>{{ $resource->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($resource->image_url)
                                        <img src="{{ Storage::url($resource->image_url) }}" 
                                             alt="{{ $resource->name }}"
                                             class="rounded me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-2"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-box text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $resource->name }}</strong>
                                        @if($resource->provider)
                                            <br><small class="text-muted">Par: {{ $resource->provider }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('campaigns.show', $resource->campaign) }}" 
                                   class="text-decoration-none" title="Voir la campagne">
                                    {{ Str::limit($resource->campaign->name, 20) }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $resource->resource_type }}</span>
                                <br>
                                <small class="text-muted">{{ $resource->category }}</small>
                            </td>
                            <td>
                                <strong class="text-success">{{ $resource->quantity_pledged }}</strong> 
                                / 
                                <span class="text-primary">{{ $resource->quantity_needed }}</span>
                                <small class="text-muted d-block">{{ $resource->unit }}</small>
                            </td>
                            <td>
                                <div class="progress mb-1" style="height: 12px;">
                                    <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : ($resource->progress_percentage > 50 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $resource->progress_percentage }}%"
                                         title="{{ $resource->progress_percentage }}%">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $resource->progress_percentage }}%</small>
                                @if($resource->missing_quantity > 0)
                                    <br><small class="text-danger">Manque: {{ $resource->missing_quantity }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ [
                                    'needed' => 'secondary',
                                    'pledged' => 'info', 
                                    'received' => 'success',
                                    'in_use' => 'primary'
                                ][$resource->status] ?? 'dark' }}">
                                    {{ $resource->status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ [
                                    'low' => 'success',
                                    'medium' => 'info',
                                    'high' => 'warning',
                                    'urgent' => 'danger'
                                ][$resource->priority] }}">
                                    <i class="fas fa-{{ $resource->priority == 'urgent' ? 'exclamation-triangle' : 'flag' }}"></i>
                                    {{ $resource->priority }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('resources.show', $resource) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('resources.edit', $resource) }}" 
                                       class="btn btn-warning btn-sm" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Bouton statut rapide -->
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" 
                                                type="button" 
                                                data-bs-toggle="dropdown"
                                                title="Changer le statut">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach(['needed' => 'Nécessaire', 'pledged' => 'Promis', 'received' => 'Reçu', 'in_use' => 'Utilisé'] as $value => $label)
                                                <li>
                                                    <form action="{{ route('resources.update-status', $resource) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="dropdown-item {{ $resource->status == $value ? 'active' : '' }}"
                                                                onclick="return confirm('Changer le statut à {{ $label }}?')">
                                                            {{ $label }}
                                                        </button>
                                                        <input type="hidden" name="status" value="{{ $value }}">
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <form action="{{ route('resources.destroy', $resource) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune ressource trouvée</h5>
                                <p class="text-muted">Commencez par créer votre première ressource</p>
                                <a href="{{ route('resources.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Créer une ressource
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($resources->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p class="mb-0">
                        Affichage de <strong>{{ $resources->firstItem() }}</strong> à 
                        <strong>{{ $resources->lastItem() }}</strong> sur 
                        <strong>{{ $resources->total() }}</strong> ressources
                    </p>
                </div>
                <div>
                    {{ $resources->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.progress {
    background-color: #e9ecef;
    border-radius: 0.375rem;
}
.progress-bar {
    border-radius: 0.375rem;
    transition: width 0.6s ease;
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}
</style>
@endsection

@section('scripts')
<script>
// Confirmation pour les actions sensibles
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation pour la suppression
    const deleteForms = document.querySelectorAll('form[action*="/destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette ressource ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });

    // Tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection