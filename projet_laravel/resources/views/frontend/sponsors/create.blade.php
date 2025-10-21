@extends('layouts.frontend')

@section('title', 'Devenir Sponsor - EcoEvents')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">
                    <span class="text-success">Devenir Sponsor</span>
                </h1>
                <p class="lead mb-4">
                    Rejoignez notre mission écologique en soutenant nos événements.
                    Votre entreprise peut faire la différence pour un avenir plus durable.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="form-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-handshake me-2"></i>
                            Demande de Partenariat
                        </h3>
                        <p class="mb-0 mt-2">Remplissez le formulaire ci-dessous pour devenir sponsor</p>
                    </div>
                    <div class="card-body p-5">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('sponsors.store') }}" method="POST" novalidate>
                            @csrf

                            <!-- Company Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-success mb-3">
                                        <i class="fas fa-building me-2"></i>Informations de l'entreprise
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label">Nom de l'entreprise *</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name"
                                        value="{{ old('company_name') }}">
                                    @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_email" class="form-label">Email de contact *</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                        id="contact_email" name="contact_email"
                                        value="{{ old('contact_email') }}">
                                    @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="contact_phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror"
                                        id="contact_phone" name="contact_phone"
                                        value="{{ old('contact_phone') }}">
                                    @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="website" class="form-label">Site web</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror"
                                        id="website" name="website"
                                        value="{{ old('website') }}" placeholder="https://example.com">
                                    @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-success mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i>Adresse
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Ville</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city"
                                        value="{{ old('city') }}">
                                    @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Pays</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country"
                                        value="{{ old('country') }}">
                                    @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sponsorship Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-success mb-3">
                                        <i class="fas fa-gift me-2"></i>Type de sponsoring
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="sponsorship_type" class="form-label">Type de sponsoring *</label>
                                    <select class="form-select @error('sponsorship_type') is-invalid @enderror"
                                        id="sponsorship_type" name="sponsorship_type">
                                        <option value="">Sélectionnez un type</option>
                                        <option value="argent" {{ old('sponsorship_type') == 'argent' ? 'selected' : '' }}>
                                            Sponsoring financier
                                        </option>
                                        <option value="materiel" {{ old('sponsorship_type') == 'materiel' ? 'selected' : '' }}>
                                            Sponsoring matériel
                                        </option>
                                        <option value="service" {{ old('sponsorship_type') == 'service' ? 'selected' : '' }}>
                                            Sponsoring service
                                        </option>
                                    </select>
                                    @error('sponsorship_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Motivation -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-success mb-3">
                                        <i class="fas fa-heart me-2"></i>Motivation du partenariat
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="motivation" class="form-label">Pourquoi souhaitez-vous devenir sponsor ? *</label>
                                    <textarea class="form-control @error('motivation') is-invalid @enderror"
                                        id="motivation" name="motivation" rows="4"
                                        placeholder="Expliquez votre motivation pour soutenir nos événements écologiques...">{{ old('motivation') }}</textarea>
                                    <div class="form-text">Minimum 50 caractères</div>
                                    @error('motivation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="additional_info" class="form-label">Informations supplémentaires</label>
                                    <textarea class="form-control @error('additional_info') is-invalid @enderror"
                                        id="additional_info" name="additional_info" rows="3"
                                        placeholder="Toute information supplémentaire qui pourrait nous aider...">{{ old('additional_info') }}</textarea>
                                    @error('additional_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Envoyer la demande
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <span class="text-success">Avantages du Sponsoring</span>
                </h2>
                <p class="lead text-muted">
                    Découvrez les bénéfices de devenir sponsor EcoEvents
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card benefit-card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="benefit-icon mb-3">
                            <i class="fas fa-eye fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Visibilité</h5>
                        <p class="card-text text-muted">
                            Augmentez votre visibilité auprès d'une communauté engagée pour l'environnement
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card benefit-card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="benefit-icon mb-3">
                            <i class="fas fa-leaf fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Impact Positif</h5>
                        <p class="card-text text-muted">
                            Contribuez directement à des actions concrètes pour l'environnement
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card benefit-card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="benefit-icon mb-3">
                            <i class="fas fa-users fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Réseau</h5>
                        <p class="card-text text-muted">
                            Rejoignez un réseau d'entreprises partageant les mêmes valeurs
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
        min-height: 40vh;
    }

    .benefit-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
    }

    .benefit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        transition: transform 0.3s ease;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }
</style>
@endpush