@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="h4 mb-4"><i class="fas fa-building me-2 text-success"></i>Modifier mon profil sponsor</h1>

    <form method="POST" action="{{ route('sponsor.self.update') }}" class="card shadow-sm p-4">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nom de l'entreprise *</label>
                <input type="text" name="company_name" value="{{ old('company_name', $sponsor->company_name) }}" class="form-control @error('company_name') is-invalid @enderror" required>
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email de contact *</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $sponsor->contact_email) }}" class="form-control @error('contact_email') is-invalid @enderror" required>
                @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Téléphone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $sponsor->contact_phone) }}" class="form-control @error('contact_phone') is-invalid @enderror">
                @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Site web</label>
                <input type="url" name="website" value="{{ old('website', $sponsor->website) }}" class="form-control @error('website') is-invalid @enderror">
                @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $sponsor->address) }}" class="form-control @error('address') is-invalid @enderror">
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Ville</label>
                <input type="text" name="city" value="{{ old('city', $sponsor->city) }}" class="form-control @error('city') is-invalid @enderror">
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Pays</label>
                <input type="text" name="country" value="{{ old('country', $sponsor->country) }}" class="form-control @error('country') is-invalid @enderror">
                @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Motivation</label>
                <textarea name="motivation" rows="2" class="form-control @error('motivation') is-invalid @enderror">{{ old('motivation', $sponsor->motivation) }}</textarea>
                @error('motivation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Informations supplémentaires</label>
                <textarea name="additional_info" rows="3" class="form-control @error('additional_info') is-invalid @enderror">{{ old('additional_info', $sponsor->additional_info) }}</textarea>
                @error('additional_info')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success"><i class="fas fa-save me-1"></i> Enregistrer</button>
            <a href="{{ route('sponsor.dashboard') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>

    <hr class="my-5">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-user-slash me-2"></i>Demande de suppression de compte</span>
        </div>
        <div class="card-body">
            @if($sponsor->isDeletionRequested())
            <div class="alert alert-warning"><i class="fas fa-clock me-2"></i>Une demande de suppression est déjà en attente de traitement par l'administration.</div>
            @else
            <p class="text-muted">Vous pouvez demander la suppression de votre compte sponsor. Cette action devra être validée par un administrateur.</p>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteRequestModal">
                <i class="fas fa-trash me-1"></i> Demander la suppression
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal Deletion Request -->
<div class="modal fade" id="deleteRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('sponsor.self.requestDeletion') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Demande de suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Expliquez brièvement la raison de votre demande :</p>
                    <textarea name="reason" rows="4" class="form-control @error('reason') is-invalid @enderror" required placeholder="Raison..."></textarea>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="alert alert-warning mt-3 small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Votre compte sera désactivé seulement après validation par un administrateur. Vous pouvez annuler en contactant le support tant que ce n'est pas traité.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-danger"><i class="fas fa-paper-plane me-1"></i> Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection