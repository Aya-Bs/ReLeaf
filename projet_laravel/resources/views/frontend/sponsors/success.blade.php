@extends('layouts.frontend')

@section('title', 'Demande Envoyée - EcoEvents')

@section('content')
<!-- Success Section -->
<section class="success-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="success-card">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h1 class="display-5 fw-bold text-success mb-4">
                        Demande Envoyée !
                    </h1>
                    <p class="lead text-muted mb-4">
                        Votre demande de sponsoring a été transmise avec succès à notre équipe.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Prochaines étapes :</strong><br>
                        • Notre équipe examinera votre demande<br>
                        • Vous recevrez une réponse par email dans les 48h<br>
                        • En cas d'acceptation, nous vous contacterons pour les détails
                    </div>
                    <div class="d-flex gap-3 justify-content-center mt-4">
                        <a href="{{ route('sponsors.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-eye me-2"></i>Voir nos sponsors
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-success">
                            <i class="fas fa-home me-2"></i>Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info Section -->
<section class="contact-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title">
                    <span class="text-success">Besoin d'aide ?</span>
                </h2>
                <p class="lead text-muted">
                    Notre équipe est là pour vous accompagner
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="contact-card text-center p-4 bg-white rounded shadow-sm">
                            <i class="fas fa-envelope fa-2x text-success mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted mb-0">sponsors@ecoevents.com</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="contact-card text-center p-4 bg-white rounded shadow-sm">
                            <i class="fas fa-phone fa-2x text-success mb-3"></i>
                            <h5>Téléphone</h5>
                            <p class="text-muted mb-0">+33 1 23 45 67 89</p>
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
.success-section {
    min-height: 60vh;
    display: flex;
    align-items: center;
}

.success-card {
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.success-icon {
    animation: bounceIn 1s ease-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.section-title {
    position: relative;
    display: inline-block;
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

.contact-card {
    transition: transform 0.3s ease;
}

.contact-card:hover {
    transform: translateY(-5px);
}
</style>
@endpush

