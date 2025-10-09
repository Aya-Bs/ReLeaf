@extends('backend.layouts.app')

@section('title', 'Liste des lieux')

@section('content')
<style>
    .locations-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .locations-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 2px 6px rgba(45, 90, 39, 0.18);
    }
    .locations-title-text h1 {
        margin: 0;
        font-size: 23px;
        font-weight: 600;
        color: #2d5a27;
    }
    /* Pagination custom style */
    .pagination {
        justify-content: center !important;
        margin-top: 1.5rem !important;
        gap: 12px !important;
    }
    .pagination .page-item {
        border-radius: 12px !important;
        overflow: hidden !important;
    }
    .pagination .page-link {
        color: #2d5a27 !important;
        background: #f4f7f4 !important;
        border: none !important;
        border-radius: 12px !important;
        margin: 0 !important;
        min-width: 48px !important;
        min-height: 48px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 18px !important;
        font-weight: 500 !important;
        transition: background 0.2s, color 0.2s !important;
        box-shadow: none !important;
    }
    .pagination .page-link:focus {
        outline: none !important;
        box-shadow: 0 0 0 2px #2d5a2733 !important;
    }
    .pagination .page-item.active .page-link {
        background: #2d5a27 !important;
        color: #fff !important;
        border: none !important;
    }
    .pagination .page-link:hover {
        background: #e0e8e0 !important;
        color: #2d5a27 !important;
    }
    .pagination .page-item.disabled .page-link {
        background: #f4f7f4 !important;
        color: #b0b0b0 !important;
        border: none !important;
    }
</style>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2" style="margin-bottom:0;">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord </a></li>
                <li class="breadcrumb-item active" aria-current="page">Lieux</li>
            </ol>
        </nav>
    </div>
    <div class="locations-title-section">
        <div class="locations-icon">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="locations-title-text">
            <h1>Lieux</h1>
        </div>
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
@endsection
