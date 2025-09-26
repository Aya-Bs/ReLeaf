@extends('backend.layouts.app')

@section('title', 'Créer un Blog')
@section('page-title', 'Créer un Blog')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Nouveau Blog</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('auteur.blogs.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" name="title" class="form-control">
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Contenu</label>
                        <textarea name="content" id="content" class="form-control improved-textarea" rows="12" style="resize: vertical; min-height: 350px; font-size: 1.15rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: #f8fafc; border: 1.5px solid #cbd5e1; padding: 1.2rem;" placeholder="Écrivez ici le contenu de votre blog..." maxlength="5000"></textarea>
                        <div class="d-flex justify-content-end mt-1">
                            <small id="content-count" style="color: #6b7280;">0 / 5000 caractères</small>
                        </div>
                        @error('content')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" name="tags" class="form-control">
                        @error('tags')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success">Créer</button>
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const textarea = document.getElementById('content');
    const count = document.getElementById('content-count');
    textarea.addEventListener('input', function() {
        count.textContent = `${textarea.value.length} / 5000 caractères`;
    });
</script>
@endsection

<!-- Zone de texte classique, sans éditeur JS -->
