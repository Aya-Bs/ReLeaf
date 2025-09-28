@extends('backend.layouts.app')

@section('title', 'Modifier la Ressource: ' . $resource->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Modifier la Ressource: {{ $resource->name }}</h5>
                    <div>
                        <a href="{{ route('resources.show', $resource) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        <a href="{{ route('resources.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de la ressource *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $resource->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $resource->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="campaign_id" class="form-label">Campagne associée *</label>
                                            <select class="form-control @error('campaign_id') is-invalid @enderror" 
                                                    id="campaign_id" name="campaign_id" required>
                                                <option value="">Sélectionnez une campagne</option>
                                                @foreach($campaigns as $campaign)
                                                    <option value="{{ $campaign->id }}" {{ old('campaign_id', $resource->campaign_id) == $campaign->id ? 'selected' : '' }}>
                                                        {{ $campaign->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('campaign_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="provider" class="form-label">Fournisseur</label>
                                            <input type="text" class="form-control @error('provider') is-invalid @enderror" 
                                                   id="provider" name="provider" value="{{ old('provider', $resource->provider) }}">
                                            @error('provider')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="quantity_needed" class="form-label">Quantité nécessaire *</label>
                                            <input type="number" class="form-control @error('quantity_needed') is-invalid @enderror" 
                                                   id="quantity_needed" name="quantity_needed" 
                                                   value="{{ old('quantity_needed', $resource->quantity_needed) }}" min="1" required>
                                            @error('quantity_needed')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="quantity_pledged" class="form-label">Quantité promise</label>
                                            <input type="number" class="form-control @error('quantity_pledged') is-invalid @enderror" 
                                                   id="quantity_pledged" name="quantity_pledged" 
                                                   value="{{ old('quantity_pledged', $resource->quantity_pledged) }}" min="0">
                                            @error('quantity_pledged')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="unit" class="form-label">Unité *</label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                                   id="unit" name="unit" value="{{ old('unit', $resource->unit) }}" required>
                                            @error('unit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="resource_type" class="form-label">Type de ressource *</label>
                                            <select class="form-control @error('resource_type') is-invalid @enderror" 
                                                    id="resource_type" name="resource_type" required>
                                                @foreach(['money' => 'Argent', 'food' => 'Nourriture', 'clothing' => 'Vêtements', 'medical' => 'Médical', 'equipment' => 'Équipement', 'human' => 'Main d\'œuvre', 'other' => 'Autre'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('resource_type', $resource->resource_type) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('resource_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Catégorie *</label>
                                            <select class="form-control @error('category') is-invalid @enderror" 
                                                    id="category" name="category" required>
                                                @foreach(['materiel' => 'Matériel', 'financier' => 'Financier', 'humain' => 'Humain', 'technique' => 'Technique'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('category', $resource->category) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priorité *</label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" name="priority" required>
                                        @foreach(['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'urgent' => 'Urgente'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('priority', $resource->priority) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        @foreach(['needed' => 'Nécessaire', 'pledged' => 'Promis', 'received' => 'Reçu', 'in_use' => 'En utilisation'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $resource->status) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Image actuelle</label>
                                    @if($resource->image_url)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($resource->image_url) }}" 
                                                 alt="{{ $resource->name }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 150px; width: 100%; object-fit: cover;">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" 
                                                       id="remove_image" name="remove_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_image">
                                                    Supprimer l'image
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-muted mb-2">
                                            <i class="fas fa-image fa-2x"></i>
                                            <p>Aucune image</p>
                                        </div>
                                    @endif
                                    
                                    <label for="image" class="form-label">Changer l'image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Statistiques</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Progression: {{ $resource->progress_percentage }}%</li>
                                            <li>Manquant: {{ $resource->missing_quantity }} {{ $resource->unit }}</li>
                                            <li>Créée le: {{ $resource->created_at->format('d/m/Y') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes supplémentaires</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes', $resource->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('resources.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu de l'image
    const imageInput = document.getElementById('image');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!preview) {
                        // Créer l'élément preview s'il n'existe pas
                        const previewDiv = imageInput.parentElement;
                        const newPreview = document.createElement('img');
                        newPreview.id = 'imagePreview';
                        newPreview.src = e.target.result;
                        newPreview.className = 'img-fluid rounded mt-2';
                        newPreview.style = 'max-height: 150px; display: block;';
                        previewDiv.appendChild(newPreview);
                    } else {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Calcul automatique du statut
    const quantityNeeded = document.getElementById('quantity_needed');
    const quantityPledged = document.getElementById('quantity_pledged');
    const statusSelect = document.getElementById('status');

    function updateStatus() {
        const needed = parseInt(quantityNeeded.value) || 0;
        const pledged = parseInt(quantityPledged.value) || 0;

        if (needed > 0 && pledged >= needed) {
            statusSelect.value = 'received';
        } else if (pledged > 0) {
            statusSelect.value = 'pledged';
        } else {
            statusSelect.value = 'needed';
        }
    }

    if (quantityNeeded && quantityPledged && statusSelect) {
        quantityNeeded.addEventListener('change', updateStatus);
        quantityPledged.addEventListener('change', updateStatus);
    }
});
</script>
@endsection