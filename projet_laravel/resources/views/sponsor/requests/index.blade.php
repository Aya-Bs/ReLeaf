@extends('layouts.frontend')

@section('title', 'Mes demandes de sponsoring')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-4"><i class="fas fa-handshake me-2 text-success"></i>Mes demandes de sponsoring</h1>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($requests->isEmpty())
    <div class="alert alert-info mb-0">Aucune demande en attente.</div>
    @else
    <div class="list-group">
        @foreach($requests as $req)
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-semibold">{{ $req->event?->title ?? 'Événement' }}</div>
                <small class="text-muted">{{ optional($req->event?->date)->format('d/m/Y H:i') }}</small>
            </div>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('sponsor.requests.accept', $req) }}">
                    @csrf
                    <button class="btn btn-sm btn-success"><i class="fas fa-check me-1"></i>Accepter</button>
                </form>
                <form method="POST" action="{{ route('sponsor.requests.decline', $req) }}" onsubmit="return confirm('Refuser cette demande ?');">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times me-1"></i>Refuser</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection