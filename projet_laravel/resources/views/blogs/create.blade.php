@extends('layouts.frontend')

@section('title', 'Créer un Blog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-4">
                        <i class="fas fa-blog me-2" style="color: #2d5a27;"></i><strong>Créer un nouveau blog</strong>
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-4" style="background: transparent; padding: 0; margin: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: #2d5a27;">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}" style="color: #2d5a27;">Blogs</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;"><strong>Créer</strong></li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
                        @csrf
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading me-2" style="color: #2d5a27;"></i>Titre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title') }}"
                                           required
                                           placeholder="Titre du blog">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">
                                        <i class="fas fa-align-left me-2" style="color: #2d5a27;"></i>Contenu <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content"
                                              name="content"
                                              rows="6"
                                              required
                                              placeholder="Écrivez le contenu de votre blog...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tags -->
                                <div class="mb-3">
                                    <label for="tags" class="form-label">
                                        <i class="fas fa-tags me-2" style="color: #2d5a27;"></i>Tags <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('tags') is-invalid @enderror"
                                           id="tags"
                                           name="tags"
                                           value="{{ old('tags') }}"
                                           required
                                           placeholder="Ex: tech, laravel, programmation">
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Image Upload -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-images me-2" style="color: #2d5a27;"></i>Image du blog
                                    </label>
                                    <div class="drag-drop-area @error('image') is-invalid @enderror" id="dragDropArea" style="border:2px dashed #2d5a27; border-radius:12px; background:#f4fbf4; padding:1.2rem 0.5rem; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                        <div class="drag-drop-content" style="pointer-events:none;">
                                            <i class="fas fa-cloud-upload-alt" style="font-size:2.1rem; color:#2d5a27; margin-bottom:0.5rem;"></i>
                                            <div style="font-size:1.05rem; color:#2d5a27; font-weight:500;">Glissez et déposez l'image</div>
                                            <div style="color:#2d5a27; margin:0.25rem 0; font-size:0.95rem;">ou</div>
                                            <div style="pointer-events:all;">
                                                <button type="button" class="btn btn-outline-success btn-sm" id="browseBtn" style="border-color:#2d5a27; color:#2d5a27;">Parcourir</button>
                                                <input type="file" id="image" name="image" accept="image/*" style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div id="imagePreview" class="mt-3"></div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-eco" style="background-color:#2d5a27; color:white;">
                                        <i class="fas fa-save me-2"></i>Créer le blog
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Drag & Drop for blog image
const dragDropArea = document.getElementById('dragDropArea');
const fileInput = document.getElementById('image');
const browseBtn = document.getElementById('browseBtn');

browseBtn.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', (e) => previewImage(e.target.files[0]));

['dragenter','dragover','dragleave','drop'].forEach(event => {
    dragDropArea.addEventListener(event, e => { e.preventDefault(); e.stopPropagation(); }, false);
});

['dragenter','dragover'].forEach(event => dragDropArea.addEventListener(event, () => dragDropArea.classList.add('drag-over')));
['dragleave','drop'].forEach(event => dragDropArea.addEventListener(event, () => dragDropArea.classList.remove('drag-over')));

dragDropArea.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    if(dt.files.length > 0) previewImage(dt.files[0]);
    fileInput.files = dt.files;
});

function previewImage(file) {
    if(!file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height:200px;">`;
    };
    reader.readAsDataURL(file);
}
</script>
@endpush

@push('styles')
<style>
.drag-drop-area { border: 2px dashed #dee2e6; border-radius: 8px; padding: 40px 20px; text-align:center; background:#f8f9fa; transition:all 0.3s; cursor:pointer; }
.drag-drop-area.drag-over { border-color:#2d5a27; background:#e8f5e8; }
.drag-drop-content { pointer-events:none; }
</style>
@endpush
