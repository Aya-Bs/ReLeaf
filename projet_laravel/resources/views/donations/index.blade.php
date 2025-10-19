@extends('layouts.frontend')

@section('title', 'Mes dons')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="h4 mb-4"><i class="fas fa-donate me-2 text-success"></i>Mes dons</h1>

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Événement</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $donation)
                <tr>
                    <td>{{ ($donation->donated_at ?? $donation->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $donation->event?->title }}</td>
                    <td>{{ $donation->type === 'sponsor' ? 'Sponsor' : 'Individuel' }}</td>
                    <td>{{ number_format($donation->amount,2,',',' ') }} {{ $donation->currency }}</td>
                    <td>
                        @php
                        $status = $donation->status;
                        $badgeClass = match($status){
                        'confirmed' => 'bg-success',
                        'pending' => 'bg-warning',
                        'failed' => 'bg-danger',
                        'cancelled' => 'bg-secondary',
                        default => 'bg-secondary',
                        };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </td>
                    <td class="text-end">
                        @php $canEdit = auth()->user() && $donation->canBeModifiedBy(auth()->user()); @endphp
                        @if($canEdit)
                        <a href="{{ route('donations.edit', $donation) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="{{ route('donations.destroy', $donation) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce don ?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Aucun don pour l'instant.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $donations->links() }}
        </div>
    </div>
</section>
@endsection