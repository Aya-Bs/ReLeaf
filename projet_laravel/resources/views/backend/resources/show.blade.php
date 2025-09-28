@extends('backend.layouts.app')

@section('title', $resource->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Détails de la Ressource: {{ $resource->name }}</h5>
                    <div>
                        <a href="{{ route('resources.edit', $resource) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Informations générales</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Nom:</strong></td>
                                            <td>{{ $resource->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type:</strong></td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $resource->resource_type }}</span>
                                                <span class="badge bg-info">{{ $resource->category }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Campagne:</strong></td>
                                            <td>
                                                <a href="{{ route('campaigns.show', $resource->campaign) }}" 
                                                   class="text-decoration-none">
                                                    {{ $resource->campaign->name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fournisseur:</strong></td>
                                            <td>{{ $resource->provider ?? 'Non spécifié' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Unité:</strong></td>
                                            <td>{{ $resource->unit }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Statistiques</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Quantité nécessaire:</strong></td>
                                            <td class="text-primary">{{ $resource->quantity_needed }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Quantité promise:</strong></td>
                                            <td class="text-success">{{ $resource->quantity_pledged }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Manquant:</strong></td>
                                            <td class="text-danger">{{ $resource->missing_quantity }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Progression:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $resource->progress_percentage == 100 ? 'success' : 'warning' }}">
                                                    {{ $resource->progress_percentage }}%
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Statut:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ [
                                                    'needed' => 'secondary',
                                                    'pledged' => 'info', 
                                                    'received' => 'success',
                                                    'in_use' => 'primary'
                                                ][$resource->status] }}">
                                                    {{ $resource->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($resource->description)
                            <div class="mb-4">
                                <h6>Description</h6>
                                <p class="text-muted">{{ $resource->description }}</p>
                            </div>
                            @endif

                            @if($resource->notes)
                            <div class="mb-4">
                                <h6>Notes supplémentaires</h6>
                                <p class="text-muted">{{ $resource->notes }}</p>
                            </div>
                            @endif

                            <!-- Barre de progression détaillée -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Progression de la collecte</h6>
                                </div>
                                <div class="card-body">
                                    <div class="progress mb-2" style="height: 25px;">
                                        <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : 'warning' }}" 
                                             style="width: {{ $resource->progress_percentage }}%">
                                            <strong>{{ $resource->progress_percentage }}%</strong>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <small class="text-muted">Nécessaire</small>
                                            <br>
                                            <strong>{{ $resource->quantity_needed }} {{ $resource->unit }}</strong>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Collecté</small>
                                            <br>
                                            <strong class="text-success">{{ $resource->quantity_pledged }} {{ $resource->unit }}</strong>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Manquant</small>
                                            <br>
                                            <strong class="text-danger">{{ $resource->missing_quantity }} {{ $resource->unit }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Image -->
                            @if($resource->image_url)
                                <div class="text-center mb-3">
                                    <img src="{{ Storage::url($resource->image_url) }}" 
                                         alt="{{ $resource->name }}"
                                         class="img-fluid rounded"
                                         style="max-height: 300px; width: 100%; object-fit: cover;">
                                </div>
                            @endif

                            <!-- Carte priorité -->
                            <div class="card mb-3 border-{{ [
                                'low' => 'success',
                                'medium' => 'info',
                                'high' => 'warning',
                                'urgent' => 'danger'
                            ][$resource->priority] }}">
                                <div class="card-header bg-{{ [
                                    'low' => 'success',
                                    'medium' => 'info',
                                    'high' => 'warning',
                                    'urgent' => 'danger'
                                ][$resource->priority] }} text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-{{ $resource->priority == 'urgent' ? 'exclamation-triangle' : 'flag' }}"></i>
                                        Priorité: {{ $resource->priority }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">
                                        @if($resource->priority == 'urgent')
                                            <span class="text-danger"><i class="fas fa-exclamation-circle"></i> Action requise immédiatement</span>
                                        @elseif($resource->priority == 'high')
                                            <span class="text-warning"><i class="fas fa-clock"></i> Important à traiter rapidement</span>
                                        @elseif($resource->priority == 'medium')
                                            <span class="text-info"><i class="fas fa-check-circle"></i> Priorité normale</span>
                                        @else
                                            <span class="text-success"><i class="fas fa-check"></i> Peut attendre</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Actions rapides -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Actions rapides</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Formulaire promesse -->
                                    <form action="{{ route('resources.pledge', $resource) }}" method="POST" class="mb-3">
                                        @csrf
                                        <label class="form-label">Ajouter une promesse</label>
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control" 
                                                   placeholder="Quantité" min="1" required>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-hand-holding-heart"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="provider" class="form-control mt-1" 
                                               placeholder="Fournisseur (optionnel)">
                                    </form>

                                    <!-- Changement de statut -->
                                    <form action="{{ route('resources.update-status', $resource) }}" method="POST">
                                        @csrf
                                        <label class="form-label">Changer le statut</label>
                                        <div class="input-group">
                                            <select name="status" class="form-control" required>
                                                @foreach(['needed' => 'Nécessaire', 'pledged' => 'Promis', 'received' => 'Reçu', 'in_use' => 'Utilisé'] as $value => $label)
                                                    <option value="{{ $value }}" {{ $resource->status == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Métadonnées -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="fas fa-info-circle"></i> Informations</h6>
                                    <ul class="small text-muted mb-0">
                                        <li>Créée le: {{ $resource->created_at->format('d/m/Y H:i') }}</li>
                                        <li>Modifiée le: {{ $resource->updated_at->format('d/m/Y H:i') }}</li>
                                        @if($resource->provider)
                                            <li>Fournisseur: {{ $resource->provider }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection