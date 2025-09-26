@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div>
    <!-- Session Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h2 class="auth-title h3">Bon retour !</h2>
    <p class="auth-subtitle">Connectez-vous à votre compte EcoEvents</p>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Adresse email
            </label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="votre@email.com">
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Mot de passe
            </label>
            <div class="input-group">
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="Votre mot de passe">
                <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="password-icon"></i>
                </span>
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">
                    Se souvenir de moi
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="btn btn-eco">
                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
            </button>
        </div>

        <!-- Divider -->
        <div class="mb-4">
            <div class="position-relative">
                <hr class="my-3">
                <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted">ou</span>
            </div>
        </div>

        <!-- Google Login Button -->
        <div class="mb-4">
            <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100">
                <i class="fab fa-google me-2"></i>Continuer avec Google
            </a>
        </div>

        <!-- Links -->
        <div class="text-center">
            @if (Route::has('password.request'))
                <p class="mb-2">
                    <a href="{{ route('password.request') }}" class="auth-link">
                        <i class="fas fa-key me-1"></i>Mot de passe oublié ?
                    </a>
                </p>
            @endif

            <p class="mb-0">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="auth-link">
                    <i class="fas fa-user-plus me-1"></i>Créer un compte
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

// Form validation
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (!email || !password) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
    }
});
</script>
@endpush
@endsection
