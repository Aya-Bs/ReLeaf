@extends('backend.layouts.app')

@section('title', 'Gestion des Sponsors - Administration')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-handshake me-2 text-success"></i>
                Gestion des Sponsors
            </h1>
            <p class="text-muted">Administrez les demandes et partenariats de sponsoring</p>
        </div>
        <div>
            <a href="{{ route('backend.sponsors.trashed') }}" class="btn btn-outline-warning">
                <i class="fas fa-trash me-2"></i>Sponsors supprimés
            </a>
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
                                Total Sponsors
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_sponsors'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
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
                                En Attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_sponsors'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Validés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['validated_sponsors'] }}
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
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejetés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['rejected_sponsors'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsors Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Liste des Sponsors
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="sponsorsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsors as $sponsor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-success me-2"></i>
                                    <div>
                                        <div class="font-weight-bold">{{ $sponsor->company_name }}</div>
                                        @if($sponsor->city)
                                        <small class="text-muted">{{ $sponsor->city }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>{{ $sponsor->contact_email }}</div>
                                    @if($sponsor->contact_phone)
                                    <small class="text-muted">{{ $sponsor->contact_phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $sponsor->formatted_sponsorship_type }}
                                </span>
                            </td>
                            <td>
                                @if($sponsor->status === 'pending')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                                @elseif($sponsor->status === 'validated')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Validé
                                </span>
                                @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>Rejeté
                                </span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <div>{{ $sponsor->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $sponsor->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('backend.sponsors.show', $sponsor) }}"
                                        class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($sponsor->status === 'pending')
                                    <form action="{{ route('backend.sponsors.validate', $sponsor) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                            title="Valider" onclick="return confirm('Valider ce sponsor ?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        title="Rejeter" onclick="showRejectModal({{ $sponsor->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        title="Supprimer" data-toggle="modal" data-target="#deleteModal-{{ $sponsor->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal-{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $sponsor->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $sponsor->id }}">Supprimer le Sponsor</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('backend.sponsors.destroy', $sponsor) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body">
                                            <p>Êtes-vous sûr de vouloir supprimer ce sponsor ? Cette action est irréversible.</p>
                                            <div class="form-group">
                                                <label for="deletion_reason">Raison de la suppression</label>
                                                <textarea name="deletion_reason" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-handshake fa-3x mb-3"></i>
                                    <p>Aucun sponsor pour le moment</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sponsors->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $sponsors->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter le sponsor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Raison du rejet *</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                            rows="4" required placeholder="Expliquez pourquoi ce sponsor est rejeté..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showRejectModal(sponsorId) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/sponsors/${sponsorId}/reject`;
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }
</script>
@endpush