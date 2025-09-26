@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tableau de bord</h4>
                </div>
                <div class="card-body">
                    <h5>Bienvenue, {{ $user->first_name }} !</h5>
                    <p>Votre compte a été vérifié avec succès.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Statistiques -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes événements</h5>
                    <p class="card-text display-4">0</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes participations</h5>
                    <p class="card-text display-4">0</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Points écologiques</h5>
                    <p class="card-text display-4">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-eco">
                            <i class="fas fa-calendar-plus me-2"></i>Créer un événement
                        </a>
                        <a href="#" class="btn btn-outline-eco">
                            <i class="fas fa-search me-2"></i>Découvrir les événements
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sécurité du compte</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Email vérifié
                            <span class="badge bg-success rounded-pill">
                                <i class="fas fa-check"></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Authentification à deux facteurs
                            @if($user->two_factor_enabled)
                                <span class="badge bg-success rounded-pill">
                                    <i class="fas fa-check"></i>
                                </span>
                            @else
                                <a href="{{ route('2fa.setup') }}" class="btn btn-sm btn-outline-primary">
                                    Activer
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection