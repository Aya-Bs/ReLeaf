<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EcoEvents') }} - @yield('title', 'Événements Écologiques')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --eco-green: #2d5a27;
            --eco-light-green: #4a7c59;
            --eco-accent: #8bc34a;
            --eco-dark: #1b3a17;
        }
        
        .navbar-brand {
            font-weight: 600;
            color: var(--eco-green) !important;
        }
        
        .btn-eco {
            background-color: var(--eco-green);
            border-color: var(--eco-green);
            color: white;
        }
        
        .btn-eco:hover {
            background-color: var(--eco-light-green);
            border-color: var(--eco-light-green);
            color: white;
        }
        
        .text-eco {
            color: var(--eco-green) !important;
        }
        
        .bg-eco {
            background-color: var(--eco-green) !important;
        }
        
        .footer {
            background-color: var(--eco-dark);
            color: white;
        }
        
        .eco-card {
            border-left: 4px solid var(--eco-accent);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            box-shadow: 0 8px 25px rgba(13, 202, 240, 0.4) !important;
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
            color: var(--eco-green);
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

        /* Ensure navbar is always visible */
.navbar {
    position: sticky !important;
    top: 0 !important;
    z-index: 1030 !important;
    background: white !important;
}

/* Ensure dropdowns work properly */
.dropdown-menu {
    z-index: 1040 !important;
}

/* Main content should not overlap navbar */
main {
    margin-top: 0 !important;
    position: relative;
    z-index: 1;
}

/* Body padding to account for fixed navbar */
body {
    padding-top: 0;
}
    </style>

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
   <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-leaf me-2"></i>EcoEvents
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">À propos</a>
                    </li>
                    @if(auth()->user()->role === 'organizer')
                        <!-- Menu pour les organisateurs -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-calendar-alt me-1"></i>Événements
                            </a>
                            <ul class="dropdown-menu">
                                 <li><a class="dropdown-item" href="{{ route('locations.index') }}">
                                    <i class="fas fa-home me-2"></i>Lieux
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('events.my-events') }}">
                                    <i class="fas fa-calendar-alt me-2"></i>Mes événements
                                </a></li>
                               
                            </ul>
                        </li>
                    @else
                        <!-- Menu pour les utilisateurs normaux -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('events.index') }}">
                                <i class="fas fa-calendar-alt me-1"></i>Événements
                            </a>
                        </li>
                    @endif
                    
                       @if(auth()->user()->role === 'organizer')
                       <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bullhorn me-1"></i>Campagnes
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('campaigns.index') }}">
                                <i class="fas fa-list me-2"></i>Mes campagnes
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('campaigns.create') }}">
                                <i class="fas fa-plus me-2"></i>Créer une campagne
                            </a></li>
                        </ul>
                    </li>

                        
                    @else
                        <!-- Menu pour les utilisateurs normaux -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('campaigns.index') }}">
                                <i class="fas fa-bullhorn me-2"></i>Campagnes
                            </a>
                        </li>
                    @endif
                    <!-- Campaigns Menu -->
                    
                     <!-- Resources Menu -->
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('resources.index') }}">
                             <i class="fas fa-box me-1"></i>Ressources
                         </a>
                     </li>

                     <!-- Section Volontaires -->
                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                             <i class="fas fa-hands-helping me-1"></i>Volontaires
                         </a>
                         <ul class="dropdown-menu">
                             @if(auth()->user()->isVolunteer())
                                 <!-- Si l'utilisateur est déjà volontaire -->
                                 <li><a class="dropdown-item" href="{{ route('volunteers.show', auth()->user()->volunteer) }}">
                                     <i class="fas fa-user me-2"></i>Mon Profil Volontaire
                                 </a></li>
                                 <li><a class="dropdown-item" href="{{ route('assignments.index') }}">
                                     <i class="fas fa-tasks me-2"></i>Mes Missions
                                 </a></li>
                                 <li><a class="dropdown-item" href="{{ route('volunteers.available-missions') }}">
                                     <i class="fas fa-search me-2"></i>Missions Disponibles
                                 </a></li>
                                 <li><a class="dropdown-item" href="{{ route('volunteers.index') }}">
                                     <i class="fas fa-users me-2"></i>Tous les Volontaires
                                 </a></li>
                                 <li><hr class="dropdown-divider"></li>
                                 <li><a class="dropdown-item" href="{{ route('volunteers.edit', auth()->user()->volunteer) }}">
                                     <i class="fas fa-edit me-2"></i>Modifier mon profil
                                 </a></li>
                             @else
                                 <!-- Si l'utilisateur n'est pas encore volontaire -->
                                 <li><a class="dropdown-item" href="{{ route('volunteers.create') }}">
                                     <i class="fas fa-plus me-2"></i>Devenir Volontaire
                                 </a></li>
                                 <li><a class="dropdown-item" href="{{ route('volunteers.index') }}">
                                     <i class="fas fa-users me-2"></i>Voir les Volontaires
                                 </a></li>
                             @endif
                         </ul>
                     </li>

                     <!-- Chatbot Menu -->
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('chatbot.index') }}">
                             <i class="fas fa-robot me-1"></i>Assistant IA
                         </a>
                     </li>

                @endauth
            </ul>
            
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle me-1" width="24" height="24">
                            @else
                                <i class="fas fa-user-circle me-1"></i>
                            @endif
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-eye me-2"></i>Voir le profil
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit.extended') }}">
                                <i class="fas fa-edit me-2"></i>Modifier le profil
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('user.certificates.index') }}">
                                <i class="fas fa-certificate me-2"></i>Mes certificats
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('donations.list') }}">
                                <i class="fas fa-donate me-2"></i>Mes dons
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Admin Link (if user is admin) -->
                            @if(auth()->user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('backend.dashboard') }}">
                                <i class="fas fa-cog me-2"></i>Administration
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            @endif
                            
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
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
        <a href="{{ route('chatbot.index') }}" class="btn btn-info btn-lg rounded-circle shadow-lg" title="Assistant IA EcoEvents">
            <i class="fas fa-robot"></i>
        </a>
        <div class="chatbot-tooltip">
            <div class="tooltip-content">
                <strong>Assistant IA</strong><br>
                <small>Disponible 24/7</small>
            </div>
        </div>
    </div>

     <!-- Bouton Chatbot Flottant -->
     <div class="chatbot-float-btn">
         <a href="{{ route('chatbot.index') }}" class="btn btn-info btn-lg rounded-circle shadow-lg" title="Assistant IA EcoEvents">
             <i class="fas fa-robot"></i>
         </a>
         <div class="chatbot-tooltip">
             <div class="tooltip-content">
                 <strong>Assistant IA</strong><br>
                 <small>Disponible 24/7</small>
             </div>
         </div>
     </div>

    <!-- Main Content -->
    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-leaf me-2"></i>EcoEvents</h5>
                    <p class="mb-0">Ensemble pour un avenir plus vert</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} EcoEvents. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>