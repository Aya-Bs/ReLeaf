@extends('layouts.frontend')

@section('title', 'Modifier profil volontaire')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Modifier mon profil volontaire</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('volunteers.update', $volunteer) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $volunteer->status==='active'?'selected':'' }}>Actif</option>
                                <option value="inactive" {{ $volunteer->status==='inactive'?'selected':'' }}>Inactif</option>
                                <option value="suspended" {{ $volunteer->status==='suspended'?'selected':'' }}>Suspendu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" rows="3" class="form-control">{{ old('bio', $volunteer->bio) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Motivation</label>
                            <textarea name="motivation" rows="3" class="form-control">{{ old('motivation', $volunteer->motivation) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Heures max/semaine</label>
                            <input type="number" name="max_hours_per_week" class="form-control" value="{{ old('max_hours_per_week', $volunteer->max_hours_per_week) }}" min="1" max="168" />
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('volunteers.show', $volunteer) }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




