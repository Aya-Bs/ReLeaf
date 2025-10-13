@extends('layouts.frontend')

@section('title', 'Créer une Mission')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Créer une nouvelle mission</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('assignments.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="volunteer_id" class="form-label">Volontaire <span class="text-danger">*</span></label>
                            <select class="form-select" id="volunteer_id" name="volunteer_id" required>
                                <option value="">Sélectionner un volontaire</option>
                                @foreach($volunteers as $volunteer)
                                    <option value="{{ $volunteer->id }}" {{ old('volunteer_id') == $volunteer->id ? 'selected' : '' }}>
                                        {{ $volunteer->user->name }} ({{ $volunteer->user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('volunteer_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="assignable_type" class="form-label">Type de mission <span class="text-danger">*</span></label>
                            <select class="form-select" id="assignable_type" name="assignable_type" required>
                                <option value="">Sélectionner le type</option>
                                <option value="App\Models\Event" {{ old('assignable_type') == 'App\Models\Event' ? 'selected' : '' }}>Événement</option>
                                <option value="App\Models\Campaign" {{ old('assignable_type') == 'App\Models\Campaign' ? 'selected' : '' }}>Campagne</option>
                            </select>
                            @error('assignable_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="assignable_id" class="form-label">ID de l'élément <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="assignable_id" name="assignable_id" 
                                   value="{{ old('assignable_id') }}" required>
                            <div class="form-text">ID de l'événement ou de la campagne</div>
                            @error('assignable_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="role" name="role" 
                                   value="{{ old('role') }}" placeholder="Ex: Coordinateur, Aide, Spécialiste..." required>
                            @error('role')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="hours_committed" class="form-label">Heures engagées <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="hours_committed" name="hours_committed" 
                                   value="{{ old('hours_committed') }}" min="1" required>
                            @error('hours_committed')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Notes supplémentaires sur la mission...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('assignments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Créer la mission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
