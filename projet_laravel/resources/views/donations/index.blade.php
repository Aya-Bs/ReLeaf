@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="h4 mb-4">Mes dons</h1>

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
                <td><span class="badge bg-secondary">{{ $donation->status }}</span></td>
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

    {{ $donations->links() }}
</div>
@endsection