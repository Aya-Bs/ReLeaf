@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Vérification à deux facteurs</h4>
        </div>

        <div class="card-body">
            <p class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Veuillez entrer le code à 6 chiffres généré par votre application d'authentification.
            </p>

            <form method="POST" action="{{ route('2fa.verify') }}">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label">Code de vérification</label>
                    <input type="text" 
                           class="form-control @error('code') is-invalid @enderror" 
                           id="code" 
                           name="code"
                           required
                           autocomplete="off"
                           pattern="[0-9]{6}"
                           maxlength="6"
                           autofocus>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-check-circle me-2"></i>Vérifier
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#recoveryCodeModal">
                    <i class="fas fa-key me-1"></i>Utiliser un code de récupération
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les codes de récupération -->
<div class="modal fade" id="recoveryCodeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Utiliser un code de récupération</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('2fa.recovery') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="recovery_code" class="form-label">Code de récupération</label>
                        <input type="text" 
                               class="form-control" 
                               id="recovery_code" 
                               name="recovery_code"
                               required
                               placeholder="XXXX-XXXX-XXXX-XXXX">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
