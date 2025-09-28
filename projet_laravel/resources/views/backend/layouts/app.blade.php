<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'EcoEvents') }} - @yield('title', 'Administration')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --eco-green: #2d5a27;
            --eco-light-green: #4a7c59;
            --eco-accent: #8bc34a;
            --eco-dark: #1b3a17;
        }
        
        .main-header .navbar-nav .nav-link {
            color: rgba(255,255,255,.8);
        }
        
        .main-header .navbar-nav .nav-link:hover {
            color: #fff;
        }
        
        .navbar-eco {
            background: linear-gradient(135deg, var(--eco-green) 0%, var(--eco-light-green) 100%);
        }
        
        .sidebar-eco {
            background-color: var(--eco-dark);
        }
        
        .sidebar-eco .nav-sidebar .nav-item .nav-link {
            color: rgba(255,255,255,.8);
        }
        
        .sidebar-eco .nav-sidebar .nav-item .nav-link:hover {
            background-color: rgba(255,255,255,.1);
            color: #fff;
        }
        
        .sidebar-eco .nav-sidebar .nav-item .nav-link.active {
            background-color: var(--eco-accent);
            color: var(--eco-dark);
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
        
        .card-eco {
            border-top: 3px solid var(--eco-accent);
        }
        
        .text-eco {
            color: var(--eco-green) !important;
        }
        
        .bg-eco {
            background-color: var(--eco-green) !important;
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-eco">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fas fa-home me-1"></i>Site Web
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i>Mon Profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-eco">
            <!-- Brand Logo -->
            <a href="{{ route('backend.dashboard') }}" class="brand-link">
                <i class="fas fa-leaf brand-image img-circle elevation-3 ml-3 mr-2" style="color: var(--eco-accent);"></i>
                <span class="brand-text font-weight-light">EcoEvents Admin</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('backend.dashboard') }}" class="nav-link {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Tableau de bord</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">GESTION</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('backend.users.index') }}" class="nav-link {{ request()->routeIs('backend.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Utilisateurs</p>
                            </a>
                        </li>
                        
                                                <li class="nav-item">
    <a href="{{ route('backend.events.index') }}" class="nav-link {{ request()->routeIs('backend.events.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-alt"></i>
        <p>Événements</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('backend.campaigns.index') }}" class="nav-link {{ request()->routeIs('backend.campaigns.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-seedling"></i>
        <p>Campaigns</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('backend.resources.index') }}" class="nav-link {{ request()->routeIs('backend.resources.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-boxes"></i>
        <p>Resources</p>
    </a>
</li>
                        
                        <li class="nav-header">STATISTIQUES</li>
                        
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Rapports
                                    <span class="badge badge-warning right">Bientôt</span>
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Administration')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('home') }}">EcoEvents</a>.</strong>
            Tous droits réservés.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @stack('scripts')
</body>
</html>
