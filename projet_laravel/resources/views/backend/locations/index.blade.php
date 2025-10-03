@extends('backend.layouts.app')

@section('title', 'Liste des lieux')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color:#2d5a27;">Lieux</h2>
    </div>
    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Adresse</th>
                        <th>Capacité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($locations as $location)
                    <tr>
                        <td>{{ $location->name }}</td>
                        <td>{{ $location->city }}</td>
                        <td>{{ $location->address }}</td>
                        <td>{{ $location->capacity ?? '-' }}</td>
                        <td>
                            <a href="{{ route('backend.locations.show', $location->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucun lieu trouvé.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $locations->links() }}
    </div>
</div>
@endsection
