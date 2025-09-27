@extends('backend.layouts.app')

@section('title', 'Modifier la Campagne: ' . $campaign->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Modifier la Campagne: {{ $campaign->name }}</h5>
                    <div>
                        <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        <a href="{{ route('campaigns.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de la campagne *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $campaign->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description', $campaign->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Catégorie *</label>
                                            <select class="form-control @error('category') is-invalid @enderror" 
                                                    id="category" name="category" required>
                                                <option value="">Sélectionnez une catégorie</option>
                                                @foreach(['reforestation' => 'Reforestation', 'nettoyage' => 'Nettoyage', 'sensibilisation' => 'Sensibilisation', 'recyclage' => 'Recyclage', 'biodiversite' => 'Biodiversité', 'energie_renouvelable' => 'Énergie Renouvelable', 'autre' => 'Autre'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('category', $campaign->category) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Statut *</label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required>
                                                @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'completed' => 'Terminée', 'cancelled' => 'Annulée'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('status', $campaign->status) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">Date de début *</label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" 
                                                   value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">Date de fin *</label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" 
                                                   value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="goal" class="form-label">Objectif financier (€)</label>
                                            <input type="number" step="0.01" class="form-control @error('goal') is-invalid @enderror" 
                                                   id="goal" name="goal" value="{{ old('goal', $campaign->goal) }}">
                                            @error('goal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="funds_raised" class="form-label">Montant collecté (€)</label>
                                            <input type="number" step="0.01" class="form-control @error('funds_raised') is-invalid @enderror" 
                                                   id="funds_raised" name="funds_raised" value="{{ old('funds_raised', $campaign->funds_raised) }}">
                                            @error('funds_raised')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="environmental_impact" class="form-label">Impact environnemental</label>
                                    <input type="text" class="form-control @error('environmental_impact') is-invalid @enderror" 
                                           id="environmental_impact" name="environmental_impact" 
                                           value="{{ old('environmental_impact', $campaign->environmental_impact) }}">
                                    @error('environmental_impact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" 
                                           value="{{ old('tags', $campaign->tags ? implode(', ', $campaign->tags) : '') }}" 
                                           placeholder="ex: écologie, déchets, recyclage">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Image actuelle</label>
                                    @if($campaign->image_url)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($campaign->image_url) }}" 
                                                 alt="{{ $campaign->name }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
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

                                <div class="mb-3 form-check form-switch">
                                    <input type="checkbox" class="form-check-input" 
                                           id="visibility" name="visibility" value="1" 
                                           {{ old('visibility', $campaign->visibility) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visibility">Campagne visible publiquement</label>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Statistiques</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Participants: {{ $campaign->participants_count }}</li>
                                            <li>Créée le: {{ $campaign->created_at->format('d/m/Y') }}</li>
                                            <li>Modifiée le: {{ $campaign->updated_at->format('d/m/Y') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">Annuler</a>
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
    // Aperçu de l'image
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (!preview) {
                    // Créer l'élément preview s'il n'existe pas
                    const previewDiv = document.querySelector('.mb-3');
                    const newPreview = document.createElement('img');
                    newPreview.id = 'imagePreview';
                    newPreview.src = e.target.result;
                    newPreview.className = 'img-fluid rounded mt-2';
                    newPreview.style = 'max-height: 200px; display: block;';
                    previewDiv.appendChild(newPreview);
                } else {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Validation des dates
    document.getElementById('start_date').addEventListener('change', function() {
        const endDate = document.getElementById('end_date');
        if (this.value && endDate.value && this.value > endDate.value) {
            alert('La date de fin doit être après la date de début');
            endDate.value = this.value;
        }
        endDate.min = this.value;
    });
</script>
@endsection