@extends('layouts.frontend')

@section('title', 'Modifier la Mission')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Modifier la mission</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('assignments.update', $assignment) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="volunteer_id" class="form-label">Volontaire</label>
                            <select class="form-select" id="volunteer_id" name="volunteer_id" required>
                                @foreach($volunteers as $volunteer)
                                    <option value="{{ $volunteer->id }}" 
                                            {{ $assignment->volunteer_id == $volunteer->id ? 'selected' : '' }}>
                                        {{ $volunteer->user->name }} ({{ $volunteer->user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <input type="text" class="form-control" id="role" name="role" 
                                   value="{{ old('role', $assignment->role) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ old('start_date', $assignment->start_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ old('end_date', $assignment->end_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="hours_committed" class="form-label">Heures engagées</label>
                            <input type="number" class="form-control" id="hours_committed" name="hours_committed" 
                                   value="{{ old('hours_committed', $assignment->hours_committed) }}" 
                                   min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $assignment->notes) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('assignments.show', $assignment) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
