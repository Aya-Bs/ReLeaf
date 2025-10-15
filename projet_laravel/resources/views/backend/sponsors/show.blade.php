@extends('backend.layouts.app')

@section('title', $sponsor->company_name . ' - Administration')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-building me-2 text-success"></i>
                {{ $sponsor->company_name }}
            </h1>
            <p class="text-muted">Détails du sponsor</p>
        </div>
        <div>
            <a href="{{ route('backend.sponsors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sponsor Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informations du sponsor
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nom de l'entreprise</label>
                                <p class="form-control-plaintext">{{ $sponsor->company_name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email de contact</label>
                                <p class="form-control-plaintext">
                                    <a href="mailto:{{ $sponsor->contact_email }}">{{ $sponsor->contact_email }}</a>
                                </p>
                            </div>
                            @if($sponsor->contact_phone)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Téléphone</label>
                                <p class="form-control-plaintext">{{ $sponsor->contact_phone }}</p>
                            </div>
                            @endif
                            @if($sponsor->website)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Site web</label>
                                <p class="form-control-plaintext">
                                    <a href="{{ $sponsor->website }}" target="_blank">{{ $sponsor->website }}</a>
                                </p>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($sponsor->address)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Adresse</label>
                                <p class="form-control-plaintext">{{ $sponsor->address }}</p>
                            </div>
                            @endif
                            @if($sponsor->city)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ville</label>
                                <p class="form-control-plaintext">{{ $sponsor->city }}</p>
                            </div>
                            @endif
                            @if($sponsor->country)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pays</label>
                                <p class="form-control-plaintext">{{ $sponsor->country }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motivation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heart me-2"></i>Motivation du partenariat
                    </h6>
                </div>
                <div class="card-body">
                    <p>{{ $sponsor->motivation }}</p>
                </div>
            </div>

            <!-- Additional Info -->
            @if($sponsor->additional_info)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informations supplémentaires
                    </h6>
                </div>
                <div class="card-body">
                    <p>{{ $sponsor->additional_info }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Statut
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if($sponsor->status === 'pending')
                        <span class="badge bg-warning fs-6 p-3 mb-3">
                            <i class="fas fa-clock me-2"></i>En attente
                        </span>
                        <p class="text-muted">En attente de validation</p>
                    @elseif($sponsor->status === 'validated')
                        <span class="badge bg-success fs-6 p-3 mb-3">
                            <i class="fas fa-check me-2"></i>Validé
                        </span>
                        @if($sponsor->validated_at)
                            <p class="text-muted">Validé le {{ $sponsor->validated_at->format('d/m/Y à H:i') }}</p>
                        @endif
                    @else
                        <span class="badge bg-danger fs-6 p-3 mb-3">
                            <i class="fas fa-times me-2"></i>Rejeté
                        </span>
                    @endif

                    <div class="mt-3">
                        <span class="badge bg-info fs-6 p-2">
                            {{ $sponsor->formatted_sponsorship_type }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($sponsor->status === 'pending')
                        <form action="{{ route('backend.sponsors.validate', $sponsor) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" 
                                    onclick="return confirm('Valider ce sponsor ?')">
                                <i class="fas fa-check me-2"></i>Valider
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger w-100 mb-2" 
                                onclick="showRejectModal()">
                            <i class="fas fa-times me-2"></i>Rejeter
                        </button>
                    @endif
                    
                    <button type="button" class="btn btn-warning w-100" 
                            onclick="showDeleteModal()">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success">{{ $sponsor->events->count() }}</h4>
                                <small class="text-muted">Événements</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-primary">{{ $sponsor->donations->count() }}</h4>
                            <small class="text-muted">Dons</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Section -->
    @if($sponsor->events->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Événements soutenus
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Événement</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sponsor->events as $event)
                                <tr>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->date ? $event->date->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @if($event->pivot->amount)
                                            {{ number_format($event->pivot->amount, 0, ',', ' ') }} €
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $event->pivot->status === 'active' ? 'success' : ($event->pivot->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($event->pivot->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter le sponsor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backend.sponsors.reject', $sponsor) }}" method="POST">
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer le sponsor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backend.sponsors.destroy', $sponsor) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="deletion_reason" class="form-label">Raison de la suppression *</label>
                        <textarea class="form-control" id="deletion_reason" name="deletion_reason" 
                                  rows="4" required placeholder="Expliquez pourquoi ce sponsor est supprimé..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Cette action supprimera définitivement le sponsor et toutes ses données associées.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRejectModal() {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

function showDeleteModal() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush

