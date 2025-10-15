@extends('layouts.frontend')

@section('title', 'Modifier le blog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2" style="color: #2d5a27;"></i>Modifier le blog
                    </h4>
                    <a href="{{ route('blogs.myblogs') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" id="editBlogForm">
                        @csrf
                        @method('PUT')

                        <!-- Titre -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Titre <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $blog->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contenu -->
                        <div class="mb-3">
                            <label for="content" class="form-label">
                                Contenu <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content"
                                      name="content"
                                      rows="6"
                                      required>{{ old('content', $blog->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text"
                                   class="form-control @error('tags') is-invalid @enderror"
                                   id="tags"
                                   name="tags"
                                   value="{{ old('tags', $blog->tags) }}"
                                   placeholder="Ex: écologie, environnement">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($blog->image_url)
                                <div class="mt-2">
                                    <p>Image actuelle :</p>
                                    <img src="{{ $blog->image_url }}" alt="Image du blog" style="max-width:200px; max-height:150px; object-fit:cover;">
                                </div>
                            @endif
                        </div>

                        <!-- Bouton -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès !',
            text: '{{ session('success') }}',
            confirmButtonColor: '#2d5a27',
        });
    @endif
</script>
@endpush
