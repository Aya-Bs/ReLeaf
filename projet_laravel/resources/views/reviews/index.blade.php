@extends('backend.layouts.app')

@section('title', 'Commentaires du Blog')
@section('page-title', 'Commentaires du Blog')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('auteur.dashboard') }}">Dashboard Auteur</a></li>
    <li class="breadcrumb-item active">Commentaires</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <h4>Commentaires pour : <strong>{{ $blog->title }}</strong></h4>
    </div>
    @forelse($reviews as $review)
        <div class="col-md-6 mb-3">
            <div class="card card-eco shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="font-weight-bold">{{ $review->user_name }}</span>
                        <span class="badge badge-success">{{ $review->rating }}/5</span>
                    </div>
                    <p class="mb-2">{{ $review->comment }}</p>
                    <small class="text-muted">Posté le {{ $review->date_posted ? $review->date_posted->format('d/m/Y H:i') : $review->created_at->format('d/m/Y H:i') }}</small>
                    <div class="mt-2">
                        @if(auth()->id() === $review->user_id || auth()->id() === $blog->author_id)
                            <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-warning">Éditer</a>
                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce commentaire ?')">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">Aucun commentaire pour ce blog.</div>
        </div>
    @endforelse
</div>
@endsection
