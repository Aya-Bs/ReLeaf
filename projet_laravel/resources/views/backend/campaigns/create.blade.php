@extends('backend.layouts.app')

@section('title', 'Créer une Campagne')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Créer une Nouvelle Campagne</h5>
                    <a href="{{ route('campaigns.index') }}" class="btn btn-secondary btn-sm float-end">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de la campagne *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Statut *</label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required>
                                                @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'completed' => 'Terminée', 'cancelled' => 'Annulée'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
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
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">Date de fin *</label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
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
                                                   id="goal" name="goal" value="{{ old('goal') }}">
                                            @error('goal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="environmental_impact" class="form-label">Impact environnemental</label>
                                            <input type="text" class="form-control @error('environmental_impact') is-invalid @enderror" 
                                                   id="environmental_impact" name="environmental_impact" value="{{ old('environmental_impact') }}">
                                            @error('environmental_impact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags') }}" 
                                           placeholder="ex: écologie, déchets, recyclage">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image de la campagne</label>
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

                                <div class="mb-3 form-check form-switch">
                                    <input type="checkbox" class="form-check-input" 
                                           id="visibility" name="visibility" value="1" 
                                           {{ old('visibility', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visibility">Campagne visible publiquement</label>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informations</h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Les champs marqués d'un * sont obligatoires</li>
                                            <li>L'image doit être au format JPG, PNG ou WebP</li>
                                            <li>La taille maximale est de 2MB</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Créer la campagne
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
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Validation des dates
    document.getElementById('start_date').addEventListener('change', function() {
        const endDate = document.getElementById('end_date');
        if (this.value && endDate.value && this.value > endDate.value) {
            endDate.value = '';
        }
        endDate.min = this.value;
    });
</script>
@endsection