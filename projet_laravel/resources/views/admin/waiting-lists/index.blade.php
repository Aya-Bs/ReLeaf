@extends('backend.layouts.app')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-list-alt me-2"></i>
                    Gestion des Listes d'Attente
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Listes d'attente</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="btn-group">
                    <button class="btn btn-outline-secondary" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Actualiser
                    </button>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Événement</label>
                            <select name="event_id" class="form-select">
                                <option value="">Tous les événements</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>En attente</option>
                                <option value="promoted" {{ request('status') === 'promoted' ? 'selected' : '' }}>Promus</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulés</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-eco d-block">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>{{ $waitingLists->where('status', 'waiting')->count() }}</h3>
                            <small>En attente</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>{{ $waitingLists->where('status', 'promoted')->count() }}</h3>
                            <small>Promus</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h3>{{ $waitingLists->where('status', 'cancelled')->count() }}</h3>
                            <small>Annulés</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ $waitingLists->count() }}</h3>
                            <small>Total</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table des listes d'attente -->
            <div class="card">
                <div class="card-body">
                    @if($waitingLists->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Position</th>
                                        <th>Utilisateur</th>
                                        <th>Événement</th>
                                        <th>Statut</th>
                                        <th>Rejoint le</th>
                                        <th>Promu le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($waitingLists as $waitingList)
                                        <tr class="waiting-list-row" data-status="{{ $waitingList->status }}">
                                            <td>
                                                @if($waitingList->status === 'waiting')
                                                    <span class="badge bg-primary">{{ $waitingList->position }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2">
                                                        {{ strtoupper(substr($waitingList->user_name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $waitingList->user_name }}</strong><br>
                                                        <small class="text-muted">{{ $waitingList->user_email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $waitingList->event->title }}</strong><br>
                                                <small class="text-muted">{{ $waitingList->event->date->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @switch($waitingList->status)
                                                    @case('waiting')
                                                        <span class="badge bg-warning">En attente</span>
                                                        @break
                                                    @case('promoted')
                                                        <span class="badge bg-success">Promu</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Annulé</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                {{ $waitingList->joined_at ? $waitingList->joined_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td>
                                                @if($waitingList->promoted_at)
                                                    {{ $waitingList->promoted_at->format('d/m/Y H:i') }}<br>
                                                    <small class="text-muted">par {{ $waitingList->promotedBy->name ?? 'Admin' }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($waitingList->status === 'waiting')
                                                    <div class="btn-group btn-group-sm">
                                                        <form action="{{ route('admin.waiting-lists.promote', $waitingList) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="Promouvoir manuellement" onclick="return confirm('Promouvoir cet utilisateur de la liste d\'attente ?')">
                                                                <i class="fas fa-arrow-up"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif($waitingList->status === 'promoted')
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                        Réservation créée
                                                    </span>
                                                @else
                                                    <span class="text-muted">Aucune action</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $waitingLists->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune liste d'attente trouvée</h5>
                            <p class="text-muted">Les listes d'attente apparaîtront ici quand les événements seront complets.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #2d5a27;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
.btn-eco {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.btn-eco:hover {
    background-color: #234420;
    border-color: #234420;
    color: white;
}
.waiting-list-row:hover {
    background-color: #f8f9fa;
}
</style>
@endpush
@endsection
