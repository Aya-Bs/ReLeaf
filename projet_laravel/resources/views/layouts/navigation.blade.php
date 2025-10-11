<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/eco-logo.png') }}" alt="EcoEvents" height="40">
        </a>

        <!-- Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>

                @auth
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="fas fa-calendar-alt me-1"></i>Événements
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}" href="{{ route('admin.reservations.index') }}">
                        <i class="fas fa-ticket-alt me-1"></i>Réservations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('backend.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Administration
                    </a>
                </li>
                @elseif(auth()->user()->role === 'sponsor')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sponsor.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Mon Dashboard
                    </a>
                </li>
                @endif

                <!-- Lien Volontaire pour tous les utilisateurs connectés -->
                @if(auth()->user()->isVolunteer())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('volunteers.*') ? 'active' : '' }}" href="{{ route('volunteers.show', auth()->user()->volunteer->id) }}">
                        <i class="fas fa-hands-helping me-1"></i>Volontaire
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('volunteers.create') ? 'active' : '' }}" href="{{ route('volunteers.create') }}">
                        <i class="fas fa-user-plus me-1"></i>Devenir volontaire
                    </a>
                </li>
                @endif
                @endauth
            </ul>

            <!-- Right Side -->
            <ul class="navbar-nav ms-auto">
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Inscription
                    </a>
                </li>
                @else
                <!-- Notifications -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger">0</span>
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle me-1" width="24">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i>Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('user.certificates.index') }}">
                                <i class="fas fa-certificate me-2"></i>Mes Certifications
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('chatbot.index') }}">
                                <i class="fas fa-robot me-2"></i>Assistant IA
                            </a>
                        </li>
                        @if(auth()->user()->role === 'sponsor' && auth()->user()->sponsor)
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-building me-2"></i>Profil sponsor
                            </a>
                        </li>
                        @if(auth()->user()->sponsor->isDeletionRequested())
                        <li>
                            <span class="dropdown-item text-warning">
                                <i class="fas fa-clock me-2"></i>Suppression demandée
                            </span>
                        </li>
                        @else
                        <li>
                            <form method="POST" action="{{ route('sponsor.self.requestDeletion') }}" onsubmit="return confirm('Confirmer la demande de suppression de votre compte sponsor ?');">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-user-slash me-2"></i>Demande suppression sponsor
                                </button>
                            </form>
                        </li>
                        @endif
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-cog me-2"></i>Paramètres
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Bouton Chatbot Flottant -->
<div class="chatbot-float-btn">
    <a href="{{ route('chatbot.index') }}" class="btn btn-eco btn-lg rounded-circle shadow-lg" title="Assistant IA EcoEvents">
        <i class="fas fa-robot"></i>
    </a>
    <div class="chatbot-tooltip">
        <div class="tooltip-content">
            <strong>Assistant IA</strong><br>
            <small>Disponible 24/7</small>
        </div>
    </div>
</div>

@push('styles')
<style>
.navbar {
    box-shadow: 0 2px 4px rgba(0,0,0,.04);
}

.nav-link.active {
    color: #2d5a27 !important;
    font-weight: 500;
}

.dropdown-item:active {
    background-color: #2d5a27;
}

/* Bouton Chatbot Flottant */
.chatbot-float-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    animation: float 3s ease-in-out infinite;
}

.chatbot-float-btn .btn {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: all 0.3s ease;
    border: none;
}

.chatbot-float-btn .btn:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(45, 90, 39, 0.4) !important;
}

.chatbot-tooltip {
    position: absolute;
    bottom: 70px;
    right: 0;
    background: white;
    padding: 10px 15px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    pointer-events: none;
    white-space: nowrap;
}

.chatbot-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    right: 20px;
    border: 8px solid transparent;
    border-top-color: white;
}

.chatbot-float-btn:hover .chatbot-tooltip {
    opacity: 1;
    transform: translateY(0);
}

.tooltip-content {
    text-align: center;
    color: #2d5a27;
}

.tooltip-content strong {
    font-size: 0.9rem;
}

.tooltip-content small {
    font-size: 0.75rem;
    color: #6c757d;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .chatbot-float-btn {
        bottom: 20px;
        right: 20px;
    }
    
    .chatbot-float-btn .btn {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .chatbot-tooltip {
        bottom: 60px;
        right: -10px;
    }
}
</style>
@endpush