@extends('backend.layouts.app')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Utilisateurs</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>
                    Liste des utilisateurs
                </h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <form method="GET" action="{{ route('backend.users.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control float-right" 
                                   placeholder="Rechercher..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card-body border-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group" role="group">
                            <a href="{{ route('backend.users.index') }}" 
                               class="btn {{ !request('eco_ambassador') ? 'btn-eco' : 'btn-outline-secondary' }}">
                                <i class="fas fa-users mr-1"></i>Tous les utilisateurs
                            </a>
                            <a href="{{ route('backend.users.index', ['eco_ambassador' => 1]) }}" 
                               class="btn {{ request('eco_ambassador') ? 'btn-eco' : 'btn-outline-secondary' }}">
                                <i class="fas fa-leaf mr-1"></i>Ambassadeurs écologiques
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <span class="badge badge-info">{{ $users->total() }} utilisateur(s) régulier(s)</span>
                    </div>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Profil</th>
                            <th>Statut</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <img src="{{ $user->avatar_url }}" alt="Avatar" 
                                         class="img-circle img-size-32">
                                </td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->profile)
                                        <br><small class="text-muted">{{ $user->profile->full_name }}</small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->profile)
                                        @if($user->profile->city)
                                            <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                            {{ $user->profile->city }}
                                            @if($user->profile->country)
                                                , {{ $user->profile->country }}
                                            @endif
                                            <br>
                                        @endif
                                        @if($user->profile->phone)
                                            <i class="fas fa-phone text-muted mr-1"></i>
                                            {{ $user->profile->phone }}
                                        @endif
                                    @else
                                        <span class="text-muted">Profil non complété</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->profile && $user->profile->is_eco_ambassador)
                                        <span class="badge badge-success">
                                            <i class="fas fa-leaf mr-1"></i>Ambassadeur
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Utilisateur</span>
                                    @endif
                                    
                                    @if($user->email_verified_at)
                                        <br><span class="badge badge-info">Email vérifié</span>
                                    @else
                                        <br><span class="badge badge-warning">Email non vérifié</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->created_at->format('d/m/Y') }}
                                    <br><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('backend.users.show', $user) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.users.edit', $user) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Toggle Eco Ambassador -->
                                        <form method="POST" 
                                              action="{{ route('backend.users.toggle-eco-ambassador', $user) }}" 
                                              style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $user->profile && $user->profile->is_eco_ambassador ? 'btn-success' : 'btn-outline-success' }}" 
                                                    title="{{ $user->profile && $user->profile->is_eco_ambassador ? 'Retirer le statut d\'ambassadeur' : 'Promouvoir ambassadeur' }}">
                                                <i class="fas fa-leaf"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun utilisateur trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="card-footer">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmer la suppression</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteUser(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    $('#deleteModal').modal('show');
}
</script>
@endpush
