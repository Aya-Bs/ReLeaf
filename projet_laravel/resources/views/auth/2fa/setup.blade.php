@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $enabled ? 'Authentification à deux facteurs activée' : 'Configurer l\'authentification à deux facteurs' }}</h4>
                </div>

                <div class="card-body">
                    @if ($enabled)
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
                        <p class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            L'authentification à deux facteurs ajoute une couche de sécurité supplémentaire à votre compte.
                            Une fois activée, vous devrez saisir un code à 6 chiffres généré par votre application
                            d'authentification en plus de votre mot de passe.
                        </p>

                        <div class="mb-4">
                            <h5>1. Scannez ce QR code avec votre application d'authentification</h5>
                            <div class="qr-code-container bg-white p-3 d-inline-block">
                                {!! $qrCodeSvg !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>2. Ou entrez cette clé manuellement dans votre application</h5>
                            <div class="secret-key bg-light p-3 font-monospace">
                                {{ chunk_split($secret, 4, ' ') }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('2fa.enable') }}" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">
                                    3. Entrez le code généré par votre application
                                </label>
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

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shield-alt me-2"></i>Activer l'authentification à deux facteurs
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
