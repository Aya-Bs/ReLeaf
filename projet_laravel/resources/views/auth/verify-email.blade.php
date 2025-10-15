@extends('layouts.auth')

@section('content')
<div>
    <!-- Logo et titre -->
    <div class="text-center mb-4">
        <h2 class="auth-title h3">EcoEvents</h2>
        <p class="auth-subtitle">
            Ensemble pour un avenir plus vert.<br>
            Rejoignez la communauté écologique qui fait la différence.
        </p>
    </div>

    <!-- Message de statut -->
    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            Un nouveau lien de vérification a été envoyé à votre adresse email.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Carte principale -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="verification-icon mb-3">
                    <i class="fas fa-envelope-open-text text-eco"></i>
                    <span class="verification-badge">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                <h3 class="h4 mb-2">Vérifiez votre adresse email</h3>
                <p class="text-muted mb-4">
                    Merci de vous être inscrit ! Veuillez vérifier votre email en cliquant sur le lien 
                    que nous venons de vous envoyer.
                </p>
            </div>

            <!-- Actions -->
            <div class="d-grid gap-3 mb-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-eco w-100">
                        <i class="fas fa-paper-plane me-2"></i>Renvoyer l'email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                    </button>
                </form>
            </div>

            <!-- Note -->
            <div class="text-center text-muted">
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    Si vous ne trouvez pas l'email, vérifiez votre dossier spam
                </small>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.verification-icon {
    position: relative;
    display: inline-block;
    width: 80px;
    height: 80px;
    line-height: 80px;
    background-color: rgba(45, 90, 39, 0.1);
    border-radius: 50%;
}

.verification-icon i {
    font-size: 2rem;
    color: #2d5a27;
}

.verification-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 25px;
    height: 25px;
    line-height: 25px;
    background-color: #2d5a27;
    color: white;
    border-radius: 50%;
    font-size: 0.8rem;
}

.btn-eco {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
    transition: all 0.3s ease;
}

.btn-eco:hover {
    background-color: #234420;
    border-color: #234420;
    color: white;
    transform: translateY(-1px);
}

.text-eco {
    color: #2d5a27;
}

.auth-title {
    color: #2d5a27;
    font-weight: 600;
}

.auth-subtitle {
    color: #666;
    font-size: 0.95rem;
}
</style>
@endpush
@endsection