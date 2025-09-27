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

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>0</h3>
                <p>Participations</p>
            </div>
            <div class="icon">
                <i class="fas fa-handshake"></i>
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
                    @foreach(\App\Models\User::with('profile')->latest()->take(8)->get() as $user)
                        <li>
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="img-circle">
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
