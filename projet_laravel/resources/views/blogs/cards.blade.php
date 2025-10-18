@extends('layouts.frontend')

@section('title', 'Tous les Blogs')

@section('content')
<div class="container">
    <h2 class="mb-4">Tous les Blogs</h2>

    {{-- Formulaire de filtre par titre --}}
    <form method="GET" action="{{ route('blogs.index') }}" class="mb-4 row g-2">
        <div class="col-md-10">
            <input type="text" name="title" value="{{ request('title') }}" class="form-control" placeholder="Rechercher par titre">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
    </form>

    @if($blogs->isEmpty())
        <div class="alert alert-info">Aucun blog disponible.</div>
    @else
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($blog->image_url)
                            <img src="{{ $blog->image_url }}" class="card-img-top" style="height:170px; object-fit:cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ Str::limit($blog->title, 50) }}</h5>
                            <p class="card-text">{{ Str::limit($blog->content, 100) }}</p>

                            {{-- Tags colorés --}}
                            @if($blog->tags)
                                <div class="mb-2">
                                    @foreach(explode(',', $blog->tags) as $tag)
                                        @php
                                            $colors = ['primary', 'danger', 'success', 'warning', 'info', 'secondary', 'pink', 'purple'];
                                            $color = $colors[array_rand($colors)];
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Bouton détails uniquement --}}
                            <a href="{{ route('blogs.show', $blog) }}" class="btn btn-primary mt-auto">Détails</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $blogs->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
