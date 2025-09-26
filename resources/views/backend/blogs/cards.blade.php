@extends('backend.layouts.app')

@section('title', 'Mes Blogs - Cards')
@section('page-title', 'Mes Blogs (Cards)')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('auteur.dashboard') }}">Dashboard Auteur</a></li>
    <li class="breadcrumb-item active">Blogs Cards</li>
@endsection

@section('content')
<div class="row">
    @foreach($blogs as $blog)
        <div class="col-md-4 mb-4">
            <div class="card card-eco h-100 shadow-lg border-0 rounded-4" style="overflow:hidden;">
                @if($blog->image_url)
                    <img src="{{ asset($blog->image_url) }}" class="card-img-top" alt="Image du blog" style="max-height:180px; object-fit:cover; border-bottom:4px solid #8bc34a;">
                @endif
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title text-center font-weight-bold" style="font-size:1.3rem;">{{ $blog->title }}</h5>
                    <p class="card-text text-muted" style="min-height:48px;">{{ Str::limit($blog->content, 80) }}</p>
                    <div class="mb-2 text-center">
                        @foreach(explode(',', $blog->tags) as $tag)
                            @if(trim($tag) !== '')
                                <span class="badge badge-pill" style="background-color: #{{ substr(md5(trim($tag)), 0, 6) }}; color: #fff; margin-right: 8px;">{{ trim($tag) }}</span>
                            @endif
                        @endforeach
                    </div>
                    <a href="{{ route('auteur.blogs.show', $blog) }}" class="btn btn-info btn-block mt-2" style="border-radius:20px;">Voir détails</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
