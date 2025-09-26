@extends('backend.layouts.app')

@section('title', 'Modifier le Blog')
@section('page-title', 'Modifier le Blog')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Modifier Blog</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('auteur.blogs.update', $blog) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Contenu</label>
                        <textarea name="content" class="form-control" rows="5" required>{{ $blog->content }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image_url">Image (URL)</label>
                        <input type="text" name="image_url" class="form-control" value="{{ $blog->image_url }}">
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" name="tags" class="form-control" value="{{ $blog->tags }}">
                    </div>
                    <button type="submit" class="btn btn-warning">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
