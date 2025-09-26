@extends('layouts.auth')

@section('title', 'Inscription')

@section('content')
<div>
    <h2 class="auth-title h3">Rejoignez EcoEvents</h2>
    <p class="auth-subtitle">Créez votre compte et participez à la révolution écologique</p>

    <!-- Information importante -->
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Note :</strong> Après avoir créé votre compte, vous serez redirigé vers la page de connexion
        pour vous authentifier avant d'accéder à votre espace personnel.
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- First Name & Last Name -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="first_name" class="form-label">
                        <i class="fas fa-user me-2"></i>Prénom <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('first_name') is-invalid @enderror"
                           id="first_name"
                           name="first_name"
                           value="{{ old('first_name') }}"
                           required
                           autofocus
                           autocomplete="given-name"
                           placeholder="Votre prénom">
                    @error('first_name')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="last_name" class="form-label">
                        <i class="fas fa-user me-2"></i>Nom <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('last_name') is-invalid @enderror"
                           id="last_name"
                           name="last_name"
                           value="{{ old('last_name') }}"
                           required
                           autocomplete="family-name"
                           placeholder="Votre nom">
                    @error('last_name')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Name (auto-generated) -->
        <input type="hidden" id="name" name="name" value="{{ old('name') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Adresse email <span class="text-danger">*</span>
            </label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="username"
                   placeholder="votre@email.com">
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mb-3">
            <label for="phone" class="form-label">
                <i class="fas fa-phone me-2"></i>Téléphone
            </label>
            <input type="tel"
                   class="form-control @error('phone') is-invalid @enderror"
                   id="phone"
                   name="phone"
                   value="{{ old('phone') }}"
                   autocomplete="tel"
                   placeholder="+33 1 23 45 67 89">
            @error('phone')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            <small class="form-text text-muted">Optionnel - Format: +33 1 23 45 67 89</small>
        </div>

        <!-- Date de naissance -->
        <div class="mb-3">
            <label for="birth_date" class="form-label">
                <i class="fas fa-calendar me-2"></i>Date de naissance
            </label>
            <input type="date"
                   class="form-control @error('birth_date') is-invalid @enderror"
                   id="birth_date"
                   name="birth_date"
                   value="{{ old('birth_date') }}"
                   autocomplete="bday">
            @error('birth_date')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Ville et Pays -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="city" class="form-label">
                        <i class="fas fa-city me-2"></i>Ville
                    </label>
                    <input type="text"
                           class="form-control @error('city') is-invalid @enderror"
                           id="city"
                           name="city"
                           value="{{ old('city') }}"
                           autocomplete="address-level2"
                           placeholder="Votre ville">
                    @error('city')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="country" class="form-label">
                        <i class="fas fa-globe me-2"></i>Pays
                    </label>
                    <select class="form-select @error('country') is-invalid @enderror"
                            id="country"
                            name="country"
                            autocomplete="country">
                        <option value="">Sélectionnez un pays</option>
                        <option value="TN" {{ old('country') == 'TN' ? 'selected' : '' }}>Tunisie</option>
                        <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                        <option value="DZ" {{ old('country') == 'DZ' ? 'selected' : '' }}>Algérie</option>
                        <option value="MA" {{ old('country') == 'MA' ? 'selected' : '' }}>Maroc</option>
                        <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgique</option>
                        <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Suisse</option>
                        <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                        <option value="LB" {{ old('country') == 'LB' ? 'selected' : '' }}>Liban</option>
                        <option value="EG" {{ old('country') == 'EG' ? 'selected' : '' }}>Égypte</option>
                        <option value="SN" {{ old('country') == 'SN' ? 'selected' : '' }}>Sénégal</option>
                        <option value="CI" {{ old('country') == 'CI' ? 'selected' : '' }}>Côte d'Ivoire</option>
                    </select>
                    @error('country')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Mot de passe <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimum 8 caractères">
                <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="password-icon"></i>
                </span>
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            <small class="form-text text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Minimum 8 caractères avec lettres et chiffres
            </small>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-2"></i>Confirmer le mot de passe <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Répétez votre mot de passe">
                <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation')">
                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                </span>
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a>
                    et la <a href="#" class="auth-link">politique de confidentialité</a>
                    <span class="text-danger">*</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="btn btn-eco">
                <i class="fas fa-user-plus me-2"></i>Créer mon compte
            </button>
        </div>

        <!-- Links -->
        <div class="text-center">
            <p class="mb-0">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="auth-link">
                    <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                </a>
            </p>
        </div>
    </form>
</div>

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Auto-generate name field from first_name and last_name
function updateNameField() {
    const firstName = document.getElementById('first_name').value;
    const lastName = document.getElementById('last_name').value;
    const nameField = document.getElementById('name');

    if (firstName && lastName) {
        nameField.value = firstName + ' ' + lastName;
    } else if (firstName) {
        nameField.value = firstName;
    } else if (lastName) {
        nameField.value = lastName;
    }
}

document.getElementById('first_name').addEventListener('input', updateNameField);
document.getElementById('last_name').addEventListener('input', updateNameField);

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const firstName = document.getElementById('first_name').value;
    const lastName = document.getElementById('last_name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const terms = document.getElementById('terms').checked;

    if (!firstName || !lastName || !email || !password || !passwordConfirmation) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }

    if (password !== passwordConfirmation) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return;
    }

    if (!terms) {
        e.preventDefault();
        alert('Vous devez accepter les conditions d\'utilisation.');
        return;
    }

    // Update name field before submission
    updateNameField();
});

// Initialize name field on page load
document.addEventListener('DOMContentLoaded', function() {
    updateNameField();
});
</script>
@endpush
@endsection
