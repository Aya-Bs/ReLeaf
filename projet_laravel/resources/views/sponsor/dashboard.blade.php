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

    <!-- Welcome Banner with animated waves -->
    <div class="welcome-banner mb-4 rounded-4">
        <div class="banner-inner">
            <div class="banner-graphic">
                <img src="{{ asset('images/illustrations/sponsor-hero.png') }}" alt="Illustration ReLeaf" onerror="this.style.display='none'" />
            </div>
            <div class="banner-content">
                <h2 class="banner-title">Bienvenue</h2>
                <h1 class="banner-sponsor">{{ $sponsorName ?? (Auth::user()->sponsor->company_name ?? (Auth::user()->name ?? 'Guest')) }}</h1>

            </div>
            <div class="banner-image-right">
                <img src="{{ asset('images/sponsor.png') }}" alt="Sponsor" onerror="this.style.display='none'" />
            </div>

        </div>

        <!-- Decorative Shapes -->
        <svg class="shape shape-blob1" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <g transform="translate(300,300)">
                <path d="M120,-180C160,-150,200,-120,210,-80C220,-40,200,10,175,55C150,100,120,140,75,170C30,200,-30,220,-80,205C-130,190,-170,140,-190,90C-210,40,-210,-10,-195,-55C-180,-100,-150,-140,-115,-170C-80,-200,-40,-220,0,-220C40,-220,80,-200,120,-180Z"
                    fill="rgba(255,255,255,0.06)" />
            </g>
        </svg>
        <div class="shape shape-dots" aria-hidden="true"></div>

        <svg class="shape shape-ring" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="100" cy="100" r="80" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
            <circle cx="100" cy="100" r="60" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2" />
        </svg>



        <!-- Wave SVG Elements -->
        <svg class="wave wave-1" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,50 Q300,0 600,50 T1200,50 L1200,120 L0,120 Z" fill="rgba(255,255,255,0.1)"></path>
        </svg>

        <svg class="wave wave-2" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,60 Q300,20 600,60 T1200,60 L1200,120 L0,120 Z" fill="rgba(255,255,255,0.05)"></path>
        </svg>
    </div>

    <!-- Badges Row (prominent, centered and large) -->
    @if(isset($rewardStats) && !empty($rewardStats['badges']))
    <div class="badges-hero mb-4">
        @foreach($rewardStats['badges'] as $badge)
        <div class="hero-badge text-center">
            <div class="hero-badge-inner">
                @if(!empty($badge['image_url']))
                <img src="{{ asset($badge['image_url']) }}" alt="{{ $badge['label'] }}" />
                @else
                <i class="fas fa-medal {{
                        $badge['slug']==='bronze' ? 'text-warning' : (
                        $badge['slug']==='silver' ? 'text-secondary' : (
                        $badge['slug']==='gold' ? 'text-warning' : 'text-primary'))
                    }}"></i>
                @endif
            </div>
            <div class="badge-caption fw-semibold mt-2">{{ $badge['label'] }}</div>
        </div>
        @endforeach
    </div>
    @endif

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

    <!-- Stats Cards (modern) -->
    @if(isset($stats))
    <div class="row g-3 mb-4 stat-cards">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-title">Soutien Total</div>
                    <div class="stat-value">{{ number_format($stats['total_support'], 0, ',', ' ') }} €</div>
                    <div class="stat-delta text-success">+{{ number_format(max(0,$stats['recent_90d'] ?? 0), 0, ',', ' ') }} € / 90j</div>
                </div>
                <div class="stat-icon gradient-green">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-title">Dons Confirmés</div>
                    <div class="stat-value">{{ number_format($stats['donations_sum'], 0, ',', ' ') }} €</div>
                    <div class="stat-delta text-success">+{{ (int)($stats['donations_count'] ?? 0) }} dons</div>
                </div>
                <div class="stat-icon gradient-green">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-title">Sponsoring Actif</div>
                    <div class="stat-value">{{ number_format($stats['sponsorships_sum'], 0, ',', ' ') }} €</div>
                    <div class="stat-delta text-success">Actifs</div>
                </div>
                <div class="stat-icon gradient-green">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-title">Événements Soutenus</div>
                    <div class="stat-value">{{ (int)$stats['events_supported'] }}</div>
                    <div class="stat-delta text-success">+{{ (int)$stats['events_supported'] }}</div>
                </div>
                <div class="stat-icon gradient-green">
                    <i class="far fa-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Rewards & Badges section removed as requested -->
        <!-- Sponsoring Demands Section -->
        <div class="col-lg-6 mb-4">
            <div class="soft-card h-100">
                <div class="soft-card-head">
                    <div class="chip gradient-green"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h5 class="mb-0">Sponsoring Demands</h5>
                </div>
                <div class="soft-card-body">
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
            <div class="soft-card h-100">
                <div class="soft-card-head">
                    <div class="chip gradient-green"><i class="fas fa-hand-holding-heart"></i></div>
                    <h5 class="mb-0">Make a Donation</h5>
                </div>
                <div class="soft-card-body">
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
            <div class="soft-card h-100">
                <div class="soft-card-head">
                    <div class="chip gradient-green"><i class="fas fa-clock"></i></div>
                    <h5 class="mb-0">Mes 5 derniers dons</h5>
                </div>
                <div class="soft-card-body">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-progress').forEach(function(el) {
            const pct = parseFloat(el.getAttribute('data-progress') || '0');
            el.style.width = Math.max(0, Math.min(100, pct)) + '%';
        });
    });
</script>
@endpush

@push('styles')
<style>
    .welcome-banner {
        position: relative;
        background: linear-gradient(135deg, #0b5e2e 0%, #0e7a3a 35%, #15a053 100%);
        padding: 40px 30px;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        overflow: hidden;
        color: #fff;
    }

    /* inner layout for image + text */
    .banner-inner {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 28px;
        width: 100%;
    }

    .banner-graphic img {
        width: 220px;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 10px 18px rgba(0, 0, 0, 0.15));
    }

    .banner-content {
        position: relative;
        z-index: 2;
        color: white;
        max-width: 600px;
    }

    .banner-image-right {
        margin-left: auto;
        display: block;
        position: relative;
        z-index: 2;
        /* above shapes and waves */
        transform: translateY(6px);
    }

    .banner-image-right img {
        width: 280px;
        height: auto;
        object-fit: cover;
        /* Remove card-like feel */
        border-radius: 0;
        box-shadow: none;
        /* Soft edge blending */
        -webkit-mask-image: radial-gradient(120% 110% at 50% 50%, #000 78%, rgba(0, 0, 0, 0) 100%);
        mask-image: radial-gradient(120% 110% at 50% 50%, #000 78%, rgba(0, 0, 0, 0) 100%);
    }

    .banner-sponsor {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        letter-spacing: -1px;
    }

    .banner-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        opacity: 0.95;
    }

    .wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 80px;
        z-index: 1;
    }

    .wave-1 {
        bottom: 10px;
        animation: wave 15s linear infinite;
    }

    .wave-2 {
        bottom: 0;
        animation: wave 20s linear infinite reverse;
    }

    @keyframes wave {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(100%);
        }
    }

    @media (max-width: 768px) {
        .welcome-banner {
            padding: 24px 18px;
            min-height: 220px;
        }

        .banner-graphic {
            display: none;
        }

        .banner-image-right {
            display: none;
        }

        .banner-sponsor {
            font-size: 1.75rem;
        }

        .banner-title {
            font-size: 1.1rem;
        }
    }

    /* Badges hero row */
    .badges-hero {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .hero-badge {
        text-align: center;
        transition: transform .2s ease, filter .2s ease;
    }

    .hero-badge-inner {
        width: 240px;
        height: 240px;
        display: grid;
        place-items: center;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        backdrop-filter: blur(2px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        transition: transform .2s ease;
    }

    .hero-badge img {
        width: 72%;
        height: auto;
        object-fit: contain;
    }

    .hero-badge i {
        font-size: 6rem;
    }

    .hero-badge:hover {
        transform: translateY(-4px);
    }

    .hero-badge:hover .hero-badge-inner {
        transform: scale(1.06);
    }

    .badge-caption {
        color: #0b5e2e;
    }

    @media (max-width: 768px) {
        .hero-badge-inner {
            width: 180px;
            height: 180px;
        }

        .hero-badge i {
            font-size: 4rem;
        }
    }

    /* Decorative shapes */
    .shape {
        position: absolute;
        z-index: 0;
        pointer-events: none;
    }

    .shape-blob1 {
        top: -40px;
        left: -30px;
        width: 260px;
        height: 260px;
        animation: floatY 12s ease-in-out infinite;
    }

    .shape-ring {
        top: 20px;
        right: 22%;
        width: 180px;
        height: 180px;
        animation: spinSlow 40s linear infinite;
        opacity: 0.8;
    }

    .shape-dots {
        right: -20px;
        bottom: 90px;
        width: 220px;
        height: 120px;
        background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 0);
        background-size: 10px 10px;
        border-radius: 12px;
        transform: rotate(-6deg);
        opacity: 0.7;
        animation: floatX 14s ease-in-out infinite;
    }

    @keyframes floatY {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-12px);
        }
    }

    @keyframes floatX {

        0%,
        100% {
            transform: translateX(0) rotate(-6deg);
        }

        50% {
            transform: translateX(-10px) rotate(-6deg);
        }
    }

    @keyframes spinSlow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Stat cards modern style */
    .stat-cards .stat-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(20, 20, 20, 0.08);
        padding: 16px 18px;
        height: 100%;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .stat-cards .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(20, 20, 20, 0.12);
    }

    .stat-cards .stat-info {
        display: grid;
        gap: 4px;
    }

    .stat-cards .stat-title {
        color: #7a869a;
        font-size: .9rem;
        font-weight: 600;
    }

    .stat-cards .stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1c1c1c;
    }

    .stat-cards .stat-delta {
        font-size: .9rem;
        font-weight: 700;
    }

    .stat-cards .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 6px 18px rgba(177, 44, 185, 0.3);
    }

    .stat-cards .stat-icon i {
        font-size: 1.25rem;
    }

    .gradient-pink {
        background: linear-gradient(135deg, #ff3d77 0%, #a033ff 100%);
    }

    .gradient-green {
        background: linear-gradient(135deg, #15a053 0%, #0e7a3a 100%);
        box-shadow: 0 6px 18px rgba(21, 160, 83, 0.28);
    }

    /* Soft card components */
    .soft-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
    }

    .soft-card-head {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px 0 16px;
    }

    .soft-card-body {
        padding: 12px 16px 16px 16px;
    }

    .chip {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: #fff;
        flex-shrink: 0;
    }

    /* Make lists blend with soft cards */
    .soft-card .list-group .list-group-item {
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 12px !important;
        margin-bottom: 10px;
    }

    .soft-card .list-group .list-group-item:last-child {
        margin-bottom: 0;
    }
</style>
@endpush