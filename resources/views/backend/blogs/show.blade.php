@extends('backend.layouts.app')

@section('title', $blog->title)
@section('page-title', $blog->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-eco shadow-lg border-0 rounded-4 p-4" style="overflow:hidden;">
            <div class="row align-items-center">
                <div class="col-md-5 text-center">
                    @if($blog->image_url)
                        <img src="{{ asset($blog->image_url) }}" alt="Image du blog" class="img-fluid" style="max-height:320px; object-fit:cover; border-radius:16px; box-shadow:0 2px 12px #ccc;">
                    @endif
                </div>
                <div class="col-md-7">
                    <h2 class="font-weight-bold mb-2" style="font-size:2rem; letter-spacing:1px; text-align:center;">{{ $blog->title }}</h2>
                    <div class="mb-3 text-center">
                        @foreach(explode(',', $blog->tags) as $tag)
                            @if(trim($tag) !== '')
                                <span class="badge badge-pill" style="background-color: #{{ substr(md5(trim($tag)), 0, 6) }}; color: #fff; margin-right: 8px; font-size:1rem;">{{ trim($tag) }}</span>
                            @endif
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-between mb-2 align-items-center">
                        <span class="text-muted"><i class="far fa-calendar-alt"></i> {{ $blog->date_posted }}</span>
                        @if($blog->author)
                            <span>
                                <img src="{{ $blog->author->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($blog->author->name) }}" alt="Avatar" class="img-circle img-size-32 mr-2">
                                <span>{{ $blog->author->first_name }} {{ $blog->author->last_name }}</span>
                            </span>
                        @endif
                    </div>
                    <hr>
                    <div class="mb-4" style="font-size:1.15rem; color:#333; line-height:1.7;">
                        {{ $blog->content }}
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('auteur.blogs.edit', $blog) }}" class="btn btn-warning" style="border-radius:20px;"><i class="fas fa-edit"></i> Modifier</a>
                        <form action="{{ route('auteur.blogs.destroy', $blog) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="border-radius:20px;" onclick="return confirm('Supprimer ce blog ?')"><i class="fas fa-trash"></i> Supprimer</button>
                        </form>
                        <a href="{{ route('auteur.blogs.index') }}" class="btn btn-secondary" style="border-radius:20px;">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
