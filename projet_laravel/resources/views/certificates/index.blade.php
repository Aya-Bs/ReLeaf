@extends('layouts.frontend')

@section('title', 'EcoEvents - Mes Certifications')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-eco">
                    <i class="fas fa-certificate me-2"></i>
                    Mes Certifications
                </h2>
                <div class="btn-group">
                    <a href="{{ route('home') }}" class="btn btn-outline-eco">
                        <i class="fas fa-home me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </div>

            @if($certifications->count() > 0)
                <div class="row">
                    @foreach($certifications as $certification)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 shadow-sm certificate-card">
                                <div class="card-header bg-eco text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-medal me-2"></i>
                                            Certificat de Participation
                                        </h5>
                                        <span class="badge bg-light text-eco">{{ $certification->certificate_code }}</span>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <div class="certificate-info mb-3">
                                        <h6 class="text-eco">{{ $certification->reservation->event->title }}</h6>
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $certification->reservation->event->date->format('d/m/Y') }}
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $certification->reservation->event->date->format('H:i') }}
                                            </div>
                                            <div class="col-12 mt-1">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $certification->reservation->event->location->name ?? 'Lieu non défini' }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="certificate-details mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Points gagnés</small>
                                                <div class="h5 text-eco mb-0">{{ $certification->points_earned }}</div>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Date d'obtention</small>
                                                <div class="h6 text-eco mb-0">{{ $certification->date_awarded->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="certificate-actions mt-auto">
                                        <div class="btn-group w-100">
                                            <a href="{{ route('user.certificates.show', $certification->certificate_code) }}" 
                                               class="btn btn-outline-eco">
                                                <i class="fas fa-eye me-2"></i>Voir le certificat
                                            </a>
                                            <a href="{{ route('user.certificates.download', $certification->certificate_code) }}" 
                                               class="btn btn-eco">
                                                <i class="fas fa-download me-2"></i>Télécharger PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-user-tie me-1"></i>
                                            Délivré par {{ $certification->issuedBy->name }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            Certificat vérifiable
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Informations sur la vérification -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>
                            À propos de vos certifications
                        </h6>
                        <ul class="mb-0">
                            <li>✅ Chaque certificat est <strong>unique</strong> et <strong>vérifiable</strong></li>
                            <li>📄 Vous pouvez <strong>télécharger</strong> vos certificats en format PDF</li>
                            <li>🎯 Les <strong>points gagnés</strong> sont calculés selon la participation</li>
                            <li>🔒 Vos certificats sont <strong>sécurisés</strong> et liés à votre compte</li>
                        </ul>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucune certification trouvée</h4>
                    <p class="text-muted">Vous n'avez pas encore de certifications. Participez à des événements pour en obtenir !</p>
                    <a href="{{ route('events.index') }}" class="btn btn-eco">
                        <i class="fas fa-calendar-alt me-2"></i>Découvrir les événements
                    </a>
                </div>
            @endif
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
.btn-outline-eco {
    border-color: #2d5a27;
    color: #2d5a27;
}
.btn-outline-eco:hover {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.certificate-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.certificate-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>
@endpush
@endsection
