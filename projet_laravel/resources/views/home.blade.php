@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <!-- Message de bienvenue -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="display-4 text-eco">Bienvenue sur EcoEvents</h1>
            <p class="lead text-muted">
                Découvrez et participez à des événements écologiques qui font la différence.
            </p>
        </div>
    </div>

    <!-- Cartes d'actions rapides -->
    <div class="row g-4">
        <!-- Événements à venir -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-alt text-eco me-2"></i>
                        Événements à venir
                    </h5>
                    <p class="card-text">
                        Découvrez les prochains événements écologiques près de chez vous.
                    </p>
                    <a href="{{ route('events.index') }}" class="btn btn-eco">
                        <i class="fas fa-search me-2"></i>Explorer les événements
                    </a>
                </div>
            </div>
        </div>

        <!-- Assistant IA -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm chatbot-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-robot text-info me-2"></i>
                        Assistant IA
                    </h5>
                    <p class="card-text">
                        Posez vos questions à notre assistant intelligent disponible 24/7.
                    </p>
                    <a href="{{ route('chatbot.index') }}" class="btn btn-info">
                        <i class="fas fa-comments me-2"></i>Chatter avec l'IA
                    </a>
                </div>
                <div class="card-footer bg-info bg-opacity-10">
                    <small class="text-info">
                        <i class="fas fa-clock me-1"></i>Disponible 24/7
                    </small>
                </div>
            </div>
        </div>

        <!-- Créer un événement -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-plus-circle text-eco me-2"></i>
                        Organiser un événement
                    </h5>
                    <p class="card-text">
                        Créez votre propre événement et contribuez à un avenir plus vert.
                    </p>
                    <a href="#" class="btn btn-eco">
                        <i class="fas fa-plus me-2"></i>Créer un événement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="text-center">
                <h3 class="text-eco">0</h3>
                <p class="text-muted">Événements créés</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <h3 class="text-eco">0</h3>
                <p class="text-muted">Participants</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <h3 class="text-eco">0</h3>
                <p class="text-muted">Impact écologique</p>
            </div>
        </div>
    </div>

    <!-- Section Assistant IA -->
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="assistant-ia-section bg-gradient text-white rounded-4 p-5 text-center">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-3">
                            <i class="fas fa-robot me-3"></i>
                            Assistant IA EcoEvents
                        </h2>
                        <p class="lead mb-4">
                            Votre compagnon intelligent pour découvrir les événements, obtenir de l'aide 
                            et répondre à toutes vos questions sur notre plateforme.
                        </p>
                        <div class="features-list mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="fas fa-clock fa-2x mb-2"></i>
                                        <h6>Disponible 24/7</h6>
                                        <small>Support continu</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="fas fa-globe fa-2x mb-2"></i>
                                        <h6>Multilingue</h6>
                                        <small>FR • EN • AR</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-item">
                                        <i class="fas fa-brain fa-2x mb-2"></i>
                                        <h6>Intelligence</h6>
                                        <small>Réponses contextuelles</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="assistant-preview">
                            <div class="chat-preview bg-white rounded-3 p-3 text-dark">
                                <div class="message-preview user-message mb-2">
                                    <small class="text-muted">Vous:</small>
                                    <div class="message-content">Bonjour !</div>
                                </div>
                                <div class="message-preview bot-message">
                                    <small class="text-muted">Assistant:</small>
                                    <div class="message-content">Bonjour ! 👋 Comment puis-je vous aider ?</div>
                                </div>
                            </div>
                            <a href="{{ route('chatbot.index') }}" class="btn btn-light btn-lg mt-3">
                                <i class="fas fa-comments me-2"></i>Commencer la conversation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.btn-eco {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.btn-eco:hover {
    background-color: #234420;
    border-color: #234420;
    color: white;
}
.text-eco {
    color: #2d5a27;
}

/* Styles pour la carte chatbot */
.chatbot-card {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.chatbot-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(13, 202, 240, 0.15) !important;
}

.chatbot-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0dcaf0, #17a2b8, #0dcaf0);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

.chatbot-card .card-title i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.chatbot-card .btn-info {
    background: linear-gradient(135deg, #0dcaf0, #17a2b8);
    border: none;
    transition: all 0.3s ease;
}

.chatbot-card .btn-info:hover {
    background: linear-gradient(135deg, #17a2b8, #0dcaf0);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(13, 202, 240, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .chatbot-card {
        margin-bottom: 1rem;
    }
}

/* Section Assistant IA */
.assistant-ia-section {
    background: linear-gradient(135deg, #0dcaf0, #17a2b8, #2d5a27);
    position: relative;
    overflow: hidden;
}

.assistant-ia-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.feature-item {
    padding: 1rem;
    transition: transform 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
}

.feature-item i {
    color: rgba(255, 255, 255, 0.9);
}

.feature-item h6 {
    color: white;
    font-weight: 600;
}

.feature-item small {
    color: rgba(255, 255, 255, 0.8);
}

.chat-preview {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.1);
}

.message-preview .message-content {
    background: #f8f9fa;
    padding: 0.5rem 0.75rem;
    border-radius: 12px;
    margin-top: 0.25rem;
    font-size: 0.9rem;
}

.user-message .message-content {
    background: #2d5a27;
    color: white;
    margin-left: auto;
    max-width: 80%;
}

.bot-message .message-content {
    background: #e9ecef;
    color: #333;
    max-width: 80%;
}

.assistant-preview {
    position: relative;
}

.assistant-preview .btn-light {
    background: rgba(255, 255, 255, 0.95);
    border: none;
    color: #2d5a27;
    font-weight: 600;
    transition: all 0.3s ease;
}

.assistant-preview .btn-light:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Animation pour les icônes */
.assistant-ia-section h2 i {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Responsive pour la section IA */
@media (max-width: 768px) {
    .assistant-ia-section {
        padding: 2rem 1rem !important;
    }
    
    .assistant-ia-section h2 {
        font-size: 1.5rem;
    }
    
    .feature-item {
        padding: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .chat-preview {
        margin-top: 1rem;
    }
}
</style>
@endpush
@endsection
