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
