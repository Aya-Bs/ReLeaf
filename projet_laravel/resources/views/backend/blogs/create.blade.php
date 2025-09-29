@extends('backend.layouts.app')

@section('title', 'Créer un Blog')
@section('page-title', 'Créer un Blog')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('auteur.blogs.index') }}">Blogs</a></li>
    <li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-8">
        <div class="card card-eco">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-plus mr-1"></i> Nouveau Blog</h3>
                <a href="{{ route('auteur.blogs.index') }}" class="btn btn-sm btn-secondary">Retour</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('auteur.blogs.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Titre *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Contenu *</label>
                        <textarea name="content" id="content" class="form-control improved-textarea" rows="10" style="resize:vertical;" required>{{ old('content') }}</textarea>
                        @error('content')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Image *</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        @error('image')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags (séparés par des virgules) *</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags') }}" required>
                        @error('tags')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
