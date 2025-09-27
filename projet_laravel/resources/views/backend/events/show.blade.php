@extends('backend.layouts.app')

@section('title', $event->title)
@section('page-title', 'Détails de l\'événement')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.events.index') }}">Événements</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($event->title, 30) }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('backend.events.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>

            <!-- Event Card -->
            <div class="card card-eco">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>{{ $event->title }}
                        </h4>
                        <div>
                            @if($event->isPending())
                                <span class="badge badge-warning">En attente</span>
                            @elseif($event->isPublished())
                                <span class="badge badge-success">Publié</span>
                            @elseif($event->isDraft())
                                <span class="badge badge-secondary">Brouillon</span>
                            @elseif($event->isCancelled())
                                <span class="badge badge-danger">Annulé</span>
                            @else
                                <span class="badge badge-light">{{ $event->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
<!-- Event Images -->
@php
    // Nettoyer les chemins d'images
    $cleanedImages = [];
    if ($event->images && is_array($event->images)) {
        foreach ($event->images as $image) {
            if (!empty($image) && $image !== 'null') {
                // Remplacer les \/ par /
                $cleanedImage = str_replace('\/', '/', $image);
                $cleanedImages[] = $cleanedImage;
            }
        }
    }
@endphp

@if(count($cleanedImages) > 0)
<div class="mb-4">
    <h5><i class="fas fa-images me-2"></i>Images ({{ count($cleanedImages) }})</h5>
    <div class="row">
        @foreach($cleanedImages as $image)
        <div class="col-md-3 mb-3">
            <div class="card">
                <img src="{{ Storage::url($image) }}" 
                     class="card-img-top" 
                     style="height: 200px; object-fit: cover;"
                     alt="Image de l'événement"
                     onerror="console.log('Image error:', this.src); this.src='https://via.placeholder.com/300x200?text=Image+Non+Trouvée'">
                <div class="card-body p-2">
                    <small class="text-muted">Path: {{ $image }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    Aucune image disponible pour cet événement.
    <br><small>Images brutes: {{ json_encode($event->images) }}</small>
</div>
@endif

<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    Aucune image trouvée pour cet événement.
    @if($event->images)
        Images data: {{ json_encode($event->images) }}
    @else
        Images data is null or empty.
    @endif
</div>

                    <div class="row">
                        <!-- Left Column - Event Details -->
                        <div class="col-md-8">
                            <!-- Description -->
                            <div class="mb-4">
                                <h5><i class="fas fa-align-left me-2 text-primary"></i>Description</h5>
                                <p class="text-muted">{{ $event->description }}</p>
                            </div>

                            <!-- Event Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-calendar-day me-2 text-primary"></i>Date et heure</h6>
                                        <p class="text-muted">
                                            {{ $event->date->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-clock me-2 text-primary"></i>Durée</h6>
                                        <p class="text-muted">{{ $event->duration }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6><i class="fas fa-map-marker-alt me-2 text-primary"></i>Lieu</h6>
                                        <p class="text-muted">{{ $event->location }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6><i class="fas fa-users me-2 text-primary"></i>Participants maximum</h6>
                                        <p class="text-muted">
                                            {{ $event->max_participants ? $event->max_participants . ' personnes' : 'Illimité' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Statistics and Actions -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                         
                                    <div class="mb-3">
                                        <small class="text-muted">Organisateur</small>
                                        <div>{{ $event->user->name }}</div>
                                    </div>
                               
                                    <div class="mb-3">
                                        <small class="text-muted">Créé le</small>
                                        <div>{{ $event->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Dernière modification</small>
                                        <div>{{ $event->updated_at->format('d/m/Y à H:i') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Statut</small>
                                        <div>
                                            @if($event->isPending())
                                                <span class="badge badge-warning">En attente d'approbation</span>
                                            @elseif($event->isPublished())
                                                <span class="badge badge-success">Publié et visible</span>
                                            @elseif($event->isDraft())
                                                <span class="badge badge-secondary">Brouillon</span>
                                            @elseif($event->isCancelled())
                                                <span class="badge badge-danger">Événement annulé</span>
                                            @else
                                                <span class="badge badge-light">{{ $event->status }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Admin Actions -->
                                    <div class="mt-4">
                                        @if($event->isPending())
                                        <form action="#" method="POST" class="d-grid mb-2">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <i class="fas fa-check me-2"></i>Approuver
                                            </button>
                                        </form>
                                        <form action="#" method="POST" class="d-grid">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                <i class="fas fa-times me-2"></i>Rejeter
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection