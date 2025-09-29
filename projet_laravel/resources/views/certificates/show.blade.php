@extends('layouts.frontend')

@section('title', 'EcoEvents - Certificat ' . $certification->certificate_code)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-eco">
                    <i class="fas fa-certificate me-2"></i>
                    Certificat de Participation
                </h2>
                <div class="btn-group">
                    <a href="{{ route('user.certificates.index') }}" class="btn btn-outline-eco">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <a href="{{ route('user.certificates.download', $certification->certificate_code) }}" class="btn btn-eco">
                        <i class="fas fa-download me-2"></i>Télécharger PDF
                    </a>
                </div>
            </div>

            <!-- Certificat principal -->
            <div class="card certificate-main shadow-lg">
                <div class="card-header bg-eco text-white text-center">
                    <h3 class="mb-0">
                        <i class="fas fa-leaf me-2"></i>
                        EcoEvents - Certificat de Participation
                    </h3>
                </div>
                
                <div class="card-body text-center py-5">
                    <!-- Logo et titre -->
                    <div class="mb-4">
                        <i class="fas fa-certificate fa-4x text-eco mb-3"></i>
                        <h4 class="text-eco">Certificat de Participation</h4>
                        <p class="text-muted">Ce certificat atteste la participation à l'événement suivant :</p>
                    </div>

                    <!-- Détails de l'événement -->
                    <div class="event-details mb-4">
                        <h5 class="text-eco">{{ $certification->reservation->event->title }}</h5>
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <i class="fas fa-calendar text-eco me-2"></i>
                                <strong>{{ $certification->reservation->event->date->format('d/m/Y à H:i') }}</strong>
                            </div>
                            <div class="col-md-4">
                                <i class="fas fa-map-marker-alt text-eco me-2"></i>
                                <strong>{{ $certification->reservation->event->location }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Informations du participant -->
                    <div class="participant-info mb-4">
                        <div class="border-top border-bottom py-3">
                            <h5 class="text-eco">Décerné à</h5>
                            <h4 class="mb-0">{{ $certification->reservation->user->name }}</h4>
                            <p class="text-muted mb-0">{{ $certification->reservation->user->email }}</p>
                        </div>
                    </div>

                    <!-- Points et date -->
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="points-earned">
                                <h3 class="text-eco mb-1">{{ $certification->points_earned }}</h3>
                                <small class="text-muted">Points gagnés</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="date-awarded">
                                <h6 class="text-eco mb-1">{{ $certification->date_awarded->format('d/m/Y') }}</h6>
                                <small class="text-muted">Date d'obtention</small>
                            </div>
                        </div>
                    </div>

                    <!-- Code de vérification -->
                    <div class="verification-code mt-4">
                        <small class="text-muted">Code de vérification :</small>
                        <div class="h6 text-eco font-monospace">{{ $certification->certificate_code }}</div>
                    </div>
                </div>

                <div class="card-footer bg-light text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-user-tie me-1"></i>
                                Délivré par {{ $certification->issuedBy->name }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Certificat vérifiable en ligne
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions supplémentaires -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-share-alt fa-2x text-eco mb-3"></i>
                            <h6>Partager votre certificat</h6>
                            <p class="text-muted small">Partagez votre réussite sur les réseaux sociaux</p>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm" onclick="shareOnLinkedIn()">
                                    <i class="fab fa-linkedin"></i> LinkedIn
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="shareOnTwitter()">
                                    <i class="fab fa-twitter"></i> Twitter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-qrcode fa-2x text-eco mb-3"></i>
                            <h6>Vérification rapide</h6>
                            <p class="text-muted small">Scannez le QR code pour vérifier ce certificat</p>
                            <a href="{{ route('certificates.verify.code', $certification->certificate_code) }}" 
                               class="btn btn-outline-eco btn-sm" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Vérifier en ligne
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
.certificate-main {
    border: 3px solid #2d5a27;
}
.event-details h5 {
    font-weight: 600;
    margin-bottom: 1rem;
}
.participant-info {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
}
.points-earned, .date-awarded {
    text-align: center;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
}
.verification-code {
    background-color: #e8f5e8;
    border-radius: 8px;
    padding: 1rem;
    border: 2px dashed #2d5a27;
}
</style>
@endpush

@push('scripts')
<script>
function shareOnLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('Mon certificat EcoEvents - ' + '{{ $certification->reservation->event->title }}');
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('Je viens d\'obtenir mon certificat de participation EcoEvents ! 🌱');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
}
</script>
@endpush
@endsection
