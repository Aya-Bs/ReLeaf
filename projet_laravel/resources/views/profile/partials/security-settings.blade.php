@php
    $user = auth()->user();
@endphp

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-shield-alt me-2 text-eco"></i>
            Sécurité du compte
        </h5>
    </div>

    <div class="card-body">
        <div class="list-group list-group-flush">
            <!-- Email Vérifié -->
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Email vérifié</h6>
                    <p class="text-muted small mb-0">Votre email a été vérifié</p>
                </div>
                <span class="badge bg-success rounded-pill">
                    <i class="fas fa-check"></i>
                </span>
            </div>

            <!-- 2FA -->
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Authentification à deux facteurs</h6>
                    <p class="text-muted small mb-0">
                        @if($user->two_factor_enabled)
                            Activée - Sécurité renforcée
                        @else
                            Non activée - Recommandé pour plus de sécurité
                        @endif
                    </p>
                </div>
                @if($user->two_factor_enabled)
                    <form action="{{ route('2fa.disable') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-times-circle me-1"></i>Désactiver
                        </button>
                    </form>
                @else
                    <a href="{{ route('2fa.setup') }}" class="btn btn-eco btn-sm">
                        <i class="fas fa-lock me-1"></i>Activer
                    </a>
                @endif
            </div>

            <!-- Dernière connexion -->
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Dernière connexion</h6>
                    <p class="text-muted small mb-0">
                        {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}
                    </p>
                </div>
                <i class="fas fa-clock text-muted"></i>
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
