@extends('layouts.frontend')

@section('title', 'Blogs')

@section('content')
<div class="container">
    <h2>Tous les blogs</h2>

    @if($blogs->isEmpty())
        <div class="alert alert-info">Aucun blog disponible.</div>
    @else
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($blog->image_url)
                            <img src="{{ $blog->image_url }}" class="card-img-top" style="height:170px; object-fit:cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ Str::limit($blog->title, 50) }}</h5>
                            <p class="card-text">{{ Str::limit($blog->content, 100) }}</p>
                            <a href="{{ route('blogs.show', $blog) }}" class="btn btn-primary mt-auto">Lire</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
