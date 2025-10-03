@extends('layouts.frontend')

@section('title', 'Lieux')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1" style="background: transparent; padding: 0; margin: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27;">Accueil</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;">Lieux</li>
                        </ol>
                    </nav>
                    <h2 class="fw-bold mt-2" style="color:#2d5a27">
                        <i class="fas fa-map-marked-alt me-2" style="color:#2d5a27;"></i>Mes Lieux
                    </h2>
                </div>
                <a href="{{ route('locations.create') }}" class="btn btn-eco rounded-pill shadow-sm px-4 py-2" style="background:#2d5a27; color:white;">
                    <i class="fas fa-plus-circle me-1"></i> Ajouter un lieu
                </a>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row g-4">
                @forelse($locations as $location)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-lg" style="border-radius:22px; background: #f8fbf7;">
                        @if($location->images && count($location->images))
                            <img src="{{ asset('storage/' . $location->images[0]) }}" class="card-img-top" style="height:200px; object-fit:cover; border-radius:22px 22px 0 0;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:200px; border-radius:22px 22px 0 0;">
                                <i class="fas fa-tree fa-3x text-success"></i>
                            </div>
                        @endif
                        <div class="card-body pb-2">
                            <h5 class="card-title mb-1 fw-bold" style="color:#2d5a27;">{{ $location->name }}</h5>
                            <div class="mb-2">
                                <span class="badge bg-success bg-opacity-25 me-2" style="background-color:#eaf7ea!important; color:#2d5a27!important;"><i class="fas fa-map-marker-alt me-1" style="color:#2d5a27;"></i><span style="color:#111;">{{ $location->city }}</span></span>
                                <span class="badge bg-secondary bg-opacity-25" style="background-color:#eaf7ea!important; color:#2d5a27!important;"><i class="fas fa-users me-1" style="color:#2d5a27;"></i><span style="color:#111;">{{ $location->capacity ?? 'N/A' }}</span></span>
                            </div>
                            <p class="card-text small" style="color:#111;">{{ Str::limit($location->description, 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="{{ route('locations.show', $location) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">Voir</a>
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Modifier</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="fas fa-map-marked-alt fa-2x mb-2"></i><br>
                    Aucun lieu enregistré pour le moment.
                </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $locations->links() }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
