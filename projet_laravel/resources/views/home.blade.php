@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Message de bienvenue -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="display-4 text-eco">Bienvenue sur EcoEvents</h1>
            <p class="lead text-muted">
                Découvrez et participez à des événements écologiques qui font la différence.
            </p>
        </div>
    </div>

    <!-- Cartes d'actions rapides -->
    <div class="row g-4">
        <!-- Événements à venir -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-alt text-eco me-2"></i>
                        Événements à venir
                    </h5>
                    <p class="card-text">
                        Découvrez les prochains événements écologiques près de chez vous.
                    </p>
                    <a href="#" class="btn btn-eco">
                        <i class="fas fa-search me-2"></i>Explorer les événements
                    </a>
                </div>
            </div>
        </div>

        <!-- Créer un événement -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-plus-circle text-eco me-2"></i>
                        Organiser un événement
                    </h5>
                    <p class="card-text">
                        Créez votre propre événement et contribuez à un avenir plus vert.
                    </p>
                    <a href="#" class="btn btn-eco">
                        <i class="fas fa-plus me-2"></i>Créer un événement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="text-center">
                <h3 class="text-eco">0</h3>
                <p class="text-muted">Événements créés</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <h3 class="text-eco">0</h3>
                <p class="text-muted">Participants</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <h3 class="text-eco">0</h3>
                <p class="text-muted">Impact écologique</p>
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
