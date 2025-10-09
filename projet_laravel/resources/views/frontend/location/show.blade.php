@extends('layouts.frontend')

@section('title', $location->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background: transparent; padding: 0; margin: 0;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27;">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('locations.index') }}" style="color: #2d5a27;">Lieux</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;">{{ $location->name }}</li>
                    </ol>
                </nav>
                <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary rounded-pill mt-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
            <div class="card shadow-lg border-0" style="border-radius:28px; background: #f8fbf7;">
                @if($location->images && count($location->images))
                    <img src="{{ asset('storage/' . $location->images[0]) }}" class="card-img-top" style="height:260px; object-fit:cover; border-radius:28px 28px 0 0;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:260px; border-radius:28px 28px 0 0;">
                        <i class="fas fa-tree fa-3x text-success"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h2 class="fw-bold mb-2" style="color:#2d5a27;">{{ $location->name }}</h2>
                    <div class="mb-2">
                        <span class="badge bg-success bg-opacity-25 me-2" style="background-color:#eaf7ea!important; color:#2d5a27!important;"><i class="fas fa-map-marker-alt me-1" style="color:#2d5a27;"></i><span style="color:#111;">{{ $location->address }}, {{ $location->city }}</span></span>
                        <span class="badge bg-secondary bg-opacity-25" style="background-color:#eaf7ea!important; color:#2d5a27!important;"><i class="fas fa-users me-1" style="color:#2d5a27;"></i><span style="color:#111;">Capacité : {{ $location->capacity ?? 'Non spécifiée' }}</span></span>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-align-left me-2" style="color:#2d5a27;"></i><span style="color:#111;">{{ $location->description }}</span>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <span class="badge bg-light border" style="color:#2d5a27;"><i class="fas fa-globe-africa me-1" style="color:#2d5a27;"></i><span style="color:#111;">Lat: {{ $location->latitude }}</span></span>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-light border" style="color:#2d5a27;"><i class="fas fa-globe-europe me-1" style="color:#2d5a27;"></i><span style="color:#111;">Lng: {{ $location->longitude }}</span></span>
                        </div>
                    </div>
                    @if($location->images && count($location->images) > 1)
                    <div class="mb-3">
                        <div class="row g-2">
                            @foreach(array_slice($location->images, 1) as $img)
                                <div class="col-4">
                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow-sm" style="height:60px; object-fit:cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('locations.edit', $location) }}" class="btn btn-outline-success rounded-pill px-4 me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <form action="{{ route('locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Supprimer ce lieu ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
