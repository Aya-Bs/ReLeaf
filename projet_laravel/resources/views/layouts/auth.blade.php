<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ReLeaf') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
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
            --eco-gradient: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fffe 0%, #e8f5e8 100%);
            min-height: 100vh;
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(45, 90, 39, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }
        
        .auth-left {
            background: var(--eco-gradient);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .auth-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="leaves" patternUnits="userSpaceOnUse" width="20" height="20"><path d="M10 2C6 2 2 6 2 10s4 8 8 8 8-4 8-8-4-8-8-8z" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23leaves)"/></svg>') repeat;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .auth-logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            z-index: 1;
            position: relative;
        }
        
        .auth-tagline {
            font-size: 1.1rem;
            opacity: 0.9;
            z-index: 1;
            position: relative;
        }
        
        .auth-right {
            padding: 3rem;
        }
        
        .auth-title {
            color: var(--eco-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .auth-subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--eco-accent);
            box-shadow: 0 0 0 0.2rem rgba(139, 195, 74, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--eco-dark);
            margin-bottom: 0.5rem;
        }
        
        .btn-eco {
            background: var(--eco-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-eco:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 90, 39, 0.3);
            color: white;
        }
        
        .btn-outline-eco {
            border: 2px solid var(--eco-green);
            color: var(--eco-green);
            background: transparent;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-eco:hover {
            background: var(--eco-green);
            color: white;
            transform: translateY(-2px);
        }
        
        .auth-link {
            color: var(--eco-green);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .auth-link:hover {
            color: var(--eco-light-green);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            color: #dc2626;
        }
        
        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a;
        }
        
        .input-group-text {
            border: 2px solid #e9ecef;
            border-right: none;
            background: #f8f9fa;
            border-radius: 12px 0 0 12px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }
        
        .input-group .form-control:focus {
            border-left: none;
        }
        
        .password-toggle {
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: var(--eco-green);
        }
        
        @media (max-width: 768px) {
            .auth-left {
                display: none;
            }
            
            .auth-right {
                padding: 2rem 1.5rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="row g-0 h-100">
                <div class="col-lg-6 auth-left">
                    <div>
                        <div class="auth-logo">
                            <i class="fas fa-leaf me-2"></i>{{ config('app.name', 'ReLeaf') }}
                        </div>
                        <p class="auth-tagline">
                            Ensemble pour un avenir plus vert.<br>
                            Rejoignez la communauté écologique qui fait la différence.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-6 auth-right">
                    @if(session('status'))
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
