@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2 text-success"></i>
                Sponsor Dashboard
            </h1>
            <p class="text-muted">Bienvenue, {{ Auth::user()->name }} !</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->avatar_url }}" alt="avatar" width="32" class="rounded-circle me-2">
                <span>{{ Auth::user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 d-flex gap-2 flex-wrap">
            <a href="{{ route('donations.list') }}" class="btn btn-outline-success">
                <i class="fas fa-hand-holding-heart me-1"></i> Mes dons
            </a>
            @if(Auth::user()->sponsor)
            <a href="{{ route('sponsor.self.edit') }}" class="btn btn-outline-secondary">
                <i class="fas fa-building me-1"></i> Mon profil sponsor
            </a>
            @if(Auth::user()->sponsor->isDeletionRequested())
            <span class="badge bg-warning text-dark align-self-center">Suppression demandée</span>
            @endif
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Sponsoring Demands Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Sponsoring Demands
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">This section will display your sponsoring demands.</p>
                    {{-- This will be implemented later --}}
                </div>
            </div>
        </div>

        <!-- Make a Donation Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding-heart me-2"></i>
                        Make a Donation
                    </h5>
                </div>
                <div class="card-body">
                    <p>Choose an event to support:</p>
                    <div class="list-group">
                        @forelse($events as $event)
                        <a href="{{ route('donations.create', $event) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <small class="text-muted">{{ $event->date->format('d/m/Y') }} - {{ $event->location }}</small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        @empty
                        <p class="text-center text-muted">No events available for donation at the moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Donations Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Mes 5 derniers dons
                    </h5>
                </div>
                <div class="card-body">
                    @php
                    $recentDonations = \App\Models\Donation::with('event')
                    ->when(Auth::user()->sponsor, function($q){
                    $q->where(function($sub){ $user = Auth::user(); $sub->where('sponsor_id', $user->sponsor->id)->orWhere('user_id', $user->id); });
                    }, function($q){ $q->where('user_id', Auth::id()); })
                    ->latest()->take(5)->get();
                    @endphp
                    @if($recentDonations->isEmpty())
                    <p class="text-muted mb-0">Aucun don récent.</p>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentDonations as $donation)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="me-2">
                                <div class="fw-semibold">{{ $donation->event?->title ?? 'Événement' }}</div>
                                <small class="text-muted">{{ ($donation->donated_at ?? $donation->created_at)->format('d/m/Y H:i') }} · {{ number_format($donation->amount,2,',',' ') }} {{ $donation->currency }}</small><br>
                                <small>
                                    @if($donation->status === 'pending')
                                    <span class="badge bg-secondary">En attente</span>
                                    @elseif($donation->status === 'confirmed')
                                    <span class="badge bg-success">Confirmé</span>
                                    @else
                                    <span class="badge bg-danger">{{ ucfirst($donation->status) }}</span>
                                    @endif
                                    @php $r = $donation->editableRemainingHours(); @endphp
                                    @if($donation->canBeModifiedBy(Auth::user()))
                                    <span class="badge bg-info text-dark">Modifiable {{ $r }}h</span>
                                    @endif
                                </small>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1">
                                @if($donation->canBeModifiedBy(Auth::user()))
                                <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('donations.destroy', $donation) }}" onsubmit="return confirm('Supprimer ce don ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="mt-3 text-end">
                        <a href="{{ route('donations.list') }}" class="small">Voir tous mes dons &raquo;</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection