@extends('backend.layouts.app')

@section('title', 'Modifier le Volontaire')
@section('page-title', 'Modifier le Volontaire')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.volunteers.index') }}">Volontaires</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.volunteers.show', $volunteer) }}">{{ $volunteer->user->name }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Modifier le Volontaire
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('backend.volunteers.update', $volunteer) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Statut</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status', $volunteer->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status', $volunteer->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_hours_per_week">Heures max/semaine</label>
                                <input type="number" class="form-control" id="max_hours_per_week" name="max_hours_per_week" 
                                       value="{{ old('max_hours_per_week', $volunteer->max_hours_per_week) }}" 
                                       min="1" max="168">
                                @error('max_hours_per_week')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3">{{ old('bio', $volunteer->bio) }}</textarea>
                        @error('bio')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="motivation">Motivation</label>
                        <textarea class="form-control" id="motivation" name="motivation" rows="3">{{ old('motivation', $volunteer->motivation) }}</textarea>
                        @error('motivation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="emergency_contact">Contact d'urgence</label>
                        <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" 
                               value="{{ old('emergency_contact', $volunteer->emergency_contact) }}">
                        @error('emergency_contact')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="medical_conditions">Conditions médicales</label>
                        <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="2">{{ old('medical_conditions', $volunteer->medical_conditions) }}</textarea>
                        @error('medical_conditions')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('backend.volunteers.show', $volunteer) }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informations
                </h3>
            </div>
            <div class="card-body">
                <p><strong>Nom :</strong> {{ $volunteer->user->name }}</p>
                <p><strong>Email :</strong> {{ $volunteer->user->email }}</p>
                <p><strong>Inscrit le :</strong> {{ $volunteer->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Dernière modification :</strong> {{ $volunteer->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
