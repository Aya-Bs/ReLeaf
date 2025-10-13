@extends('backend.layouts.app')

@section('title', 'Bienvenue - Administration EcoEvents')

@section('content')
<!-- Welcome Header -->
<section class="welcome-header bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-crown me-3"></i>Bienvenue, Administrateur !
                </h1>
                <p class="lead mb-4">
                    Vous êtes connecté au panneau d'administration d'EcoEvents.
                    Gérez la plateforme et accompagnez notre communauté écologique.
                </p>
                <div class="welcome-actions">
                    <a href="{{ route('backend.dashboard') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-tachometer-alt me-2"></i>Accéder au Dashboard
                    </a>
                    <a href="{{ route('backend.users.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-users me-2"></i>Gérer les Utilisateurs
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="welcome-icon">
                    <i class="fas fa-leaf fa-5x text-success opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="quick-stats py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="stat-card card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-user-plus fa-3x text-success"></i>
                        </div>
                        <h3 class="h2 text-primary">{{ $welcomeStats['today_users'] }}</h3>
                        <p class="text-muted mb-0">Nouveaux membres aujourd'hui</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-tasks fa-3x text-warning"></i>
                        </div>
                        <h3 class="h2 text-primary">{{ $welcomeStats['pending_tasks'] }}</h3>
                        <p class="text-muted mb-0">Tâches en attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="stat-card card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-heartbeat fa-3x text-danger"></i>
                        </div>
                        <h3 class="h3 text-success">{{ $welcomeStats['system_health'] }}</h3>
                        <p class="text-muted mb-0">État du système</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="quick-actions py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Actions Rapides</h2>
                <p class="lead text-muted">
                    Accédez rapidement aux fonctionnalités principales
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="action-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="action-icon mb-3">
                            <i class="fas fa-chart-line fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Tableau de Bord</h5>
                        <p class="card-text text-muted">
                            Consultez les statistiques et métriques de la plateforme
                        </p>
                        <a href="{{ route('backend.dashboard') }}" class="btn btn-primary">
                            Accéder
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="action-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="action-icon mb-3">
                            <i class="fas fa-users fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Gestion Utilisateurs</h5>
                        <p class="card-text text-muted">
                            Gérez les comptes et profils des membres
                        </p>
                        <a href="{{ route('backend.users.index') }}" class="btn btn-success">
                            Accéder
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="action-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="action-icon mb-3">
                            <i class="fas fa-calendar-alt fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Événements</h5>
                        <p class="card-text text-muted">
                            Supervisez les événements organisés
                        </p>
                        <a href="#" class="btn btn-info">
                            Bientôt disponible
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="action-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-4">
                        <div class="action-icon mb-3">
                            <i class="fas fa-cog fa-3x text-secondary"></i>
                        </div>
                        <h5 class="card-title">Paramètres</h5>
                        <p class="card-text text-muted">
                            Configurez les paramètres de la plateforme
                        </p>
                        <a href="#" class="btn btn-secondary">
                            Bientôt disponible
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Activity -->
<section class="recent-activity py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Activité Récente</h2>
                <p class="lead text-muted">
                    Dernières actions sur la plateforme
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="activity-timeline">
                    <div class="activity-item d-flex mb-4">
                        <div class="activity-icon me-3">
                            <i class="fas fa-user-plus bg-success text-white rounded-circle p-2"></i>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <h6 class="mb-1">Nouveau membre inscrit</h6>
                            <p class="text-muted mb-0">Un utilisateur vient de rejoindre EcoEvents</p>
                            <small class="text-muted">{{ now()->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    <div class="activity-item d-flex mb-4">
                        <div class="activity-icon me-3">
                            <i class="fas fa-leaf bg-primary text-white rounded-circle p-2"></i>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <h6 class="mb-1">Ambassadeur écologique nommé</h6>
                            <p class="text-muted mb-0">Un membre a été désigné ambassadeur</p>
                            <small class="text-muted">{{ now()->subHours(2)->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    <div class="activity-item d-flex mb-4">
                        <div class="activity-icon me-3">
                            <i class="fas fa-calendar-check bg-info text-white rounded-circle p-2"></i>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <h6 class="mb-1">Événement validé</h6>
                            <p class="text-muted mb-0">Un événement a été approuvé par l'équipe</p>
                            <small class="text-muted">{{ now()->subHours(5)->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- System Status -->
<section class="system-status py-5 bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3 class="h2 mb-4">
                    <i class="fas fa-server me-2"></i>État du Système
                </h3>
                <div class="status-item d-flex justify-content-between mb-3">
                    <span>Serveur Web</span>
                    <span class="badge bg-success">Actif</span>
                </div>
                <div class="status-item d-flex justify-content-between mb-3">
                    <span>Base de données</span>
                    <span class="badge bg-success">Connecté</span>
                </div>
                <div class="status-item d-flex justify-content-between mb-3">
                    <span>Service Email</span>
                    <span class="badge bg-success">Opérationnel</span>
                </div>
                <div class="status-item d-flex justify-content-between mb-3">
                    <span>Système de fichiers</span>
                    <span class="badge bg-success">Disponible</span>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="h2 mb-4">
                    <i class="fas fa-chart-pie me-2"></i>Utilisation des Ressources
                </h3>
                <div class="resource-usage">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>CPU</span>
                            <span>15%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 15%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Mémoire</span>
                            <span>32%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 32%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Stockage</span>
                            <span>8%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 8%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.welcome-header {
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
    min-height: 50vh;
}

.welcome-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.action-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.activity-icon {
    min-width: 40px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.section-title {
    position: relative;
    display: inline-block;
    color: #2d5a27;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: #28a745;
    border-radius: 2px;
}

.welcome-actions .btn {
    transition: all 0.3s ease;
}

.welcome-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.system-status {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%);
}

.progress {
    background-color: rgba(255,255,255,0.2);
    border-radius: 10px;
}

@media (max-width: 768px) {
    .welcome-header {
        text-align: center;
        padding: 3rem 0;
    }

    .welcome-header h1 {
        font-size: 2.5rem;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .welcome-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au survol
    const cards = document.querySelectorAll('.action-card, .stat-card');

    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Mise à jour automatique des statistiques
    function updateStats() {
        // Simulation de mise à jour des données
        const todayUsersEl = document.querySelector('.stat-card:nth-child(1) .h2');
        if (todayUsersEl) {
            todayUsersEl.textContent = Math.floor(Math.random() * 5);
        }
    }

    // Mettre à jour les stats toutes les 30 secondes
    setInterval(updateStats, 30000);
});
</script>
@endpush
