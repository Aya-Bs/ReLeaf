@extends('backend.layouts.app')

@section('title', 'Modifier Blog')
@section('page-title', 'Modifier Blog')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('auteur.blogs.index') }}">Blogs</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <div class="card card-eco">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-edit mr-1"></i> Modifier Blog</h3>
                <a href="{{ route('auteur.blogs.index') }}" class="btn btn-sm btn-secondary">Retour</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('auteur.blogs.update', $blog) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">Titre *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
                        @error('title')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Contenu *</label>
                        <textarea name="content" id="content" class="form-control" rows="10" required>{{ old('content', $blog->content) }}</textarea>
                        @error('content')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Image (laisser vide pour conserver)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($blog->image_url)
                            <small class="d-block mt-1">Image actuelle : <img src="{{ $blog->image_url }}" alt="image" style="height:40px;"></small>
                        @endif
                        @error('image')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags *</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags', $blog->tags) }}" required>
                        @error('tags')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
