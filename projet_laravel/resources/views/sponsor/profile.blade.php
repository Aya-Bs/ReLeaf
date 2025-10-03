@extends('layouts.app')

@section('title','Mon Profil Sponsor')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1 text-success"><i class="fas fa-building me-2"></i>Profil Sponsor</h1>
            <p class="text-muted mb-0">
                @if($validatedAt)
                Sponsor validé depuis {{ $validatedAt->format('d/m/Y') }} ({{ $joinedSinceDays }} jours)
                @else
                Compte créé il y a {{ $joinedSinceDays }} jours (en attente ou non validé)
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sponsor.dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-home me-1"></i>Dashboard</a>
            <a href="{{ route('profile.edit.extended') }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Modifier</a>
            @if($sponsor && !$sponsor->isDeletionRequested())
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSponsorRequestModal"><i class="fas fa-user-slash me-1"></i>Demander suppression</button>
            @elseif($sponsor && $sponsor->isDeletionRequested())
            <span class="badge bg-warning text-dark align-self-center"><i class="fas fa-clock me-1"></i>Suppression demandée</span>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar_url }}" alt="avatar" class="rounded-circle mb-3" width="120" height="120">
                    <h5 class="mb-0">{{ $user->full_name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <p class="small mb-0"><i class="fas fa-calendar-alt me-1"></i>Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white py-2"><strong>Résumé</strong></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><i class="fas fa-donate text-success me-1"></i>Total dons : <strong>{{ number_format($totalDonations,2,',',' ') }}</strong></li>
                        <li class="mb-2"><i class="fas fa-gift text-primary me-1"></i>Nombre de dons : <strong>{{ $donationsCount }}</strong></li>
                        <li class="mb-2"><i class="fas fa-people-carry text-warning me-1"></i>Événements sponsorisés : <strong>{{ $eventsSponsoredCount }}</strong></li>
                        @if($sponsor)
                        <li class="mb-2"><i class="fas fa-flag-checkered text-info me-1"></i>Type : {{ $sponsor->formatted_sponsorship_type }}</li>
                        <li class="mb-2"><i class="fas fa-info-circle text-secondary me-1"></i>Status : <span class="badge bg-{{ $sponsor->isDeletionRequested() ? 'warning text-dark' : ($sponsor->isValidated() ? 'success' : ($sponsor->isPending() ? 'secondary' : 'danger')) }}">{{ ucfirst($sponsor->status) }}</span></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white"><strong><i class="fas fa-building me-2 text-success"></i>Informations Entreprise</strong></div>
                <div class="card-body">
                    @if($sponsor)
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <label class="text-muted mb-0">Entreprise</label>
                            <div class="fw-semibold">{{ $sponsor->company_name ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-0">Email contact</label>
                            <div class="fw-semibold">{{ $sponsor->contact_email ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-0">Téléphone</label>
                            <div class="fw-semibold">{{ $sponsor->contact_phone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-0">Site web</label>
                            <div class="fw-semibold">@if($sponsor->website)<a href="{{ $sponsor->website }}" target="_blank">{{ $sponsor->website }}</a>@else — @endif</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-0">Ville</label>
                            <div class="fw-semibold">{{ $sponsor->city ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-0">Pays</label>
                            <div class="fw-semibold">{{ $sponsor->country ?? '—' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted mb-0">Motivation</label>
                            <div class="fw-semibold">{{ $sponsor->motivation ?? '—' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted mb-0">Informations supplémentaires</label>
                            <div class="fw-semibold">{{ $sponsor->additional_info ?? '—' }}</div>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0">Aucun profil sponsor validé pour l'instant.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong><i class="fas fa-clock me-2 text-success"></i>Dons récents</strong>
                    <a href="{{ route('donations.list') }}" class="small">Tout voir &raquo;</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        @forelse($recentDonations as $donation)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-2">
                                <div class="fw-semibold">{{ $donation->event?->title ?? 'Événement' }}</div>
                                <small class="text-muted">{{ ($donation->donated_at ?? $donation->created_at)->format('d/m/Y H:i') }} · {{ number_format($donation->amount,2,',',' ') }} {{ $donation->currency }}</small><br>
                                <small>
                                    @php $r = $donation->editableRemainingHours(); @endphp
                                    <span class="badge bg-{{ $donation->status === 'pending' ? 'secondary' : ($donation->status === 'confirmed' ? 'success' : 'danger') }}">{{ ucfirst($donation->status) }}</span>
                                    @if($donation->canBeModifiedBy($user))
                                    <span class="badge bg-info text-dark">Modifiable {{ $r }}h</span>
                                    @endif
                                </small>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1">
                                @if($donation->canBeModifiedBy($user))
                                <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('donations.destroy', $donation) }}" onsubmit="return confirm('Supprimer ce don ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">Aucun don récent.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@if($sponsor && !$sponsor->isDeletionRequested())
<!-- Modal demande suppression sponsor -->
<div class="modal fade" id="deleteSponsorRequestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('sponsor.self.requestDeletion') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-slash text-danger me-2"></i>Demande de suppression sponsor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Expliquez la raison de votre demande. Cette action devra être validée par un administrateur.</p>
                    <div class="mb-3">
                        <label class="form-label">Raison (min 10 caractères)</label>
                        <textarea name="reason" rows="4" class="form-control" required minlength="10" placeholder="Raison de la suppression..."></textarea>
                    </div>
                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-info-circle me-1"></i>Votre compte sponsor restera actif tant que l'admin n'aura pas confirmé.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-danger"><i class="fas fa-paper-plane me-1"></i>Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($sponsor && !$sponsor->isDeletionRequested())
<!-- Modal suppression immédiate -->
<div class="modal fade" id="deleteSponsorNowModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('sponsor.self.deleteNow') }}" onsubmit="return confirm('Confirmer suppression immédiate ? Cette action est irréversible.');">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Suppression immédiate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Cette action va :</p>
                    <ul class="small mb-3">
                        <li>Marquer votre profil sponsor comme supprimé (soft delete)</li>
                        <li>Vous déconnecter</li>
                        <li>Rétrograder votre compte utilisateur en rôle 'user'</li>
                        <li>Préserver l'historique de vos dons</li>
                    </ul>
                    <div class="alert alert-warning small"><i class="fas fa-info-circle me-1"></i>Cette opération est irréversible sans intervention admin.</div>
                    <div class="mb-3">
                        <label class="form-label">Tapez <strong>DELETE</strong> pour confirmer</label>
                        <input type="text" name="confirm" class="form-control" required pattern="DELETE" placeholder="DELETE">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-danger"><i class="fas fa-fire me-1"></i>Supprimer maintenant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection