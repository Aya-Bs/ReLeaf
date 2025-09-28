@extends('backend.layouts.app')

@section('title', 'Créer une Ressource')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Créer une Nouvelle Ressource</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de la ressource *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="ex: Plants d'arbres, Gants de protection..." required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3"
                                              placeholder="Description détaillée de la ressource...">{{ old('description') }}</textarea>
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
                                                    <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                                        {{ $campaign->name }} ({{ $campaign->category }})
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
                                                   id="provider" name="provider" value="{{ old('provider') }}"
                                                   placeholder="ex: Entreprise XYZ, Donateur...">
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
                                                   value="{{ old('quantity_needed', 1) }}" min="1" required>
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
                                                   value="{{ old('quantity_pledged', 0) }}" min="0">
                                            @error('quantity_pledged')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="unit" class="form-label">Unité *</label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                                   id="unit" name="unit" value="{{ old('unit', 'unité') }}"
                                                   placeholder="ex: kg, litre, pièce..." required>
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
                                                <option value="">Sélectionnez un type</option>
                                                @foreach(['money' => 'Argent', 'food' => 'Nourriture', 'clothing' => 'Vêtements', 'medical' => 'Médical', 'equipment' => 'Équipement', 'human' => 'Main d\'œuvre', 'other' => 'Autre'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('resource_type') == $value ? 'selected' : '' }}>
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
                                                    <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
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
                                            <option value="{{ $value }}" {{ old('priority', 'medium') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut initial *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        @foreach(['needed' => 'Nécessaire', 'pledged' => 'Promis', 'received' => 'Reçu', 'in_use' => 'En utilisation'] as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', 'needed') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Image de la ressource</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="imagePreview" src="#" alt="Aperçu de l'image" 
                                             class="img-fluid rounded" style="display: none; max-height: 200px;">
                                    </div>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-info-circle"></i> Informations</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Les champs marqués d'un * sont obligatoires</li>
                                            <li>L'image doit être au format JPG, PNG ou WebP</li>
                                            <li>Taille maximale : 2MB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes supplémentaires</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2"
                                      placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('resources.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Créer la ressource
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
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }

    // Calcul automatique du statut basé sur les quantités
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
        quantityNeeded.addEventListener('input', updateStatus);
        quantityPledged.addEventListener('input', updateStatus);
    }
});
</script>
@endsection