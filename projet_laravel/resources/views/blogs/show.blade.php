@extends('layouts.frontend')

@section('title', $blog->title)

@section('content')
<div class="container my-5">
    <div class="card shadow-sm rounded-4 p-4">
        <h2 class="mb-3">{{ $blog->title }}</h2>
        <p class="text-muted"><small>Publié le : {{ $blog->date_posted?->format('d/m/Y H:i') }}</small></p>

        @if($blog->image_url)
            <img src="{{ $blog->image_url }}" class="img-fluid rounded-3 mb-4" style="max-height:400px; object-fit:cover; width:100%;">
        @endif

        <p class="mb-4">{{ $blog->content }}</p>

        {{-- Tags --}}
        @if($blog->tags)
            <div class="mb-4">
                <strong>Tags : </strong>
                @foreach(explode(',', $blog->tags) as $tag)
                    @php
                        $colors = ['#007bff', '#e83e8c', '#dc3545', '#6f42c1', '#17a2b8', '#fd7e14', '#20c997', '#6610f2'];
                        $color = $colors[array_rand($colors)];
                    @endphp
                    <span class="badge" style="background-color: {{ $color }}; color: white; margin-right: 5px;">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('blogs.myblogs') }}" class="btn btn-secondary">Retour aux blogs</a>
            @if(Auth::user()?->role === 'organizer')
                <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-primary">Modifier</a>
            @endif
        </div>
    </div>

    {{-- Section Reviews --}}
    <div class="reviews-section mt-5">
        <h4>Commentaires & Avis</h4>

        @if($blog->reviews && $blog->reviews->count() > 0)
            <ul class="list-group list-group-flush mt-3">
                @foreach($blog->reviews as $review)
                    <li class="list-group-item position-relative">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $review->user_name }}</strong>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                    @endfor
                                </div>
                            </div>

                            {{-- Menu ⋮ visible uniquement pour l’auteur --}}
                            @if(Auth::check() && Auth::user()->id === $review->user_id)
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" onclick="editReview({{ $review->id }}, '{{ $review->comment }}', {{ $review->rating }})">
                                                <i class="fa fa-edit me-2 text-primary"></i> Modifier
                                            </button>
                                        </li>
                                        <li>
                                            <form id="delete-review-form-{{ $review->id }}" action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $review->id }})">
                                                <i class="fa fa-trash me-2"></i> Supprimer
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <p class="mb-1">{{ $review->comment }}</p>
                        <small class="text-muted">{{ $review->date_posted ? \Carbon\Carbon::parse($review->date_posted)->format('d/m/Y H:i') : '' }}</small>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted mt-3">Aucun avis pour ce blog.</p>
        @endif

        {{-- Formulaire pour ajouter un avis --}}
        @if(Auth::check())
            <div class="mt-4">
                <h5>Laisser un avis</h5>
                <form action="{{ route('reviews.store', $blog->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label d-block">Note :</label>
                        <div class="rating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                <label for="star{{ $i }}">★</label>
                            @endfor
                        </div>
                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3" placeholder="Votre commentaire...">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Publier</button>
                </form>
            </div>
        @else
            <p class="text-muted mt-3">Veuillez vous connecter pour laisser un avis.</p>
        @endif
    </div>
</div>

{{-- STYLE ÉTOILES --}}
<style>
.rating {
    direction: rtl;
    display: inline-flex;
    gap: 5px;
}
.rating input {
    display: none;
}
.rating label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffc107;
}
</style>

{{-- SCRIPTS SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Voulez-vous supprimer ce commentaire ?',
        text: "Cette action est irréversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-review-form-' + id).submit();
        }
    });
}
</script>
@endsection
