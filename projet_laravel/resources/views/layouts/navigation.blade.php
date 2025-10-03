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
                    <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-calendar-alt me-1"></i>Événements
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
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
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-cog me-2"></i>Paramètres
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
                            <hr class="dropdown-divider">
                        </li>
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

@push('styles')
<style>
    .navbar {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .04);
    }

    .nav-link.active {
        color: #2d5a27 !important;
        font-weight: 500;
    }

    .dropdown-item:active {
        background-color: #2d5a27;
    }
</style>
@endpush