@extends('backend.layouts.app')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-ticket-alt me-2"></i>
                    Gestion des Réservations
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Réservations</li>
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
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmées</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirées</option>
                                <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>En liste d'attente</option>
                            </select>
                        </div>
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
            @php
                $pendingCount = \App\Models\Reservation::where('status', 'pending')->count();
                $confirmedCount = \App\Models\Reservation::where('status', 'confirmed')->count();
                $cancelledCount = \App\Models\Reservation::where('status', 'cancelled')->count();
                $waitingCount = \App\Models\WaitingList::where('status', 'waiting')->count();
            @endphp
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>{{ $pendingCount }}</h3>
                            <small>En attente</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>{{ $confirmedCount }}</h3>
                            <small>Confirmées</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h3>{{ $cancelledCount }}</h3>
                            <small>Annulées</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ $waitingCount }}</h3>
                            <small>Liste d'attente</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table des réservations -->
            <div class="card">
                <div class="card-body">
                    @if($reservations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Événement</th>
                                        <th>Place</th>
                                        <th>Invités</th>
                                        <th>Statut</th>
                                        <th>Réservé le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $item)
                                        <tr class="reservation-row" data-status="{{ $item->status }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->type === 'reservation')
                                                        <img src="{{ $item->user->avatar_url }}" alt="Avatar" class="rounded-circle me-2" width="32">
                                                        <div>
                                                            <strong>{{ $item->user_name }}</strong><br>
                                                            <small class="text-muted">{{ $item->user->email }}</small>
                                                        </div>
                                                    @else
                                                        <div class="avatar-circle me-2">
                                                            {{ strtoupper(substr($item->user_name, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <strong>{{ $item->user_name }}</strong><br>
                                                            <small class="text-muted">{{ $item->user_email }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $item->event->title }}</strong><br>
                                                <small class="text-muted">{{ $item->event->date->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($item->type === 'reservation')
                                                    <span class="badge bg-info">{{ $item->seat_number }}</span>
                                                @else
                                                    <span class="badge bg-warning">Position {{ $item->position ?? '-' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->type === 'reservation')
                                                    {{ $item->num_guests }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->type === 'reservation')
                                                    @switch($item->status)
                                                        @case('pending')
                                                            <span class="badge bg-warning">En attente</span>
                                                            @break
                                                        @case('confirmed')
                                                            <span class="badge bg-success">Confirmée</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-danger">Annulée</span>
                                                            @break
                                                        @case('expired')
                                                            <span class="badge bg-secondary">Expirée</span>
                                                            @break
                                                    @endswitch
                                                @else
                                                    @switch($item->status)
                                                        @case('waiting')
                                                            <span class="badge bg-info">Liste d'attente</span>
                                                            @break
                                                        @case('promoted')
                                                            <span class="badge bg-success">Promu</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-danger">Annulé</span>
                                                            @break
                                                    @endswitch
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->type === 'reservation')
                                                    {{ $item->reserved_at->format('d/m/Y H:i') }}
                                                @else
                                                    {{ $item->joined_at ? $item->joined_at->format('d/m/Y H:i') : '-' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->type === 'reservation')
                                                    @if($item->canBeConfirmed())
                                                        <div class="btn-group btn-group-sm">
                                                            <form action="{{ route('admin.reservations.confirm', $item) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" title="Confirmer">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.reservations.reject', $item) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm" title="Rejeter">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($item->status === 'confirmed')
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-success me-2">
                                                                <i class="fas fa-check-circle"></i>
                                                                {{ $item->confirmedBy->name ?? 'Admin' }}
                                                            </span>
                                                            <form action="{{ route('admin.reservations.destroy', $item) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                        title="Supprimer définitivement" 
                                                                        onclick="return confirm('⚠️ ATTENTION: Cette action supprimera définitivement la réservation et son certificat de la base de données. Cette action est irréversible.\\n\\nÊtes-vous sûr de vouloir continuer ?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted me-2">Aucune action</span>
                                                            <form action="{{ route('admin.reservations.destroy', $item) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                        title="Supprimer définitivement" 
                                                                        onclick="return confirm('⚠️ ATTENTION: Cette action supprimera définitivement la réservation et son certificat de la base de données. Cette action est irréversible.\\n\\nÊtes-vous sûr de vouloir continuer ?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @else
                                                    @if($item->status === 'waiting')
                                                        <div class="btn-group btn-group-sm">
                                                            <form action="{{ route('admin.waiting-lists.promote', $item) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" title="Promouvoir" onclick="return confirm('Promouvoir cet utilisateur de la liste d\'attente ?')">
                                                                    <i class="fas fa-arrow-up"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($item->status === 'promoted')
                                                        <span class="text-success">
                                                            <i class="fas fa-check-circle"></i>
                                                            Réservation créée
                                                        </span>
                                                    @else
                                                        <span class="text-muted">Aucune action</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $reservations->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune réservation trouvée</h5>
                            <p class="text-muted">Les réservations apparaîtront ici une fois que les utilisateurs auront commencé à réserver des places.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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
.text-eco {
    color: #2d5a27;
}
.reservation-row:hover {
    background-color: #f8f9fa;
}
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
.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}
.btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
}
.btn-outline-danger:hover i {
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh toutes les 60 secondes pour voir les nouvelles réservations
    setTimeout(function() {
        location.reload();
    }, 60000);
});
</script>
@endpush
@endsection
