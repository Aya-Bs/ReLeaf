@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')
<div>
    <h2 class="auth-title h3">Mot de passe oublié</h2>
    <p class="auth-subtitle">Saisissez votre adresse email pour recevoir un code de vérification</p>

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

    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
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
                   autocomplete="email"
                   placeholder="votre@email.com">
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="btn btn-eco">
                <i class="fas fa-paper-plane me-2"></i>Envoyer le code de vérification
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

    <!-- Information -->
    <div class="mt-4 p-3 bg-light rounded">
        <h6 class="mb-2"><i class="fas fa-info-circle text-info me-1"></i>Comment ça marche ?</h6>
        <ol class="list-unstyled small text-muted mb-0">
            <li class="mb-1"><strong>1.</strong> Saisissez votre adresse email</li>
            <li class="mb-1"><strong>2.</strong> Recevez un code de vérification (6 caractères)</li>
            <li class="mb-0"><strong>3.</strong> Utilisez ce code pour définir un nouveau mot de passe</li>
        </ol>
    </div>
</div>

@push('scripts')
<script>
// Form validation
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;

    if (!email) {
        e.preventDefault();
        alert('Veuillez saisir votre adresse email.');
        return;
    }

    if (!email.includes('@') || !email.includes('.')) {
        e.preventDefault();
        alert('Veuillez saisir une adresse email valide.');
        return;
    }

    // Show loading state
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
    submitBtn.disabled = true;
});
</script>
@endpush
@endsection