@extends('backend.layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4"><i class="fas fa-user-slash me-2 text-danger"></i>Demandes de suppression sponsors</h1>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Entreprise</th>
                        <th>Email</th>
                        <th>Demandé le</th>
                        <th>Raison</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sponsors as $s)
                    <tr>
                        <td>{{ $s->company_name }}</td>
                        <td>{{ $s->contact_email }}</td>
                        <td>{{ $s->updated_at?->format('d/m/Y H:i') }}</td>
                        <td class="small" style="max-width:280px; white-space:normal;">{{ $s->deletion_reason }}</td>
                        <td class="d-flex gap-1">
                            <form method="POST" action="{{ route('backend.sponsors.process-deletion', $s) }}" onsubmit="return confirm('Confirmer suppression sponsor ?');">
                                @csrf
                                <button class="btn btn-sm btn-danger"><i class="fas fa-check"></i></button>
                            </form>
                            <a href="{{ route('backend.sponsors.show', $s) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Aucune demande.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection