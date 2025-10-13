@extends('backend.layouts.app')

@section('title', 'Sponsors Supprimés - Administration')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-trash me-2 text-warning"></i>
                Sponsors Supprimés
            </h1>
            <p class="text-muted">Gérez les sponsors supprimés</p>
        </div>
        <div>
            <a href="{{ route('backend.sponsors.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour aux sponsors actifs
            </a>
        </div>
    </div>

    <!-- Sponsors Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Liste des Sponsors Supprimés
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="trashedSponsorsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Supprimé le</th>
                            <th>Raison</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsors as $sponsor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-muted me-2"></i>
                                    <div>
                                        <div class="font-weight-bold text-muted">{{ $sponsor->company_name }}</div>
                                        @if($sponsor->city)
                                            <small class="text-muted">{{ $sponsor->city }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="text-muted">{{ $sponsor->contact_email }}</div>
                                    @if($sponsor->contact_phone)
                                        <small class="text-muted">{{ $sponsor->contact_phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $sponsor->formatted_sponsorship_type }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <div>{{ $sponsor->deleted_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $sponsor->deleted_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $sponsor->deletion_reason }}">
                                    {{ $sponsor->deletion_reason }}
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('backend.sponsors.restore', $sponsor) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" 
                                                title="Restaurer" onclick="return confirm('Restaurer ce sponsor ?')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            title="Voir détails" onclick="showDetailsModal({{ $sponsor->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-trash fa-3x mb-3"></i>
                                    <p>Aucun sponsor supprimé</p>
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

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du sponsor supprimé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Content will be loaded here -->
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
function showDetailsModal(sponsorId) {
    // This would typically load sponsor details via AJAX
    // For now, we'll show a placeholder
    document.getElementById('detailsContent').innerHTML = `
        <div class="text-center">
            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
            <p class="text-muted">Détails du sponsor (ID: ${sponsorId})</p>
            <p class="small text-muted">Cette fonctionnalité sera implémentée avec AJAX</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
}
</script>
@endpush

