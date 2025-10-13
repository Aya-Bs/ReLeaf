@extends('backend.layouts.app')

@section('title', 'Gestion des Dons - Administration')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-donate me-2 text-success"></i>
                Gestion des Dons
            </h1>
            <p class="text-muted">Administrez tous les dons de la plateforme</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Dons
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_donations'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-donate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Montant Confirmé
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['confirmed_amount'], 0, ',', ' ') }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Montant Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_amount'], 0, ',', ' ') }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En Attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['pending_amount'], 0, ',', ' ') }} €
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Liste des Dons
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="donationsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Événement</th>
                            <th>Donateur</th>
                            <th>Type</th>
                            <th>Montant</th>
                            <th>Méthode</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <div>
                                        <div class="font-weight-bold">{{ $donation->event->title }}</div>
                                        <small class="text-muted">{{ $donation->event->date ? $donation->event->date->format('d/m/Y') : 'Date TBD' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($donation->type === 'individual' && $donation->user)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <div>
                                            <div class="font-weight-bold">{{ $donation->user->name }}</div>
                                            <small class="text-muted">{{ $donation->user->email }}</small>
                                        </div>
                                    </div>
                                @elseif($donation->type === 'sponsor' && $donation->sponsor)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-success me-2"></i>
                                        <div>
                                            <div class="font-weight-bold">{{ $donation->sponsor->company_name }}</div>
                                            <small class="text-muted">{{ $donation->sponsor->contact_email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-question-circle text-muted me-2"></i>
                                        <div>
                                            <div class="font-weight-bold text-muted">Donateur anonyme</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $donation->type === 'individual' ? 'primary' : 'success' }}">
                                    {{ $donation->type === 'individual' ? 'Individuel' : 'Sponsor' }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold text-success">
                                    {{ number_format($donation->amount, 2, ',', ' ') }} {{ $donation->currency }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $donation->payment_method)) }}
                                </span>
                            </td>
                            <td>
                                @if($donation->status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @elseif($donation->status === 'confirmed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Confirmé
                                    </span>
                                @elseif($donation->status === 'refunded')
                                    <span class="badge bg-info">
                                        <i class="fas fa-undo me-1"></i>Remboursé
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Annulé
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <div>{{ $donation->donated_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $donation->donated_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($donation->status === 'pending')
                                        <form action="{{ route('backend.donations.confirm', $donation) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                    title="Confirmer" onclick="return confirm('Confirmer ce don ?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                title="Annuler" onclick="showCancelModal({{ $donation->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    @if($donation->notes)
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                title="Voir le message" onclick="showNotesModal('{{ $donation->notes }}')">
                                            <i class="fas fa-comment"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-donate fa-3x mb-3"></i>
                                    <p>Aucun don pour le moment</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donations->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $donations->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annuler le don</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label">Raison de l'annulation *</label>
                        <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" 
                                  rows="4" required placeholder="Expliquez pourquoi ce don est annulé..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message du donateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="notesContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showCancelModal(donationId) {
    const form = document.getElementById('cancelForm');
    form.action = `/admin/donations/${donationId}/cancel`;
    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

function showNotesModal(notes) {
    document.getElementById('notesContent').innerHTML = `<p>${notes}</p>`;
    const modal = new bootstrap.Modal(document.getElementById('notesModal'));
    modal.show();
}
</script>
@endpush

