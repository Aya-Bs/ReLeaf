@extends('layouts.frontend')

@section('title', 'Bilan Carbone - ' . $campaign->name)

@section('content')

<!-- Hero Section -->
<section class="hero-section py-4" style="background: linear-gradient(135deg, var(--eco-green, #2d5a27) 0%, var(--eco-light-green, #4a7c59) 100%); min-height: 30vh; display: flex; align-items: center; border-bottom: 3px solid #e9ecef;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 text-center text-white">
                <h1 class="display-6 fw-bold mb-2">
                    <i class="fas fa-leaf me-2"></i>Bilan Carbone
                </h1>
                <p class="lead mb-0" style="opacity: 0.95;">{{ $campaign->name }}</p>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="breadcrumb-nav">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('campaigns.index') }}" class="text-eco">
                                    <i class="fas fa-leaf me-1"></i>Campagnes
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('campaigns.show', $campaign) }}" class="text-eco">
                                    {{ Str::limit($campaign->name, 30) }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Bilan carbone</li>
                        </ol>
                    </nav>
                    <small class="text-muted d-block mt-1">Organisée par: {{ $campaign->organizer->name ?? 'Non spécifié' }}</small>
                </div>
                <div class="text-end">
                    <div class="badge bg-{{ $usedApi === 'carbon_interface' ? 'info' : 'success' }} mb-2">
                        {{ $usedApi === 'carbon_interface' ? 'CARBON INTERFACE' : 'ADEME (FALLBACK)' }}
                    </div>
                    <small class="text-muted d-block">Calculé le: {{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>

            <!-- Alertes système -->
            @if($usedApi === 'ademe')
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Mode Fallback Activé</strong> - Utilisation des données ADEME (Agence de la Transition Écologique).
                Les calculs sont fiables mais moins précis qu'avec Carbon Interface.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            

            <!-- Bilan Total -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-warning h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-smog fa-3x text-warning mb-3"></i>
                            <h2 class="text-warning">{{ number_format($totalFootprint, 2) }} kg</h2>
                            <p class="text-muted mb-0">CO2e Total</p>
                            <small class="text-muted">
                                Équivalent {{ number_format($totalFootprint * 1000, 0) }} km en voiture
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-info h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-boxes fa-3x text-info mb-3"></i>
                            <h2 class="text-info">{{ count($carbonData) }}</h2>
                            <p class="text-muted mb-0">Ressources analysées</p>
                            <small class="text-muted">
                                {{ $campaign->resources->count() }} ressources au total
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-{{ $usedApi === 'carbon_interface' ? 'info' : 'success' }} h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calculator fa-3x text-{{ $usedApi === 'carbon_interface' ? 'info' : 'success' }} mb-3"></i>
                            <h2 class="text-{{ $usedApi === 'carbon_interface' ? 'info' : 'success' }}">
                                {{ $usedApi === 'carbon_interface' ? 'CARBON INTERFACE' : 'ADEME' }}
                            </h2>
                            <p class="text-muted mb-0">Méthode de calcul</p>
                            <small class="text-muted">
                                @if($usedApi === 'carbon_interface')
                                Données temps réel
                                @else
                                Données de référence
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par type -->
            @php
                $typeBreakdown = [];
                foreach ($carbonData as $data) {
                    $type = $data['resource']->resource_type;
                    if (!isset($typeBreakdown[$type])) {
                        $typeBreakdown[$type] = 0;
                    }
                    $typeBreakdown[$type] += $data['footprint'];
                }
                arsort($typeBreakdown);
            @endphp

            @if(count($typeBreakdown) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">📊 Répartition par Type de Ressource</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($typeBreakdown as $type => $footprint)
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                        <div>
                                            <span class="fw-bold text-capitalize">
                                                @switch($type)
                                                    @case('money') 💰 Argent @break
                                                    @case('food') 🍕 Nourriture @break
                                                    @case('clothing') 👕 Vêtements @break
                                                    @case('medical') 🏥 Médical @break
                                                    @case('equipment') 🛠️ Équipement @break
                                                    @case('human') 👥 Humain @break
                                                    @default 🔧 Autre
                                                @endswitch
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ number_format(($footprint / $totalFootprint) * 100, 1) }}% du total
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-warning">{{ number_format($footprint, 2) }} kg</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Détail par Ressource -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📋 Détail par Ressource</h5>
                    <span class="badge bg-primary">{{ count($carbonData) }} ressources</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ressource</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Empreinte (kg CO2e)</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carbonData as $data)
                                @php
                                    $resource = $data['resource'];
                                    $isLocal = app('App\Services\CarbonCalculatorService')->isLocalProvider($resource->provider);
                                    $isEco = app('App\Services\CarbonCalculatorService')->isEcoFriendly($resource);
                                    $isReused = app('App\Services\CarbonCalculatorService')->isReusedMaterial($resource);
                                @endphp
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $resource->name }}</strong>
                                            @if($resource->provider)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-truck me-1"></i>
                                                {{ $resource->provider }}
                                                @if($isLocal)
                                                <span class="badge bg-success ms-1">Local</span>
                                                @endif
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-capitalize">
                                            @switch($resource->resource_type)
                                                @case('money') 💰 @break
                                                @case('food') 🍕 @break
                                                @case('clothing') 👕 @break
                                                @case('medical') 🏥 @break
                                                @case('equipment') 🛠️ @break
                                                @case('human') 👥 @break
                                                @default 🔧
                                            @endswitch
                                            {{ $resource->resource_type }}
                                        </span>
                                        <div class="mt-1">
                                            @if($isEco)
                                            <span class="badge bg-success" title="Écologique">🌱</span>
                                            @endif
                                            @if($isReused)
                                            <span class="badge bg-info" title="Réutilisé">♻️</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">
                                            {{ number_format($resource->quantity_needed) }} 
                                        </span>
                                        <span class="text-muted">{{ $resource->unit }}</span>
                                        @if($resource->quantity_pledged > 0)
                                        <br>
                                        <small class="text-success">
                                            {{ number_format($resource->quantity_pledged) }} promis
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-warning fs-5">
                                            {{ number_format($data['footprint'], 2) }} kg
                                        </span>
                                        @if($totalFootprint > 0)
                                        <br>
                                        <small class="text-muted">
                                            {{ number_format(($data['footprint'] / $totalFootprint) * 100, 1) }}%
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <div>Calcul: {{ strtoupper($usedApi) }}</div>
                                            <div>{{ $data['details']['calculated_at'] }}</div>
                                            @if($resource->priority === 'urgent')
                                            <span class="badge bg-danger mt-1">Urgent</span>
                                            @elseif($resource->priority === 'high')
                                            <span class="badge bg-warning mt-1">Haute</span>
                                            @endif
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-active">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total empreinte carbone:</td>
                                    <td colspan="2">
                                        <strong class="text-warning fs-4">
                                            {{ number_format($totalFootprint, 2) }} kg CO2e
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Équivalences pour comprendre l'impact -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">🌳 Équivalences CO2</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-car fa-2x text-secondary mb-2"></i>
                                <h6>{{ number_format($totalFootprint * 1000 / 210, 0) }} km</h6>
                                <small class="text-muted">en voiture</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-tree fa-2x text-success mb-2"></i>
                                <h6>{{ number_format($totalFootprint / 25, 1) }} arbres</h6>
                                <small class="text-muted">pour absorber en 1 an</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-bolt fa-2x text-warning mb-2"></i>
                                <h6>{{ number_format($totalFootprint * 1000 / 500, 0) }} kWh</h6>
                                <small class="text-muted">d'électricité</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-home fa-2x text-info mb-2"></i>
                                <h6>{{ number_format($totalFootprint / 8000, 2) }} ans</h6>
                                <small class="text-muted">d'émissions d'un foyer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="btn-group" role="group">
                        <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la campagne
                        </a>
                        <button onclick="window.location.reload()" class="btn btn-outline-eco">
                            <i class="fas fa-sync-alt me-2"></i>Recalculer
                        </button>
                        <a href="{{ route('carbon.apis.test') }}" class="btn btn-outline-eco" target="_blank">
                            <i class="fas fa-vial me-2"></i>Tester les APIs
                        </a>
                        @if($usedApi === 'ademe' && config('services.carbon_interface.api_key'))
                        <button class="btn btn-outline-warning" onclick="showApiHelp()">
                            <i class="fas fa-question-circle me-2"></i>Aide API
                        </button>
                        @endif
                    </div>
                    
                    @if($usedApi === 'ademe')
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Pour utiliser Carbon Interface, vérifiez votre connexion internet et votre clé API.
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations techniques -->
            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-cog me-1"></i>
                    Données calculées le {{ now()->format('d/m/Y à H:i') }} | 
                    Cache: 24h | 
                    Version: 2.0
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aide API -->
<div class="modal fade" id="apiHelpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🛠️ Aide Configuration API</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Carbon Interface n'est pas accessible</strong></p>
                <ul>
                    <li>Vérifiez votre connexion Internet</li>
                    <li>Vérifiez que la clé API est correcte dans le fichier .env</li>
                    <li>Assurez-vous que Carbon Interface est opérationnel</li>
                </ul>
                <p class="mb-0">
                    <small class="text-muted">
                        En attendant, le système utilise les données fiables de l'ADEME.
                    </small>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="{{ route('carbon.apis.test') }}" class="btn btn-primary" target="_blank">Tester à nouveau</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function showApiHelp() {
    var modal = new bootstrap.Modal(document.getElementById('apiHelpModal'));
    modal.show();
}

// Auto-refresh toutes les 5 minutes pour les données temps réel
@if($usedApi === 'carbon_interface')
setTimeout(function() {
    window.location.reload();
}, 300000); // 5 minutes
@endif

// Tooltips pour les badges écologiques
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection

@section('styles')
<style>
/* Hero */
.hero-section {
    background: linear-gradient(135deg, var(--eco-green, #2d5a27) 0%, var(--eco-light-green, #4a7c59) 100%) !important;
    border-bottom: 3px solid #e9ecef;
}
.hero-section .display-6,
.hero-section .lead {
    text-shadow: 0 1px 2px rgba(0,0,0,0.25);
}

/* Breadcrumb */
.breadcrumb-nav .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}
.breadcrumb-nav .breadcrumb-item a {
    color: var(--eco-green, #2d5a27);
    text-decoration: none;
    font-weight: 600;
}
.breadcrumb-nav .breadcrumb-item a:hover {
    color: var(--eco-light-green, #4a7c59);
}
.breadcrumb-nav .breadcrumb-item.active {
    color: #6c757d;
    font-weight: 500;
}

/* Buttons consistent with eco theme */
.btn-eco {
    background: linear-gradient(135deg, var(--eco-green, #2d5a27) 0%, var(--eco-light-green, #4a7c59) 100%);
    border: none;
    color: #fff;
    font-weight: 600;
    border-radius: 0.75rem;
}
.btn-eco:hover {
    filter: brightness(1.05);
    color: #fff;
}
.btn-outline-eco {
    border: 2px solid var(--eco-green, #2d5a27);
    color: var(--eco-green, #2d5a27);
    background: transparent;
    font-weight: 600;
    border-radius: 0.75rem;
}
.btn-outline-eco:hover {
    background: var(--eco-green, #2d5a27);
    border-color: var(--eco-green, #2d5a27);
    color: #fff;
}

/* Cards */
.card {
    border-radius: 1rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 0.5rem 1.25rem rgba(0,0,0,0.06);
}
.card-header {
    background: #fff;
    border-bottom: 1px solid #e9ecef;
}

/* Table */
.table thead th {
    border-top: none;
    font-weight: 700;
    color: var(--eco-green, #2d5a27);
    background-color: #f7fbf7;
}
.table-hover tbody tr:hover {
    background-color: #f9fcf9 !important;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    border-radius: 0.6rem;
}

/* Icon styling */
.fa-3x { opacity: 0.9; }

/* Info chips */
.border.rounded.p-3 {
    background: #fff;
}

/* Utility */
.text-eco { color: var(--eco-green, #2d5a27) !important; }
.text-warning { color: #fd7e14 !important; }

/* Responsive tweaks */
@media (max-width: 768px) {
    .hero-section { min-height: 24vh; }
}
</style>
@endsection