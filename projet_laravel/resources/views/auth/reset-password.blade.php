@extends('layouts.auth')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div>
    <h2 class="auth-title h3">Réinitialiser le mot de passe</h2>
    <p class="auth-subtitle">Saisissez le code de vérification et votre nouveau mot de passe</p>

    <!-- Session Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
        @csrf

        <!-- Email (hidden) -->
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Verification Code -->
        <div class="mb-3">
            <label for="code" class="form-label">
                <i class="fas fa-key me-2"></i>Code de vérification (6 caractères)
            </label>
            <input type="text"
                   class="form-control @error('token') is-invalid @enderror"
                   id="code"
                   name="code"
                   required
                   maxlength="6"
                   pattern="[A-Za-z0-9]{6}"
                   autocomplete="off"
                   placeholder="ABC123"
                   style="font-family: monospace; font-size: 1.2em; text-align: center; letter-spacing: 0.1em;">
            @error('token')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            <div class="form-text">
                <i class="fas fa-info-circle me-1"></i>Code reçu par email (valide 60 minutes)
            </div>
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Nouveau mot de passe
            </label>
            <div class="input-group">
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       required
                       minlength="8"
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
            <div class="form-text">
                <div id="password-strength" class="small">
                    <i class="fas fa-thermometer-empty text-muted me-1"></i>
                    Force du mot de passe: <span class="text-muted">Non évaluée</span>
                </div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-2"></i>Confirmer le mot de passe
            </label>
            <div class="input-group">
                <input type="password"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       id="password_confirmation"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Répétez le mot de passe">
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

        <!-- Password Requirements -->
        <div class="mb-4">
            <div class="password-requirements">
                <h6 class="small mb-2"><i class="fas fa-check-circle me-1"></i>Le mot de passe doit contenir :</h6>
                <div class="row small text-muted">
                    <div class="col-md-6">
                        <span id="req-length" class="requirement">✓ Au moins 8 caractères</span><br>
                        <span id="req-uppercase" class="requirement">✓ Une lettre majuscule</span><br>
                        <span id="req-lowercase" class="requirement">✓ Une lettre minuscule</span>
                    </div>
                    <div class="col-md-6">
                        <span id="req-number" class="requirement">✓ Un chiffre</span><br>
                        <span id="req-special" class="requirement">✓ Un caractère spécial</span><br>
                        <span id="req-match" class="requirement">✓ Les mots de passe correspondent</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="btn btn-eco" id="submitBtn">
                <i class="fas fa-save me-2"></i>Réinitialiser le mot de passe
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <p class="mb-0">
                <a href="{{ route('login') }}" class="auth-link">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
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

// Password strength checker
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthDiv = document.getElementById('password-strength');
    const submitBtn = document.getElementById('submitBtn');

    // Reset requirements
    document.querySelectorAll('.requirement').forEach(req => {
        req.className = 'requirement text-muted';
        req.innerHTML = req.innerHTML.replace('✓', '○');
    });

    if (password.length === 0) {
        strengthDiv.innerHTML = '<i class="fas fa-thermometer-empty text-muted me-1"></i>Force du mot de passe: <span class="text-muted">Non évaluée</span>';
        submitBtn.disabled = true;
        return;
    }

    let strength = 0;
    const checks = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    // Update requirement indicators
    Object.keys(checks).forEach(key => {
        const element = document.getElementById('req-' + key);
        if (checks[key]) {
            element.className = 'requirement text-success';
            element.innerHTML = element.innerHTML.replace('○', '✓');
            strength++;
        }
    });

    // Check password match
    const confirmPassword = document.getElementById('password_confirmation').value;
    const matchElement = document.getElementById('req-match');
    if (confirmPassword && password === confirmPassword) {
        matchElement.className = 'requirement text-success';
        matchElement.innerHTML = matchElement.innerHTML.replace('○', '✓');
    } else if (confirmPassword) {
        matchElement.className = 'requirement text-danger';
        matchElement.innerHTML = matchElement.innerHTML.replace('○', '✗');
    }

    // Update strength indicator
    const strengthLevels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
    const strengthColors = ['danger', 'warning', 'info', 'primary', 'success'];

    strengthDiv.innerHTML = `<i class="fas fa-thermometer-${['empty', 'quarter', 'half', 'three-quarters', 'full'][strength]} text-${strengthColors[strength]} me-1"></i>Force du mot de passe: <span class="text-${strengthColors[strength]}">${strengthLevels[strength]}</span>`;

    // Enable/disable submit button
    const allRequirements = Object.values(checks).every(Boolean) && password === confirmPassword;
    submitBtn.disabled = !allRequirements;
}

// Event listeners
document.getElementById('password').addEventListener('input', checkPasswordStrength);
document.getElementById('password_confirmation').addEventListener('input', checkPasswordStrength);

// Form validation
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    const code = document.getElementById('code').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;

    if (!code || code.length !== 6) {
        e.preventDefault();
        alert('Veuillez saisir le code de vérification complet (6 caractères).');
        return;
    }

    if (!password || password.length < 8) {
        e.preventDefault();
        alert('Le mot de passe doit contenir au moins 8 caractères.');
        return;
    }

    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return;
    }

    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Réinitialisation en cours...';
    submitBtn.disabled = true;
});

// Auto-focus on code field
document.getElementById('code').focus();
</script>
@endpush
@endsection