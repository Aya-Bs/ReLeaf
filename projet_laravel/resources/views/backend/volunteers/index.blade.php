@extends('backend.layouts.app')

@section('title', 'Gestion des Volontaires')
@section('page-title', 'Gestion des Volontaires')

@section('breadcrumb')
    <li class="breadcrumb-item active">Volontaires</li>
@endsection

@section('content')
<!-- Statistiques -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Volontaires</p>
            </div>
            <div class="icon">
                <i class="fas fa-hands-helping"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['active'] }}</h3>
                <p>Actifs</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['inactive'] }}</h3>
                <p>Inactifs</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-times"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ \App\Models\Assignment::count() }}</h3>
                <p>Missions</p>
            </div>
            <div class="icon">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques d'approbation -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending'] }}</h3>
                <p>En attente d'approbation</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['approved'] }}</h3>
                <p>Approuvés</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['rejected'] }}</h3>
                <p>Rejetés</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="card card-eco">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter mr-1"></i>
            Filtres
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('backend.volunteers.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Statut</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="approval_status">Statut d'approbation</label>
                        <select class="form-control" id="approval_status" name="approval_status">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                            <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="search">Rechercher</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nom ou email...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Liste des volontaires -->
<div class="card card-eco">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-1"></i>
            Liste des Volontaires
        </h3>
    </div>
    <div class="card-body">
        @if($volunteers->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Approbation</th>
                            <th>Expérience</th>
                            <th>Heures max/semaine</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($volunteers as $volunteer)
                            <tr>
                                <td>{{ $volunteer->id }}</td>
                                <td>
                                    <strong>{{ $volunteer->user->name }}</strong>
                                </td>
                                <td>{{ $volunteer->user->email }}</td>
                                <td>
                                    <span class="badge badge-{{ $volunteer->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($volunteer->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($volunteer->approval_status === 'pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                    @elseif($volunteer->approval_status === 'approved')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Approuvé
                                        </span>
                                    @elseif($volunteer->approval_status === 'rejected')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Rejeté
                                        </span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($volunteer->experience_level) }}</td>
                                <td>{{ $volunteer->max_hours_per_week }}h</td>
                                <td>{{ $volunteer->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('backend.volunteers.show', $volunteer) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.volunteers.edit', $volunteer) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($volunteer->approval_status === 'pending')
                                            <form method="POST" action="{{ route('backend.volunteers.approve', $volunteer) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Approuver ce volontaire ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Approuver">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('backend.volunteers.reject', $volunteer) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Rejeter ce volontaire ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Rejeter">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @elseif($volunteer->approval_status === 'approved')
                                            <form method="POST" action="{{ route('backend.volunteers.reset', $volunteer) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Réinitialiser l\'approbation ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="Réinitialiser">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @elseif($volunteer->approval_status === 'rejected')
                                            <form method="POST" action="{{ route('backend.volunteers.approve', $volunteer) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Approuver ce volontaire ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Approuver">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('backend.volunteers.reset', $volunteer) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Réinitialiser l\'approbation ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="Réinitialiser">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('backend.volunteers.destroy', $volunteer) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Supprimer ce volontaire ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $volunteers->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle mr-2"></i>
                Aucun volontaire trouvé.
            </div>
        @endif
    </div>
</div>
@endsection
