@extends('backend.layouts.app')

@section('title', 'Modifier le commentaire')
@section('page-title', 'Modifier le commentaire')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-eco shadow-lg border-0 rounded-4 p-4">
            <h4 class="mb-4">Modifier votre commentaire et votre note</h4>
            <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="rating">Note :</label>
                    <div id="star-rating-edit">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" id="star-edit{{ $i }}" name="rating" value="{{ $i }}" style="display:none;" {{ $review->rating == $i ? 'checked' : '' }} required>
                            <label for="star-edit{{ $i }}" style="font-size:2rem; color:{{ $i <= $review->rating ? '#ffd700' : '#ccc' }}; cursor:pointer;">&#9733;</label>
                        @endfor
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const stars = document.querySelectorAll('#star-rating-edit label');
                        const radios = document.querySelectorAll('#star-rating-edit input[type=radio]');
                        stars.forEach((star, idx) => {
                            star.addEventListener('click', function() {
                                radios[idx].checked = true;
                                stars.forEach((s, i) => {
                                    s.style.color = i <= idx ? '#ffd700' : '#ccc';
                                });
                            });
                        });
                    });
                </script>
                <div class="form-group mb-3">
                    <label for="comment">Commentaire :</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" required>{{ $review->comment }}</textarea>
                </div>
                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                <a href="{{ route('auteur.blogs.show', $review->blog_id) }}" class="btn btn-secondary ml-2">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection
