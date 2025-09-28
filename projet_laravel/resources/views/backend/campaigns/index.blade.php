@extends('backend.layouts.app')

@section('title', 'Gestion des Campagnes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Gestion des Campagnes</h1>
                <a href="{{ route('campaigns.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nouvelle Campagne
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">Liste des Campagnes</h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Rechercher..." value="{{ request('search') }}">
                        <select name="category" class="form-control me-2">
                            <option value="">Toutes les catégories</option>
                            @foreach(['reforestation', 'nettoyage', 'sensibilisation', 'recyclage', 'biodiversite', 'energie_renouvelable', 'autre'] as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Dates</th>
                            <th>Financement</th>
                            <th>Participants</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->id }}</td>
                            <td>
                                @if($campaign->image_url)
                                    <img src="{{ Storage::url($campaign->image_url) }}" alt="{{ $campaign->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $campaign->name }}</strong>
                                @if(!$campaign->visibility)
                                    <span class="badge bg-warning ms-1">Privée</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $campaign->category }}</span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $campaign->start_date->format('d/m/Y') }}<br>
                                    au {{ $campaign->end_date->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="progress mb-1" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: {{ $campaign->funds_progress_percentage }}%"></div>
                                </div>
                                <small>
                                    {{ number_format($campaign->funds_raised, 0, ',', ' ') }} € / 
                                    {{ $campaign->goal ? number_format($campaign->goal, 0, ',', ' ') . ' €' : 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $campaign->participants_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $campaign->status == 'active' ? 'success' : ($campaign->status == 'completed' ? 'info' : 'secondary') }}">
                                    {{ $campaign->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-info btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('campaigns.toggle-visibility', $campaign) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $campaign->visibility ? 'secondary' : 'success' }} btn-sm" title="{{ $campaign->visibility ? 'Rendre privée' : 'Rendre publique' }}">
                                            <i class="fas fa-{{ $campaign->visibility ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('campaigns.destroy', $campaign) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune campagne trouvée</p>
                                <a href="{{ route('campaigns.create') }}" class="btn btn-primary">Créer une campagne</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0">Affichage de {{ $campaigns->firstItem() }} à {{ $campaigns->lastItem() }} sur {{ $campaigns->total() }} campagnes</p>
                </div>
                <div>
                    {{ $campaigns->links() }}
                </div>
            </div>
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
    background-color: rgba(0, 0, 0, 0.075);
}
</style>
@endsection