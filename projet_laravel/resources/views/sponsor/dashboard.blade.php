@extends('layouts.frontend')

@section('title', 'Tableau de bord Sponsor')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-eco">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sponsor.dashboard') }}" class="text-eco">Sponsor</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
        </ol>
    </nav>

    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2 text-success"></i>
                Sponsor Dashboard
            </h1>
            <p class="text-muted">Bienvenue, {{ Auth::user()->name }} !</p>
        </div>

    </div>

    <div class="row mb-4">
        <div class="col-12 d-flex gap-2 flex-wrap">
            <a href="{{ route('donations.list') }}" class="btn btn-outline-success">
                <i class="fas fa-hand-holding-heart me-1"></i> Mes dons
            </a>
            @if(Auth::user()->sponsor)
            <a href="{{ route('sponsor.profile') }}" class="btn btn-outline-secondary">
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
                    @php
                    $s = Auth::user()->sponsor;
                    $requests = $s ? \App\Models\SponsorEvent::with('event')->where('sponsor_id', $s->id)->pending()->latest()->take(5)->get() : collect();
                    @endphp
                    @if($requests->isEmpty())
                    <p class="text-muted mb-0">Aucune demande récente.</p>
                    @else
                    <div class="list-group mb-3">
                        @foreach($requests as $req)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $req->event?->title ?? 'Événement' }}</div>
                                <small class="text-muted">{{ optional($req->event?->date)->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('sponsor.requests.accept', $req) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                </form>
                                <form method="POST" action="{{ route('sponsor.requests.decline', $req) }}" onsubmit="return confirm('Refuser cette demande ?');">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-end">
                        <a href="{{ route('sponsor.requests.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes les demandes</a>
                    </div>
                    @endif
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
                    @php
                    $s = Auth::user()->sponsor;
                    $mySponsoredIds = $s ? \App\Models\SponsorEvent::where('sponsor_id', $s->id)
                    ->whereIn('status', ['active','pending'])
                    ->pluck('event_id') : collect();
                    $mySponsoredEvents = $mySponsoredIds && $mySponsoredIds->count()
                    ? \App\Models\Event::whereIn('id', $mySponsoredIds)->where('status','published')->orderBy('date','desc')->take(5)->get()
                    : collect();
                    $otherEvents = \App\Models\Event::where('status','published')
                    ->when($mySponsoredIds && $mySponsoredIds->count(), function($q) use ($mySponsoredIds){ $q->whereNotIn('id', $mySponsoredIds); })
                    ->orderBy('date','desc')->take(5)->get();
                    @endphp

                    <!-- Events I already sponsor -->
                    <h6 class="text-success fw-semibold mb-2"><i class="fas fa-handshake me-1"></i> Événements que je sponsorise</h6>
                    @if($mySponsoredEvents->isEmpty())
                    <p class="text-muted">Aucun sponsoring en cours.</p>
                    @else
                    <div class="list-group mb-3">
                        @foreach($mySponsoredEvents as $event)
                        @php
                        $collected = \App\Models\Donation::where('event_id', $event->id)->where('status','confirmed')->sum('amount');
                        @endphp
                        <a href="{{ route('donations.create', $event) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $event->title }}</h6>
                                <span class="badge bg-success">Sponsorisé</span>
                            </div>
                            <div class="small text-muted mt-1">
                                <span><i class="far fa-calendar me-1"></i>{{ $event->date->format('d/m/Y') }}</span>
                                <span class="ms-2"><i class="fas fa-map-marker-alt me-1"></i>{{ $event->location?->name ?? 'Lieu' }}</span>
                                <span class="ms-3"><i class="fas fa-donate me-1 text-success"></i>Collecté: {{ number_format($collected, 2, ',', ' ') }} €</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    <!-- Other events I can support -->
                    <h6 class="fw-semibold mb-2"><i class="fas fa-calendar-alt me-1 text-muted"></i> Autres événements à soutenir</h6>
                    @if($otherEvents->isEmpty())
                    <p class="text-muted mb-0">Aucun autre événement disponible pour le moment.</p>
                    @else
                    <div class="list-group">
                        @foreach($otherEvents as $event)
                        @php
                        $collected = \App\Models\Donation::where('event_id', $event->id)->where('status','confirmed')->sum('amount');
                        @endphp
                        <a href="{{ route('donations.create', $event) }}" class="list-group-item list-group-item-action">
                            <h6 class="mb-0">{{ $event->title }}</h6>
                            <div class="small text-muted mt-1">
                                <span><i class="far fa-calendar me-1"></i>{{ $event->date->format('d/m/Y') }}</span>
                                <span class="ms-2"><i class="fas fa-map-marker-alt me-1"></i>{{ $event->location?->name ?? 'Lieu' }}</span>
                                <span class="ms-3"><i class="fas fa-donate me-1 text-success"></i>Collecté: {{ number_format($collected, 2, ',', ' ') }} €</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
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