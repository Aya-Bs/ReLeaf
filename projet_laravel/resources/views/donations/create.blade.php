@extends('layouts.frontend')

@section('title', 'Faire un Don - ' . $event->title)

@section('content')
@php
$collected = \App\Models\Donation::where('event_id', $event->id)
->where('status', 'confirmed')
->sum('amount');
@endphp

<!-- Simple Event Header -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h2 class="h4 mb-1">{{ $event->title }}</h2>
                        <div class="small text-muted">
                            <span class="me-3"><i class="far fa-calendar me-1"></i>{{ $event->date ? $event->date->format('d/m/Y à H:i') : 'Date à définir' }}</span>
                            <span><i class="fas fa-map-marker-alt me-1"></i>{{ $event->location?->name ?? 'Lieu à définir' }}</span>
                        </div>
                    </div>
                    <div class="text-success fw-semibold">
                        <i class="fas fa-donate me-1"></i>
                        Collecté: {{ number_format($collected, 2, ',', ' ') }} €
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Donation Form Section -->
<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-donate me-2"></i>Faire un Don</h5>
                    </div>
                    <div class="card-body p-4">
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

                                    @php
                                    $resolvedType = 'individual';
                                    if(Auth::check() && Auth::user()->role === 'sponsor') { $resolvedType = 'sponsor'; }
                                    @endphp
                                    <input type="hidden" name="type" value="{{ $resolvedType }}">

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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('donation-form');
        if (!form) return;
        const sponsorNameField = document.getElementById('sponsor-name-field');
        const hiddenType = form.querySelector('input[name="type"]').value;
        if (hiddenType === 'sponsor' && sponsorNameField) {
            sponsorNameField.style.display = 'flex';
        }
    });
</script>
@endpush