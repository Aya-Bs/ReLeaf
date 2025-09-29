@extends('layouts.frontend')

@section('title', 'Mon Profil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-eco">
                    <i class="fas fa-user-circle me-2"></i>Mon Profil
                </h2>
                <a href="{{ route('profile.edit.extended') }}" class="btn btn-eco">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                @if($user->role === 'sponsor' && $user->sponsor)
                @if($user->sponsor->isDeletionRequested())
                <span class="badge bg-warning text-dark ms-2"><i class="fas fa-clock me-1"></i>Suppression demandée</span>
                @else
                <button type="button" class="btn btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteSponsorRequestModal">
                    <i class="fas fa-user-slash me-1"></i>Demander suppression
                </button>
                @endif
                @endif
            </div>

            <div class="row">
                <!-- Informations principales -->
                <div class="col-md-4">
                    <div class="card eco-card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="{{ $user->avatar_url }}" alt="Avatar"
                                    class="rounded-circle img-thumbnail"
                                    width="120" height="120">
                            </div>
                            <h4 class="card-title">{{ $user->full_name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>

                            @if($user->profile && $user->profile->is_eco_ambassador)
                            <span class="badge bg-success">
                                <i class="fas fa-leaf me-1"></i>Ambassadeur Écologique
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Sécurité -->
                    <div class="card eco-card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-shield-alt me-2"></i>Sécurité
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong>Email vérifié</strong>
                                        <br>
                                        <small class="text-muted">Votre email est confirmé</small>
                                    </div>
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong>2FA</strong>
                                        <br>
                                        <small class="text-muted">
                                            @if($user->two_factor_enabled)
                                            Activé
                                            @else
                                            Non activé
                                            @endif
                                        </small>
                                    </div>
                                    @if($user->two_factor_enabled)
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    @else
                                    <a href="{{ route('2fa.setup') }}" class="btn btn-sm btn-eco">
                                        Activer
                                    </a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if($user->profile && $user->profile->interests)
                    <div class="card eco-card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-heart me-2"></i>Centres d'intérêt
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($user->profile->interests as $interest)
                            <span class="badge bg-light text-dark me-1 mb-1">{{ $interest }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Détails du profil -->
                <div class="col-md-8">
                    <div class="card eco-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informations personnelles
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Prénom</label>
                                        <p class="form-control-plaintext">
                                            {{ $user->first_name ?? 'Non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nom</label>
                                        <p class="form-control-plaintext">
                                            {{ $user->last_name ?? 'Non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Téléphone</label>
                                        <p class="form-control-plaintext">
                                            {{ $user->phone ?? 'Non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date de naissance</label>
                                        <p class="form-control-plaintext">
                                            {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') : 'Non renseignée' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ville</label>
                                        <p class="form-control-plaintext">
                                            {{ $user->city ?? 'Non renseignée' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pays</label>
                                        <p class="form-control-plaintext">
                                            {{ $user->country ?? 'Non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($user->profile && $user->profile->bio)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Biographie</label>
                                <p class="form-control-plaintext">{{ $user->profile->bio }}</p>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label fw-bold">Préférences de notification</label>
                                <p class="form-control-plaintext">
                                    @switch($user->profile->notification_preferences ?? 'email')
                                    @case('email')
                                    <i class="fas fa-envelope me-1"></i>Email uniquement
                                    @break
                                    @case('sms')
                                    <i class="fas fa-sms me-1"></i>SMS uniquement
                                    @break
                                    @case('both')
                                    <i class="fas fa-envelope me-1"></i><i class="fas fa-sms me-1"></i>Email et SMS
                                    @break
                                    @case('none')
                                    <i class="fas fa-bell-slash me-1"></i>Aucune notification
                                    @break
                                    @endswitch
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="card eco-card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Mes statistiques
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h4 class="text-eco">0</h4>
                                        <small class="text-muted">Événements créés</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h4 class="text-eco">0</h4>
                                        <small class="text-muted">Participations</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h4 class="text-eco">{{ $user->created_at->diffInDays() }}</h4>
                                    <small class="text-muted">Jours sur la plateforme</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->role === 'sponsor' && $user->sponsor && !$user->sponsor->isDeletionRequested())
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

    .eco-card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }

    .eco-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endpush
@endsection