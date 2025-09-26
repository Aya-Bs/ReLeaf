@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt me-2 text-eco"></i>
                        Configuration de l'authentification à deux facteurs
                    </h4>
                </div>

                <div class="card-body">
                    @if($enabled)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            L'authentification à deux facteurs est activée sur votre compte.
                        </div>

                        <form method="POST" action="{{ route('2fa.disable') }}" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times-circle me-2"></i>Désactiver l'authentification à deux facteurs
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            L'authentification à deux facteurs ajoute une couche de sécurité supplémentaire à votre compte.
                            Une fois activée, vous devrez saisir un code à 6 chiffres généré par votre application
                            d'authentification en plus de votre mot de passe.
                        </div>

                        <div class="setup-steps mt-4">
                            <!-- Étape 1 -->
                            <div class="mb-4">
                                <h5>
                                    <span class="badge bg-eco rounded-circle me-2">1</span>
                                    Scannez ce QR code avec votre application d'authentification
                                </h5>
                                <div class="qr-code-container bg-white p-4 text-center mt-3">
                                    {!! $qrCodeSvg !!}
                                </div>
                            </div>

                            <!-- Étape 2 -->
                            <div class="mb-4">
                                <h5>
                                    <span class="badge bg-eco rounded-circle me-2">2</span>
                                    Ou entrez cette clé manuellement dans votre application
                                </h5>
                                <div class="secret-key bg-light p-3 font-monospace mt-3">
                                    {{ chunk_split($secret, 4, ' ') }}
                                </div>
                            </div>

                            <!-- Étape 3 -->
                            <form method="POST" action="{{ route('2fa.enable') }}" class="mt-4">
                                @csrf
                                <h5>
                                    <span class="badge bg-eco rounded-circle me-2">3</span>
                                    Entrez le code généré par votre application
                                </h5>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Code de vérification</label>
                                            <input type="text" 
                                                   class="form-control @error('code') is-invalid @enderror" 
                                                   id="code" 
                                                   name="code"
                                                   required
                                                   autocomplete="off"
                                                   pattern="[0-9]{6}"
                                                   maxlength="6">
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-eco">
                                    <i class="fas fa-shield-alt me-2"></i>Activer l'authentification à deux facteurs
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Conseils de sécurité -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>
                        Conseils de sécurité
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Utilisez une application d'authentification fiable comme Google Authenticator
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Conservez vos codes de récupération dans un endroit sûr
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Ne partagez jamais vos codes avec qui que ce soit
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-eco {
    background-color: #2d5a27;
}
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
.badge.rounded-circle {
    width: 24px;
    height: 24px;
    padding: 0;
    line-height: 24px;
    font-size: 12px;
}
.qr-code-container {
    display: inline-block;
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
}
.secret-key {
    letter-spacing: 2px;
    border-radius: 0.5rem;
}
</style>
@endpush
@endsection