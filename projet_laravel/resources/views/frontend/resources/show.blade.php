@extends('layouts.frontend')

@section('title', $resource->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb Navigation -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('resources.index') }}" class="text-eco">
                            <i class="fas fa-boxes me-1"></i>Ressources
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($resource->name, 30) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">{{ $resource->name }}</h1>
                    <p class="text-muted mb-0">Détails de la ressource</p>
                </div>
                <div>
                    <a href="{{ route('resources.edit', $resource) }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit me-2"></i> Modifier
                    </a>
                    <a href="{{ route('resources.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations de la ressource
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Progress Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Progression de la collecte</h6>
                                <span class="badge bg-eco fs-6">{{ $resource->progress_percentage }}%</span>
                            </div>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : ($resource->progress_percentage > 50 ? 'warning' : 'danger') }}"
                                    style="width: {{ $resource->progress_percentage }}%"></div>
                            </div>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold text-eco">{{ $resource->quantity_needed }}</div>
                                        <small class="text-muted">Nécessaire</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <div class="fw-bold text-success">{{ $resource->quantity_pledged }}</div>
                                        <small class="text-muted">Collecté</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fw-bold text-warning">{{ $resource->missing_quantity }}</div>
                                    <small class="text-muted">Manquant</small>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">Unité: {{ $resource->unit }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Resource Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 40%;"><i class="fas fa-tag me-2"></i>Nom</td>
                                    <td><strong>{{ $resource->name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-leaf me-2"></i>Campagne</td>
                                    <td>
                                        <a href="{{ route('campaigns.show', $resource->campaign) }}" class="text-eco text-decoration-none">
                                            {{ $resource->campaign->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-user me-2"></i>Fournisseur</td>
                                    <td>{{ $resource->provider ?? 'Non spécifié' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 40%;"><i class="fas fa-ruler me-2"></i>Unité</td>
                                    <td>{{ $resource->unit }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-chart-line me-2"></i>Statut</td>
                                    <td>
                                        <span class="badge bg-{{ $resource->status == 'received' ? 'success' : ($resource->status == 'pledged' ? 'info' : ($resource->status == 'in_use' ? 'primary' : 'secondary')) }}">
                                            @switch($resource->status)
                                            @case('needed') ⏳ @break
                                            @case('pledged') 📋 @break
                                            @case('received') ✅ @break
                                            @case('in_use') 🔄 @break
                                            @default ❓ @break
                                            @endswitch
                                            {{ ucfirst($resource->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted"><i class="fas fa-exclamation-circle me-2"></i>Priorité</td>
                                    <td>
                                        <span class="badge bg-{{ $resource->priority == 'urgent' ? 'danger' : ($resource->priority == 'high' ? 'warning' : ($resource->priority == 'medium' ? 'info' : 'success')) }}">
                                            @switch($resource->priority)
                                            @case('urgent') 🚨 @break
                                            @case('high') ⚠️ @break
                                            @case('medium') 📊 @break
                                            @default 📌 @break
                                            @endswitch
                                            {{ ucfirst($resource->priority) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Description and Notes -->
                    @if($resource->description || $resource->notes)
                    <div class="row mt-3">
                        @if($resource->description)
                        <div class="col-12 mb-3">
                            <h6><i class="fas fa-align-left me-2"></i>Description</h6>
                            <div class="p-3 bg-light rounded">
                                {{ $resource->description }}
                            </div>
                        </div>
                        @endif

                        @if($resource->notes)
                        <div class="col-12">
                            <h6><i class="fas fa-sticky-note me-2"></i>Notes supplémentaires</h6>
                            <div class="p-3 bg-warning bg-opacity-10 rounded border border-warning">
                                <i class="fas fa-info-circle me-2 text-warning"></i>{{ $resource->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Resource Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-image me-2"></i>Image</h6>
                </div>
                <div class="card-body text-center">
                    @if($resource->image_url)
                    <img src="{{ Storage::url($resource->image_url) }}"
                        alt="{{ $resource->name }}"
                        class="resource-image img-fluid rounded"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="resource-image-placeholder" style="display: none;">
                        @switch($resource->resource_type)
                        @case('money') <i class="fas fa-money-bill-wave fa-3x text-muted"></i> @break
                        @case('food') <i class="fas fa-apple-alt fa-3x text-muted"></i> @break
                        @case('clothing') <i class="fas fa-tshirt fa-3x text-muted"></i> @break
                        @case('medical') <i class="fas fa-medkit fa-3x text-muted"></i> @break
                        @case('equipment') <i class="fas fa-tools fa-3x text-muted"></i> @break
                        @case('human') <i class="fas fa-users fa-3x text-muted"></i> @break
                        @default <i class="fas fa-box fa-3x text-muted"></i> @break
                        @endswitch
                        <p class="text-muted mt-2 mb-0">Image non disponible</p>
                    </div>
                    @else
                    <div class="resource-image-placeholder">
                        @switch($resource->resource_type)
                        @case('money') <i class="fas fa-money-bill-wave fa-3x text-muted"></i> @break
                        @case('food') <i class="fas fa-apple-alt fa-3x text-muted"></i> @break
                        @case('clothing') <i class="fas fa-tshirt fa-3x text-muted"></i> @break
                        @case('medical') <i class="fas fa-medkit fa-3x text-muted"></i> @break
                        @case('equipment') <i class="fas fa-tools fa-3x text-muted"></i> @break
                        @case('human') <i class="fas fa-users fa-3x text-muted"></i> @break
                        @default <i class="fas fa-box fa-3x text-muted"></i> @break
                        @endswitch
                        <p class="text-muted mt-2 mb-0">Aucune image</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h6>
                </div>
                <div class="card-body">
                    <!-- Add Pledge Form -->
                    <form action="{{ route('resources.pledge', $resource) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Ajouter une promesse</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="quantity" class="form-control"
                                    placeholder="Quantité" min="1" required>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <input type="text" name="provider" class="form-control form-control-sm mt-1"
                                placeholder="Fournisseur (optionnel)">
                        </div>
                    </form>

                    <!-- Status Change Form -->
                    <form action="{{ route('resources.update-status', $resource) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Changer le statut</label>
                            <div class="input-group input-group-sm">
                                <select name="status" class="form-select" required>
                                    @foreach(['needed' => '⏳ Nécessaire', 'pledged' => '📋 Promis', 'received' => '✅ Reçu', 'in_use' => '🔄 Utilisé'] as $value => $label)
                                    <option value="{{ $value }}" {{ $resource->status == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-eco">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Métadonnées</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Type:</span>
                            <span class="fw-medium">
                                @switch($resource->resource_type)
                                @case('money') 💰 Argent @break
                                @case('food') 🍎 Nourriture @break
                                @case('clothing') 👕 Vêtements @break
                                @case('medical') 🏥 Médical @break
                                @case('equipment') 🛠️ Équipement @break
                                @case('human') 👥 Main d'œuvre @break
                                @default 🔧 {{ ucfirst($resource->resource_type) }} @break
                                @endswitch
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Créée le:</span>
                            <span class="fw-medium">{{ $resource->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Modifiée le:</span>
                            <span class="fw-medium">{{ $resource->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: var(--eco-green);
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    .resource-image {
        max-height: 200px;
        object-fit: cover;
        border: 1px solid #e9ecef;
    }

    .resource-image-placeholder {
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
        border: 1px dashed #dee2e6;
    }

    .table-borderless td {
        border: none;
        padding: 0.5rem 0.25rem;
    }

    .bg-eco {
        background-color: var(--eco-green) !important;
    }

    .btn-eco {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }

    .btn-eco:hover {
        background-color: var(--eco-light-green);
        border-color: var(--eco-light-green);
        color: white;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
    }

    .progress-bar {
        border-radius: 0.25rem;
        transition: width 0.6s ease;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .btn-group {
            justify-content: center;
        }

        .table-borderless td {
            padding: 0.25rem 0.125rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush