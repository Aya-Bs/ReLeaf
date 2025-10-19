@extends('layouts.frontend')

@section('title', 'Modifier l\'Événement')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-12">
            <div>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-4">
                        <i class="fas fa-edit me-2" style="color: #2d5a27;"></i><strong>Modifier l'événement</strong>
                    </h4>
                    <!-- Breadcrumb path on top right -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-4" style="background: transparent; padding: 0; margin: 0;">
                            <li class="breadcrumb-item"><a href="{{ route('events.my-events') }}" style="color: #2d5a27;">Événements</a></li>
                            <li class="breadcrumb-item active" aria-current="page" style="color: #2d5a27;"><strong>Modifier</strong></li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" id="eventForm" novalidate >
                        @csrf
                        @method('PUT')

                        <!-- Status Info -->
                        @if($event->isPending())
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            Cet événement est en attente d'approbation par l'administrateur.
                        </div>
                        @elseif($event->isPublished())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Cet événement est publié. Les modifications peuvent être limitées.
                        </div>
                        @endif

                        <div class="row">
                            <!-- Left Column - Form Fields -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading me-2" style="color: #2d5a27;"></i>Titre de l'événement <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        id="title"
                                        name="title"
                                        value="{{ old('title', $event->title) }}"
                                        required
                                        {{ !$event->canBeEdited() ? 'disabled' : '' }}
                                        placeholder="Ex: Nettoyage de la plage">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">
                                                <i class="fas fa-clock me-2" style="color: #2d5a27;"></i>Durée <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('duration') is-invalid @enderror"
                                                id="duration"
                                                name="duration"
                                                required
                                                {{ !$event->canBeEdited() ? 'disabled' : '' }}>
                                                <option value="">Sélectionnez une durée</option>
                                                <option value="1 heure" {{ old('duration', $event->duration) == '1 heure' ? 'selected' : '' }}>1 heure</option>
                                                <option value="2 heures" {{ old('duration', $event->duration) == '2 heures' ? 'selected' : '' }}>2 heures</option>
                                                <option value="3 heures" {{ old('duration', $event->duration) == '3 heures' ? 'selected' : '' }}>3 heures</option>
                                                <option value="4 heures" {{ old('duration', $event->duration) == '4 heures' ? 'selected' : '' }}>4 heures</option>
                                                <option value="Demi-journée" {{ old('duration', $event->duration) == 'Demi-journée' ? 'selected' : '' }}>Demi-journée</option>
                                                <option value="Journée entière" {{ old('duration', $event->duration) == 'Journée entière' ? 'selected' : '' }}>Journée entière</option>
                                                <option value="Week-end" {{ old('duration', $event->duration) == 'Week-end' ? 'selected' : '' }}>Week-end</option>
                                            </select>
                                            @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

<div class="col-md-6">
    <label for="campaign_id" class="form-label">
        <i class="fas fa-bullhorn me-2" style="color: #2d5a27;"></i>Campagne <span class="text-danger">*</span>
    </label>
    @php
        $campaigns = \App\Models\Campaign::all();
    @endphp
    <select class="form-select @error('campaign_id') is-invalid @enderror" id="campaign_id" name="campaign_id" {{ !$event->canBeEdited() ? 'disabled' : '' }}>
        <option value="">Aucune campagne</option>
        @foreach($campaigns as $campaign)
            <option value="{{ $campaign->id }}" {{ old('campaign_id', $event->campaign_id) == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
        @endforeach
    </select>
    @error('campaign_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror                                    
</div>
</div>
                                <!-- Max Participants -->
                                <div class="mb-3">
                                    <label for="max_participants" class="form-label">
                                        <i class="fas fa-users me-2" style="color: #2d5a27;"></i>Nombre maximum de participants
                                    </label>
                                    <input type="number"
                                        class="form-control @error('max_participants') is-invalid @enderror"
                                        id="max_participants"
                                        name="max_participants"
                                        value="{{ old('max_participants', $event->max_participants) }}"
                                        min="1"
                                        {{ !$event->canBeEdited() ? 'disabled' : '' }}
                                        placeholder="Laissez vide pour illimité">
                                    @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Optionnel - Laissez vide si pas de limite</small>
                                </div>


                            </div>

                            <!-- Right Column - Images and Actions -->
                            <div class="col-md-4">
                                <!-- Current Images -->
                                @if($event->images && is_array($event->images) && count($event->images) > 0)
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-images me-2" style="color: #2d5a27;"></i>Images actuelles
                                    </label>
                                    <div class="row">
                                        @foreach($event->images as $image)
                                        @if(!empty($image))
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card position-relative">
                                                <img src="{{ asset('storage/' . $image) }}"
                                                    class="card-img-top"
                                                    style="height: 120px; object-fit: cover;"
                                                    alt="Event image"
                                                    onerror="this.src='https://via.placeholder.com/150x100?text=Image+Non+Trouvée'">
                                                @if($event->canBeEdited())
                                                <div class="card-body p-2 text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger remove-image-btn"
                                                        data-image-path="{{ $image }}">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-images me-2" style="color: #2d5a27;"></i>Images actuelles
                                    </label>
                                    <p class="text-muted">Aucune image pour cet événement.</p>
                                </div>
                                @endif

                                <!-- New Images - Updated Drag & Drop Style -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-plus-circle me-2" style="color: #2d5a27;"></i>Ajouter de nouvelles images
                                    </label>

                                    <div class="drag-drop-area @error('images.*') is-invalid @enderror {{ !$event->canBeEdited() ? 'disabled-area' : '' }}"
                                        id="dragDropArea"
                                        style="border:2px dashed #2d5a27; border-radius:12px; background:#f4fbf4; padding:1.2rem 0.5rem; text-align:center; display:flex; flex-direction:column; align-items:center; justify-content:center; max-width:340px; margin:auto;">
                                        <div class="drag-drop-content" style="pointer-events:none;">
                                            <i class="fas fa-cloud-upload-alt" style="font-size:2.1rem; color:#2d5a27; margin-bottom:0.5rem;"></i>
                                            <div style="font-size:1.05rem; color:#2d5a27; font-weight:500;">Drag & Drop vos images</div>
                                            <div style="color:#2d5a27; margin:0.25rem 0; font-size:0.95rem;">ou</div>
                                            <div style="pointer-events:all;">
                                                <button type="button" class="btn btn-outline-success btn-sm" id="browseBtn" style="border-color:#2d5a27; color:#2d5a27;">Parcourir les fichiers</button>
                                                <input type="file" id="images" name="images[]" multiple accept="image/*" style="display:none;">
                                            </div>
                                        </div>
                                    </div>

                                    @error('images.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Vous pouvez sélectionner plusieurs images. Formats acceptés: JPG, PNG, GIF. Max: 2MB par image.
                                    </small>

                                    <!-- Image preview -->
                                    <div id="imagePreview" class="mt-3 row g-2"></div>
                                </div>



                                <!-- Buttons -->
                                <div class="d-flex justify-content-between mt-4 align-items-center gap-2">
                                    <a href="{{ route('events.my-events') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Retour
                                    </a>

                                    <div class="d-flex gap-2">
                                        @php
                                        $hasFlyer = !empty($event->flyer_generated_at) || !empty($event->flyer_path) || !empty($event->flyer_image_path);
                                        @endphp
                                        @if(!$hasFlyer)
                                        <button type="button" class="btn btn-outline-success js-gen-flyer-one" data-id="{{ $event->id }}">
                                            <span class="icon"><i class="fas fa-wand-magic-sparkles me-1"></i></span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                            Générer le flyer
                                        </button>
                                        @else
                                        @if(!empty($event->flyer_image_path))
                                        <a href="{{ route('events.flyer.image', $event) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-image me-1"></i> Télécharger l'image
                                        </a>
                                        @endif
                                        @if(!empty($event->flyer_path))
                                        <a href="{{ route('events.flyer.pdf', $event) }}" class="btn btn-outline-danger">
                                            <i class="fas fa-file-pdf me-1"></i> Télécharger le PDF
                                        </a>
                                        @endif
                                        @endif

                                        @if($event->canBeEdited())
                                        <button type="submit" class="btn btn-eco" style="background-color: #2d5a27; border-color: #2d5a27; color: white;">
                                            <i class="fas fa-save me-2"></i>Mettre à jour
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-secondary" disabled>
                                            <i class="fas fa-lock me-2"></i>Modification non autorisée
                                        </button>
                                        @endif
                                    </div>
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
    // Generate flyer (synchronous via AJAX) on edit page
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.querySelector('.js-gen-flyer-one');
        if (!btn) return;
        btn.addEventListener('click', async function() {
            const id = this.getAttribute('data-id');
            const spinner = this.querySelector('.spinner-border');
            const icon = this.querySelector('.icon');
            this.disabled = true;
            spinner.classList.remove('d-none');
            icon.classList.add('d-none');
            try {
                const res = await fetch(`/my-events/${id}/flyer/generate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (!res.ok || data.status === 'error') throw new Error(data.message || 'Erreur');
                // Replace with download links
                const container = this.parentElement;
                this.remove();
                if (data.flyer_image_path) {
                    const aImg = document.createElement('a');
                    aImg.href = `/my-events/${id}/flyer/image`;
                    aImg.className = 'btn btn-outline-primary';
                    aImg.innerHTML = '<i class="fas fa-image me-1"></i> Télécharger l\'image';
                    container.appendChild(aImg);
                }
                if (data.flyer_path) {
                    const aPdf = document.createElement('a');
                    aPdf.href = `/my-events/${id}/flyer/pdf`;
                    aPdf.className = 'btn btn-outline-danger';
                    aPdf.innerHTML = '<i class="fas fa-file-pdf me-1"></i> Télécharger le PDF';
                    container.appendChild(aPdf);
                }
            } catch (e) {
                this.disabled = false;
                spinner.classList.add('d-none');
                icon.classList.remove('d-none');
                alert('Échec de la génération du flyer: ' + e.message);
            }
        });
    });
    // Drag & Drop functionality
    const dragDropArea = document.getElementById('dragDropArea');
    const fileInput = document.getElementById('images');
    const browseBtn = document.getElementById('browseBtn');

    // Browse button click
    browseBtn.addEventListener('click', function() {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Drag & drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dragDropArea.classList.add('drag-over');
    }

    function unhighlight() {
        dragDropArea.classList.remove('drag-over');
    }

    dragDropArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
        fileInput.files = files;
    });

    // Handle selected files
    function handleFiles(files) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-4 mb-2';
                    col.innerHTML = `
                    <div class="card position-relative">
                        <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-new-image" data-file-name="${file.name}" style="padding: 0.25rem 0.5rem;">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="card-body p-2">
                            <small class="text-muted d-block text-truncate">${file.name}</small>
                        </div>
                    </div>
                `;
                    preview.appendChild(col);

                    // Add remove functionality for new images
                    col.querySelector('.remove-new-image').addEventListener('click', function() {
                        removeNewImage(this, file.name);
                    });
                };

                reader.readAsDataURL(file);
            }
        }
    }

    // Remove new image before upload
    function removeNewImage(button, fileName) {
        if (confirm('Supprimer cette image ?')) {
            const card = button.closest('.col-6');
            card.remove();

            // Remove file from input
            const dt = new DataTransfer();
            const files = fileInput.files;

            for (let i = 0; i < files.length; i++) {
                if (files[i].name !== fileName) {
                    dt.items.add(files[i]);
                }
            }

            fileInput.files = dt.files;
        }
    }

    // Remove existing image functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all remove buttons for existing images
        document.querySelectorAll('.remove-image-btn').forEach(button => {
            button.addEventListener('click', function() {
                const imagePath = this.getAttribute('data-image-path');
                removeImage(imagePath);
            });
        });
    });

    function removeImage(imagePath) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette image ? Cette action est irréversible.')) {
            // Show loading state
            const button = document.querySelector(`[data-image-path="${imagePath}"]`);
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
            button.disabled = true;

            // Send AJAX request to remove image
            fetch('{{ route("events.remove-image", $event) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        image_path: imagePath
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the image card from the DOM
                        const imageCard = document.querySelector(`[data-image-path="${imagePath}"]`).closest('.col-md-6');
                        if (imageCard) {
                            imageCard.remove();
                        }

                        // Show success message
                        showAlert('Image supprimée avec succès!', 'success');

                        // If no images left, show message
                        const remainingImages = document.querySelectorAll('.col-md-6.mb-3').length;
                        if (remainingImages === 0) {
                            location.reload(); // Reload to show "no images" message
                        }
                    } else {
                        throw new Error(data.error || 'Erreur lors de la suppression');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Erreur lors de la suppression de l\'image: ' + error.message, 'error');
                    button.innerHTML = originalHtml;
                    button.disabled = false;
                });
        }
    }

    // Helper function to show alerts
    function showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.custom-alert');
        existingAlerts.forEach(alert => alert.remove());

        const alertClass = type === 'success' ? 'alert-success' :
            type === 'error' ? 'alert-danger' : 'alert-info';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} custom-alert alert-dismissible fade show`;
        alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

        // Insert after the card header
        const cardHeader = document.querySelector('.card-header');
        cardHeader.parentNode.insertBefore(alertDiv, cardHeader.nextSibling);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Form validation
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        const formEl = document.getElementById('eventForm');
        const CAN_EDIT = formEl ? (formEl.getAttribute('data-can-edit') === '1') : true;
        if (!CAN_EDIT) {
            e.preventDefault();
            alert('Cet événement ne peut pas être modifié.');
            return;
        }

        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const date = document.getElementById('date').value;
        const location = document.getElementById('location_id').value;
        const duration = document.getElementById('duration').value;

        if (!title || !description || !date || !location || !duration) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return;
        }

        const selectedDate = new Date(date);
        if (selectedDate <= new Date()) {
            e.preventDefault();
            alert('La date de l\'événement doit être dans le futur.');
            return;
        }
    });

    // Set min datetime for date field
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('date').min = now.toISOString().slice(0, 16);
    });
</script>
@endpush

@push('styles')
<style>
    .remove-image-btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .custom-alert {
        margin: 1rem;
        border-radius: 0.375rem;
    }

    .card-img-top {
        object-fit: cover;
    }

    .drag-drop-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .drag-drop-area.disabled-area {
        opacity: 0.6;
        pointer-events: none;
    }

    .drag-drop-area:hover {
        border-color: #2d5a27;
        background-color: #f0f9f0;
    }

    .drag-drop-area.drag-over {
        border-color: #2d5a27;
        background-color: #e8f5e8;
    }

    .drag-drop-content {
        pointer-events: none;
    }

    .drag-drop-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .drag-drop-area h5 {
        color: #495057;
        margin-bottom: 0.5rem;
    }

    #browseBtn {
        pointer-events: all;
        background-color: white;
        border: 1px solid #2d5a27;
        color: #2d5a27;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    #browseBtn:hover {
        background-color: #2d5a27;
        color: white;
    }

    .remove-new-image {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .remove-new-image:hover {
        opacity: 1;
    }

    .alert-info {
        background-color: #e8f4f8;
        border-color: #b3e0f0;
        color: #055160;
    }

    .alert-info i {
        color: #055160;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
        }

        .drag-drop-area {
            padding: 30px 15px;
        }

        .drag-drop-icon {
            font-size: 36px;
        }
    }
</style>
@endpush