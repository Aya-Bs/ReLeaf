@extends('layouts.frontend')

@section('title', 'Faire un Don - ' . $event->title)

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-heart me-2 text-success"></i>
                    Soutenir cet événement
                </h1>
                <p class="lead mb-4">
                    Votre don contribue directement à la réussite de cet événement écologique.
                    Ensemble, créons un impact positif !
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Event Info Section -->
<section class="event-info-section py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">{{ $event->title }}</h4>
                                <p class="text-muted mb-2">{{ Str::limit($event->description, 150) }}</p>
                                <div class="event-meta">
                                    <small class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $event->date ? $event->date->format('d/m/Y à H:i') : 'Date à définir' }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $event->location ?? 'Lieu à définir' }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="funding-progress">
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ min(($event->total_funding / 1000) * 100, 100) }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ number_format($event->total_funding, 0, ',', ' ') }} € collectés
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donation Form Section -->
<section class="donation-form-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-donate me-2"></i>
                            Faire un Don
                        </h3>
                        <p class="mb-0 mt-2">Choisissez le type de don et remplissez le formulaire</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">

                                {{-- General Error Display Block --}}
                                @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Erreur de validation</h4>
                                    <p>Votre formulaire contient des erreurs. Veuillez vérifier les champs ci-dessous.</p>
                                    <hr>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif

                                <form action="{{ route('donations.store', $event) }}" method="POST" id="donation-form" data-has-sponsor="{{ (Auth::check() && Auth::user()->role === 'sponsor' && Auth::user()->sponsor) ? 'true' : 'false' }}">
                                    @csrf

                                    <!-- Donation Type -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-success mb-3">
                                                <i class="fas fa-gift me-2"></i>Type de don
                                            </h5>
                                        </div>
                                    </div>

                                    @php
                                    $resolvedType = 'individual';
                                    if(Auth::check() && Auth::user()->role === 'sponsor') { $resolvedType = 'sponsor'; }
                                    @endphp
                                    <input type="hidden" name="type" value="{{ $resolvedType }}">
                                    <div class="alert alert-info mb-3 py-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Type de don verrouillé: <strong>{{ $resolvedType === 'sponsor' ? 'Don de sponsor' : 'Don individuel' }}</strong>
                                    </div>
                                    <div class="row mb-4 small text-muted">
                                        <div class="col-12">
                                            <div class="form-check mb-2 opacity-75">
                                                <input class="form-check-input" type="radio" disabled {{ $resolvedType==='individual' ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Don individuel
                                                </label>
                                            </div>
                                            <div class="form-check opacity-50">
                                                <input class="form-check-input" type="radio" disabled {{ $resolvedType==='sponsor' ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Don de sponsor
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Conditional Sponsor ID / Name section --}}
                                    @if(Auth::check() && Auth::user()->role === 'sponsor' && Auth::user()->sponsor)
                                    <input type="hidden" name="sponsor_id" value="{{ Auth::user()->sponsor->id }}">
                                    @elseif(!Auth::check() || Auth::user()->role !== 'sponsor')
                                    {{-- Only non-sponsor users entering a sponsor donation need a name; keep hidden by default if individual --}}
                                    <div id="sponsor-name-field" class="row mb-4" style="display: none;">
                                        <div class="col-12">
                                            <label for="sponsor_name" class="form-label">Nom du Sponsor</label>
                                            <input type="text" class="form-control @error('sponsor_name') is-invalid @enderror"
                                                id="sponsor_name" name="sponsor_name" value="{{ old('sponsor_name') }}"
                                                placeholder="Entrez le nom de l'entreprise sponsor">
                                            @error('sponsor_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif {{-- Donor Info --}}
                                    <div id="donor-info" class="row mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label for="donor_name" class="form-label">Votre nom *</label>
                                            <input type="text" class="form-control @error('donor_name') is-invalid @enderror"
                                                id="donor_name" name="donor_name" value="{{ old('donor_name', Auth::user()->name ?? '') }}" required>
                                            @error('donor_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="donor_email" class="form-label">Votre email *</label>
                                            <input type="email" class="form-control @error('donor_email') is-invalid @enderror"
                                                id="donor_email" name="donor_email" value="{{ old('donor_email', Auth::user()->email ?? '') }}" required>
                                            @error('donor_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label for="amount" class="form-label fw-bold">Montant du don</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="amount" name="amount"
                                                    placeholder="Ex: 100" required min="1" value="{{ old('amount') }}">
                                                <select class="form-select flex-grow-0" style="width: auto;" name="currency" id="currency">
                                                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                                    <option value="TND" {{ old('currency', 'TND') == 'TND' ? 'selected' : '' }}>TND</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="text-success mb-3">
                                                <i class="fas fa-credit-card me-2"></i>Méthode de paiement
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="payment_method" class="form-label">Méthode de paiement *</label>
                                            <select class="form-select @error('payment_method') is-invalid @enderror"
                                                id="payment_method" name="payment_method" required>
                                                <option value="">Sélectionnez une méthode</option>
                                                <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Carte bancaire</option>
                                                <option value="paypal" {{ old('payment_method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                                                <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>Chèque</option>
                                            </select>
                                            @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label for="notes" class="form-label">Message (optionnel)</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                                id="notes" name="notes" rows="3"
                                                placeholder="Un message pour l'organisateur de l'événement...">{{ old('notes') }}</textarea>
                                            @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-success btn-lg px-5">
                                                <i class="fas fa-heart me-2"></i>
                                                Faire le don
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="impact-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    <span class="text-success">Votre impact</span>
                </h2>
                <p class="lead text-muted">
                    Découvrez comment votre don contribue à la réussite de l'événement
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card impact-card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-leaf fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Action Directe</h5>
                        <p class="card-text text-muted">
                            Votre don finance directement les actions écologiques de cet événement
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card impact-card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-users fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Communauté</h5>
                        <p class="card-text text-muted">
                            Vous rejoignez une communauté de donateurs engagés pour l'environnement
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card impact-card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <div class="impact-icon mb-3">
                            <i class="fas fa-chart-line fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Transparence</h5>
                        <p class="card-text text-muted">
                            Suivez l'utilisation de votre don et l'impact généré
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

    .impact-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
    }

    .impact-card:hover {
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

    .quick-amount {
        transition: all 0.3s ease;
    }

    .quick-amount:hover {
        background-color: #28a745;
        color: white;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('donation-form');
        if (!form) return;
        const sponsorNameField = document.getElementById('sponsor-name-field');
        const hiddenType = form.querySelector('input[name="type"]').value;
        // If hidden type is sponsor AND user is not sponsor role (field exists), show the sponsor name field.
        if (hiddenType === 'sponsor' && sponsorNameField) {
            sponsorNameField.style.display = 'flex';
        }
    });
</script>
@endpush