@extends('layouts.auth')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Logo et titre -->
            <div class="text-center mb-5">
                <h1 class="h3 text-eco mb-3">EcoEvents</h1>
                <p class="text-muted">
                    Ensemble pour un avenir plus vert.<br>
                    Rejoignez la communauté écologique qui fait la différence.
                </p>
            </div>

            <!-- Carte principale -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <!-- Message de statut -->
                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Icône et titre -->
                    <div class="text-center mb-4">
                        <div class="mb-4">
                            <i class="fas fa-envelope-open-text text-eco" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="h4 mb-3">Vérifiez votre adresse email</h2>
                        <p class="text-muted mb-4">
                            Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ?
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-3">
                        <!-- Renvoyer l'email -->
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-eco btn-lg w-100">
                                <i class="fas fa-paper-plane me-2"></i>
                                Renvoyer l'email de vérification
                            </button>
                        </form>

                        <!-- Se déconnecter -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instructions supplémentaires -->
            <div class="mt-4 text-center text-muted">
                <p class="small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Si vous n'avez pas reçu l'email, vérifiez votre dossier spam ou cliquez sur le bouton ci-dessus pour recevoir un nouveau lien.
                </p>
            </div>
        </div>
    </div>
</div>

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
</style>
@endpush
@endsection