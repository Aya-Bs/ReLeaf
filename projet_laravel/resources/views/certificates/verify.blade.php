@extends('layouts.frontend')

@section('title', 'EcoEvents - Vérification de Certificat')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h2 class="text-eco">
                    <i class="fas fa-shield-alt me-2"></i>
                    Vérification de Certificat
                </h2>
                <p class="text-muted">Vérifiez l'authenticité d'un certificat EcoEvents</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Comment utiliser :</strong> Entrez le code de vérification reçu par email (ex: ECO-XVFCS5IH) ou utilisez directement l'URL complète.
                </div>
            </div>

            @if($message)
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <h5>{{ $message }}</h5>
                    <p class="mb-0">Vérifiez le code de certificat et réessayez.</p>
                </div>
            @endif

            @if($certification)
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Certificat Vérifié et Authentique
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-eco">Détails du Certificat</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Code de Certificat :</strong></td>
                                        <td><span class="badge bg-eco">{{ $certification->certificate_code }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type :</strong></td>
                                        <td>{{ ucfirst($certification->type) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Points Gagnés :</strong></td>
                                        <td><span class="badge bg-warning">{{ $certification->points_earned }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date d'Obtention :</strong></td>
                                        <td>{{ $certification->date_awarded->format('d/m/Y à H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="text-eco">Détails du Participant</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nom :</strong></td>
                                        <td>{{ $certification->reservation->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email :</strong></td>
                                        <td>{{ $certification->reservation->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Place :</strong></td>
                                        <td><span class="badge bg-info">{{ $certification->reservation->seat_number }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statut :</strong></td>
                                        <td><span class="badge bg-success">Confirmée</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-eco">Détails de l'Événement</h6>
                        <div class="event-card">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="text-eco">{{ $certification->reservation->event->title }}</h5>
                                    <div class="event-meta">
                                        <div class="mb-2">
                                            <i class="fas fa-calendar text-eco me-1"></i>
                                            <strong>{{ $certification->reservation->event->date->format('d/m/Y à H:i') }}</strong>
                                        </div>
                                        <div>
                                            <i class="fas fa-map-marker-alt text-eco me-1"></i>
                                            <span class="fw-bold">{{ $certification->reservation->event->location->name ?? 'Lieu non défini' }}</span>
                                            @if($certification->reservation->event->location && $certification->reservation->event->location->address)
                                                <br><small class="text-muted ms-3">{{ $certification->reservation->event->location->address }}, {{ $certification->reservation->event->location->city ?? '' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="organizer-info">
                                        <small class="text-muted">Organisé par</small>
                                        <div class="fw-bold">{{ $certification->reservation->event->user->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-eco">Détails de l'Émission</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Délivré par :</strong> {{ $certification->issuedBy->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Date d'émission :</strong> {{ $certification->date_awarded->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light text-center">
                        <div class="row">
                            <div class="col-md-3">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                <small>Certificat Authentique</small>
                            </div>
                            <div class="col-md-3">
                                <i class="fas fa-lock text-success me-2"></i>
                                <small>Vérification Sécurisée</small>
                            </div>
                            <div class="col-md-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small>Données Validées</small>
                            </div>
                            <div class="col-md-3">
                                @auth
                                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-eco">
                                        <i class="fas fa-user me-1"></i>Mon Profil
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5>Vérifier un Certificat</h5>
                        <p class="text-muted">Entrez le code de certificat pour vérifier son authenticité</p>
                        
                        <form method="GET" action="{{ route('certificates.verify') }}" class="mt-4">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control form-control-lg" 
                                               name="code" 
                                               placeholder="Code du certificat (ex: ECO-XXXXXXXX)"
                                               value="{{ request('code') }}"
                                               required>
                                        <button class="btn btn-eco btn-lg" type="submit">
                                            <i class="fas fa-search me-2"></i>Vérifier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Le code de certificat se trouve en bas du certificat PDF
                            </small>
                        </div>
                        
                        @auth
                            <div class="mt-3">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Retour au Profil
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            @endif

            <!-- Informations sur la vérification -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        À propos de la vérification
                    </h6>
                    <ul class="mb-0">
                        <li>✅ Tous les certificats EcoEvents sont <strong>uniques</strong> et <strong>vérifiables</strong></li>
                        <li>🔒 La vérification se fait en <strong>temps réel</strong> depuis notre base de données</li>
                        <li>🛡️ Système de sécurité pour <strong>prévenir les falsifications</strong></li>
                        <li>📄 Chaque certificat contient un <strong>code unique</strong> impossible à reproduire</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-eco {
    background-color: #2d5a27;
}
.text-eco {
    color: #2d5a27;
}
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
.event-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border-left: 4px solid #2d5a27;
}
.event-meta {
    color: #666;
    font-size: 14px;
}
</style>
@endpush
@endsection
