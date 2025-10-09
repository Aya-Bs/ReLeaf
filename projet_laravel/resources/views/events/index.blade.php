@extends('layouts.frontend')

@section('title', 'EcoEvents - Événements')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-eco">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Événements EcoEvents
                </h2>
                <div class="btn-group">
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filtres
                    </button>
                </div>
            </div>

            <!-- Filtres rapides -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'all']) }}" 
                           class="btn {{ request('filter') === 'all' || !request('filter') ? 'btn-eco' : 'btn-outline-secondary' }}">
                            Tous les événements
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'upcoming']) }}" 
                           class="btn {{ request('filter') === 'upcoming' ? 'btn-eco' : 'btn-outline-secondary' }}">
                            À venir
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'available']) }}" 
                           class="btn {{ request('filter') === 'available' ? 'btn-eco' : 'btn-outline-secondary' }}">
                            Places disponibles
                        </a>
                    </div>
                </div>
            </div>

            <!-- Liste des événements -->
            <div class="row">
                @forelse($events as $event)
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if($event->image)
                                <img src="{{ asset('images/events/' . $event->image) }}" 
                                     class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-eco d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-leaf fa-3x text-white"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-eco">{{ $event->title }}</h5>
                                
                                <div class="event-meta mb-3">
                                    <div class="row text-muted small">
                                        <div class="col-6">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $event->date->format('d/m/Y') }}
                                        </div>
                                        <div class="col-6">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $event->date->format('H:i') }}
                                        </div>
                                        <div class="col-12 mt-1">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($event->location, 30) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="card-text flex-grow-1">
                                    {{ Str::limit($event->description, 120) }}
                                </p>
                                
                                <!-- Places disponibles -->
                                
                                <div class="availability-info mb-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <small class="text-muted">
                                                Places : {{ $event->availableSeats }}/{{ $event->max_participants }} disponibles
                                            </small>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-eco" 
                                                     style="width: {{ ($event->availableSeats / $event->max_participants) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            @if($event->availableSeats <= 5 && $event->availableSeats > 0)
                                                <span class="badge bg-warning">Dernières places</span>
                                            @elseif($event->availableSeats == 0)
                                                <span class="badge bg-danger">Complet</span>
                                            @else
                                                <span class="badge bg-success">Disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="card-actions mt-auto">
                                    @if($event->userReservation)
                                        <div class="alert alert-info py-2 mb-2">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                Réservation {{ $event->userReservation->status === 'confirmed' ? 'confirmée' : 'en attente' }} 
                                                - Place {{ $event->userReservation->seat_number }}
                                            </small>
                                        </div>
                                        <a href="{{ route('reservations.confirmation', $event->userReservation) }}" 
                                           class="btn btn-outline-eco btn-sm w-100">
                                            <i class="fas fa-eye me-2"></i>Voir ma réservation
                                        </a>
                                    @elseif($event->userInWaitingList)
                                        <div class="alert alert-warning py-2 mb-2">
                                            <small>
                                                <i class="fas fa-clock me-1"></i>
                                                Vous êtes dans la liste d'attente
                                                @php
                                                    $position = \App\Models\WaitingList::getUserPosition(auth()->id(), $event->id);
                                                @endphp
                                                @if($position)
                                                    - Position {{ $position }}
                                                @endif
                                            </small>
                                        </div>
                                        <button class="btn btn-outline-warning w-100" disabled>
                                            <i class="fas fa-hourglass-half me-2"></i>En liste d'attente
                                        </button>
                                    @elseif($event->availableSeats > 0 && auth()->check())
                                        <a href="{{ route('events.seats', $event) }}" 
                                           class="btn btn-eco w-100">
                                            <i class="fas fa-ticket-alt me-2"></i>Réserver une place
                                        </a>
                                    @elseif($event->isFull && auth()->check())
                                        <form action="{{ route('waiting-list.join', $event) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-warning w-100">
                                                <i class="fas fa-user-plus me-2"></i>Rejoindre la liste d'attente
                                            </button>
                                        </form>
                                    @elseif($event->isFull)
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-times me-2"></i>Événement complet
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-eco w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>Connectez-vous pour réserver
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Par {{ $event->user->name }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $event->date->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucun événement trouvé</h4>
                            <p class="text-muted">Il n'y a pas d'événements correspondant à vos critères.</p>
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('backend.events.create') }}" class="btn btn-eco">
                                        <i class="fas fa-plus me-2"></i>Créer un événement
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($events->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $events->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-eco {
    background-color: #2d5a27;
}
.text-eco {
    color: #2d5a27;
}
.btn-eco {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.btn-eco:hover {
    background-color: #234420;
    border-color: #234420;
    color: white;
}
.btn-outline-eco {
    border-color: #2d5a27;
    color: #2d5a27;
}
.btn-outline-eco:hover {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}
</style>
@endpush
@endsection
