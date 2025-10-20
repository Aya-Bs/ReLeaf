@extends('backend.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tableau de bord</li>
@endsection

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\User::count() }}</h3>
                <p>Utilisateurs inscrits</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('backend.users.index') }}" class="small-box-footer">
                Plus d'infos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \App\Models\User::whereHas('profile', function($q) { $q->where('is_eco_ambassador', true); })->count() }}</h3>
                <p>Ambassadeurs écologiques</p>
            </div>
            <div class="icon">
                <i class="fas fa-leaf"></i>
            </div>
            <a href="{{ route('backend.users.index', ['eco_ambassador' => 1]) }}" class="small-box-footer">
                Plus d'infos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Models\Event::count() }}</h3>
                <p>Événements créés</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="#" class="small-box-footer">
                Bientôt disponible <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['total_volunteers'] }}</h3>
                <p>Volontaires</p>
            </div>
            <div class="icon">
                <i class="fas fa-hands-helping"></i>
            </div>
            <a href="{{ route('backend.volunteers.index') }}" class="small-box-footer">
                Plus d'infos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Deuxième ligne de statistiques -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['active_volunteers'] }}</h3>
                <p>Volontaires actifs</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="{{ route('backend.volunteers.index') }}" class="small-box-footer">
                Plus d'infos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['total_assignments'] }}</h3>
                <p>Missions totales</p>
            </div>
            <div class="icon">
                <i class="fas fa-tasks"></i>
            </div>
            <a href="{{ route('backend.assignments.index') }}" class="small-box-footer">
                Plus d'infos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\Campaign::count() }}</h3>
                <p>Campagnes</p>
            </div>
            <div class="icon">
                <i class="fas fa-leaf"></i>
            </div>
            <a href="{{ route('backend.campaigns.index') }}" class="small-box-footer">
                Voir les campagnes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>0</h3>
                <p>Événements créés</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <a href="#" class="small-box-footer">
                Bientôt disponible <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Main row -->
<div class="row">
    <!-- Graphique des inscriptions -->
    <div class="col-md-8">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Évolution des inscriptions
                </h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="inscriptionsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers utilisateurs -->
    <div class="col-md-4">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-plus mr-1"></i>
                    Derniers inscrits
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="users-list clearfix">
                    @foreach($stats['recent_users'] as $user)
                        <li>
                            <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" alt="Avatar" class="img-circle">
                            <a class="users-list-name" href="{{ route('backend.users.show', $user) }}">
                                {{ $user->name }}
                            </a>
                            <span class="users-list-date">{{ $user->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('backend.users.index') }}">Voir tous les utilisateurs</a>
            </div>
        </div>
    </div>
</div>

<!-- Section Volontaires -->
<div class="row">
    <!-- Derniers volontaires -->
    <div class="col-md-6">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-hands-helping mr-1"></i>
                    Derniers volontaires
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="users-list clearfix">
                    @foreach($stats['recent_volunteers'] as $volunteer)
                        <li>
                            <img src="{{ $volunteer->user->avatar_url ?? asset('images/default-avatar.png') }}" alt="Avatar" class="img-circle">
                            <a class="users-list-name" href="{{ route('volunteers.show', $volunteer) }}">
                                {{ $volunteer->user->name }}
                            </a>
                            <span class="users-list-date">
                                <span class="badge badge-{{ $volunteer->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($volunteer->status) }}
                                </span>
                                <br>{{ $volunteer->created_at->diffForHumans() }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('backend.volunteers.index') }}">Voir tous les volontaires</a>
            </div>
        </div>
    </div>

    <!-- Statistiques des missions -->
    <div class="col-md-6">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks mr-1"></i>
                    Statistiques des missions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-check"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Approuvées</span>
                                <span class="info-box-number">{{ \App\Models\Assignment::where('status', 'approved')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">En attente</span>
                                <span class="info-box-number">{{ \App\Models\Assignment::where('status', 'pending')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Terminées</span>
                                <span class="info-box-number">{{ \App\Models\Assignment::where('status', 'completed')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger">
                                <i class="fas fa-times"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Annulées</span>
                                <span class="info-box-number">{{ \App\Models\Assignment::where('status', 'cancelled')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('backend.assignments.index') }}">Voir toutes les missions</a>
            </div>
        </div>
    </div>
</div>

<!-- Activité récente -->
<div class="row">
    <div class="col-12">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Activité récente
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach(\App\Models\User::with('profile')->latest()->take(5)->get() as $user)
                        <div class="time-label">
                            <span class="bg-green">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-user bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-clock"></i> {{ $user->created_at->format('H:i') }}
                                </span>
                                <h3 class="timeline-header">
                                    <a href="{{ route('backend.users.show', $user) }}">{{ $user->name }}</a>
                                    s'est inscrit sur la plateforme
                                </h3>
                                <div class="timeline-body">
                                    Email: {{ $user->email }}
                                    @if($user->profile && $user->profile->city)
                                        <br>Ville: {{ $user->profile->city }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des inscriptions
const ctx = document.getElementById('inscriptionsChart').getContext('2d');
const inscriptionsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
        datasets: [{
            label: 'Inscriptions',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: '#2d5a27',
            backgroundColor: 'rgba(45, 90, 39, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
