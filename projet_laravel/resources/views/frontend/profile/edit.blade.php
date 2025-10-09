@extends('layouts.frontend')

@section('title', 'Modifier mon profil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-eco">
                    <i class="fas fa-edit me-2"></i>Modifier mon profil
                </h2>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <form action="{{ route('profile.update.extended') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Avatar et informations de base -->
                    <div class="col-md-4">
                        <div class="card eco-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-camera me-2"></i>Photo de profil
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <img src="{{ $user->avatar_url }}" alt="Avatar"
                                        class="rounded-circle img-thumbnail"
                                        width="120" height="120" id="avatarPreview">
                                </div>

                                <div class="mb-3">
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                        id="avatar" name="avatar" accept="image/*">
                                    @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Formats acceptés: JPG, PNG, GIF (max 2MB)
                                    </small>
                                </div>

                                @if($user->profile && $user->profile->avatar)
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    onclick="deleteAvatar()">
                                    <i class="fas fa-trash me-1"></i>Supprimer l'avatar
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Statut ambassadeur -->
                        @if($user->profile && $user->profile->is_eco_ambassador)
                        <div class="card eco-card mt-3">
                            <div class="card-body text-center">
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-leaf me-1"></i>Ambassadeur Écologique
                                </span>
                                <p class="mt-2 mb-0 small text-muted">
                                    Vous contribuez activement à la protection de l'environnement !
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Formulaire principal -->
                    <div class="col-md-8">
                        <div class="card eco-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Informations personnelles
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Nom et prénom -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">Prénom</label>
                                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                                id="first_name" name="first_name"
                                                value="{{ old('first_name', $user->profile->first_name) }}">
                                            @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Nom</label>
                                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                id="last_name" name="last_name"
                                                value="{{ old('last_name', $user->profile->last_name) }}">
                                            @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                id="phone" name="phone"
                                                value="{{ old('phone', $user->profile->phone) }}"
                                                placeholder="+33 1 23 45 67 89">
                                            @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="birth_date" class="form-label">Date de naissance</label>
                                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                                id="birth_date" name="birth_date"
                                                value="{{ old('birth_date', $user->profile->birth_date?->format('Y-m-d')) }}">
                                            @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Localisation -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">Ville</label>
                                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                                id="city" name="city"
                                                value="{{ old('city', $user->profile->city) }}">
                                            @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Pays</label>
                                            <input type="text" class="form-control @error('country') is-invalid @enderror"
                                                id="country" name="country"
                                                value="{{ old('country', $user->profile->country) }}">
                                            @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Biographie -->
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Biographie</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror"
                                        id="bio" name="bio" rows="4"
                                        placeholder="Parlez-nous de votre passion pour l'écologie...">{{ old('bio', $user->profile->bio) }}</textarea>
                                    @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum 1000 caractères</small>
                                </div>

                                @if($user->role === 'sponsor' && $user->sponsor)
                                <hr>
                                <h5 class="mt-4 mb-3"><i class="fas fa-building me-2"></i>Informations sponsor</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nom entreprise</label>
                                            <input type="text" name="company_name" value="{{ old('company_name', $user->sponsor->company_name) }}" class="form-control @error('company_name') is-invalid @enderror">
                                            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email contact</label>
                                            <input type="email" name="contact_email" value="{{ old('contact_email', $user->sponsor->contact_email) }}" class="form-control @error('contact_email') is-invalid @enderror">
                                            @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone contact</label>
                                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $user->sponsor->contact_phone) }}" class="form-control @error('contact_phone') is-invalid @enderror">
                                            @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Site web</label>
                                            <input type="url" name="website" value="{{ old('website', $user->sponsor->website) }}" class="form-control @error('website') is-invalid @enderror">
                                            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Adresse</label>
                                            <input type="text" name="address" value="{{ old('address', $user->sponsor->address) }}" class="form-control @error('address') is-invalid @enderror">
                                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Motivation</label>
                                            <textarea name="motivation" rows="2" class="form-control @error('motivation') is-invalid @enderror">{{ old('motivation', $user->sponsor->motivation) }}</textarea>
                                            @error('motivation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Infos supplémentaires</label>
                                    <textarea name="additional_info" rows="2" class="form-control @error('additional_info') is-invalid @enderror">{{ old('additional_info', $user->sponsor->additional_info) }}</textarea>
                                    @error('additional_info')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="alert alert-warning small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    La suppression du compte sponsor nécessite une validation admin. Vous pouvez en faire la demande ici.
                                </div>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSponsorModal" @if($user->sponsor->isDeletionRequested()) disabled @endif>
                                    <i class="fas fa-user-slash me-1"></i>
                                    @if($user->sponsor->isDeletionRequested()) Demande déjà envoyée @else Demander suppression sponsor @endif
                                </button>
                                @endif

                                <!-- Centres d'intérêt -->
                                <div class="mb-3">
                                    <label for="interests" class="form-label">Centres d'intérêt écologiques</label>
                                    <input type="text" class="form-control @error('interests') is-invalid @enderror"
                                        id="interests" name="interests_input"
                                        value="{{ old('interests_input', is_array($user->profile->interests) ? implode(', ', $user->profile->interests) : '') }}"
                                        placeholder="Recyclage, énergies renouvelables, jardinage bio...">
                                    @error('interests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Séparez vos intérêts par des virgules</small>
                                </div>

                                <!-- Préférences de notification -->
                                <div class="mb-3">
                                    <label for="notification_preferences" class="form-label">Préférences de notification</label>
                                    <select class="form-select @error('notification_preferences') is-invalid @enderror"
                                        id="notification_preferences" name="notification_preferences">
                                        <option value="email" {{ old('notification_preferences', $user->profile->notification_preferences) == 'email' ? 'selected' : '' }}>
                                            Email uniquement
                                        </option>
                                        <option value="sms" {{ old('notification_preferences', $user->profile->notification_preferences) == 'sms' ? 'selected' : '' }}>
                                            SMS uniquement
                                        </option>
                                        <option value="both" {{ old('notification_preferences', $user->profile->notification_preferences) == 'both' ? 'selected' : '' }}>
                                            Email et SMS
                                        </option>
                                        <option value="none" {{ old('notification_preferences', $user->profile->notification_preferences) == 'none' ? 'selected' : '' }}>
                                            Aucune notification
                                        </option>
                                    </select>
                                    @error('notification_preferences')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Boutons d'action -->
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-eco">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                        Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @if($user->role === 'sponsor' && $user->sponsor)
            <!-- Modal suppression sponsor -->
            <div class="modal fade" id="deleteSponsorModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('sponsor.self.requestDeletion') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Demande de suppression sponsor</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @if($user->sponsor->isDeletionRequested())
                                <div class="alert alert-warning">Une demande est déjà en attente.</div>
                                @else
                                <div class="mb-3">
                                    <label class="form-label">Raison</label>
                                    <textarea name="reason" rows="4" class="form-control" required placeholder="Expliquez la raison de la suppression..."></textarea>
                                </div>
                                <p class="small text-muted mb-0">L'administration confirmera avant suppression définitive.</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                @if(!$user->sponsor->isDeletionRequested())
                                <button class="btn btn-danger"><i class="fas fa-paper-plane me-1"></i>Envoyer</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Prévisualisation de l'avatar
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Conversion des intérêts en array
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        const interestsInput = document.getElementById('interests').value;
        if (interestsInput) {
            const interests = interestsInput.split(',').map(item => item.trim()).filter(item => item);

            // Créer des inputs cachés pour chaque intérêt
            interests.forEach((interest, index) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `interests[${index}]`;
                hiddenInput.value = interest;
                this.appendChild(hiddenInput);
            });
        }
    });

    // Suppression de l'avatar
    function deleteAvatar() {
        if (confirm('Êtes-vous sûr de vouloir supprimer votre avatar ?')) {
            fetch('{{ route("profile.avatar.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }
    }
</script>
@endpush
@endsection