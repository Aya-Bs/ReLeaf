@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="h4 mb-4">Modifier le don #{{ $donation->id }}</h1>

    <div class="alert alert-info">Vous pouvez modifier ce don tant qu'il est en attente et moins de 24h après sa création.</div>

    <form method="POST" action="{{ route('donations.update', $donation) }}" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Montant</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $donation->amount) }}" class="form-control @error('amount') is-invalid @enderror" required>
            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Devise</label>
            <select name="currency" class="form-select @error('currency') is-invalid @enderror">
                @foreach(['EUR','USD','TND'] as $c)
                <option value="{{ $c }}" @selected(old('currency', $donation->currency)===$c)>{{ $c }}</option>
                @endforeach
            </select>
            @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Méthode de paiement</label>
            <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                @foreach(['card'=>'Carte bancaire','paypal'=>'PayPal','bank_transfer'=>'Virement bancaire','check'=>'Chèque'] as $val=>$label)
                <option value="{{ $val }}" @selected(old('payment_method', $donation->payment_method)===$val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror" placeholder="Message optionnel">{{ old('notes', $donation->notes) }}</textarea>
            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success"><i class="fas fa-save me-1"></i> Enregistrer</button>
            <a href="{{ route('donations.list') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection