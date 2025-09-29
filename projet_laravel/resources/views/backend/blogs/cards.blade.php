@extends('backend.layouts.app')

@section('title', 'Blogs (Cards)')
@section('page-title', 'Blogs (Cards)')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('auteur.blogs.index') }}">Blogs</a></li>
    <li class="breadcrumb-item active">Cards</li>
@endsection

@section('content')
<div class="mb-3 text-right">
    <a href="{{ route('auteur.blogs.create') }}" class="btn btn-success"><i class="fas fa-plus-circle mr-1"></i> Nouveau</a>
</div>

@if($blogs->isEmpty())
    <div class="alert alert-info">Aucun blog disponible.</div>
@else
    <div class="row">
        @foreach($blogs as $blog)
            <div class="col-md-4 mb-4">
                <div class="card h-100 card-eco">
                    @if($blog->image_url)
                        <img src="{{ $blog->image_url }}" class="card-img-top" style="height:170px;object-fit:cover;" alt="image">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ Str::limit($blog->title, 50) }}</h5>
                        <p class="card-text text-muted small mb-2">{{ $blog->date_posted?->format('d/m/Y H:i') }}</p>
                        <p class="card-text flex-grow-1">{{ Str::limit($blog->content, 110) }}</p>
                        <a href="{{ route('auteur.blogs.show', $blog) }}" class="btn btn-sm btn-primary mt-auto">Lire</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
