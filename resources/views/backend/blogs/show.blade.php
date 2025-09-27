@extends('backend.layouts.app')

@section('title', $blog->title)
@section('page-title', $blog->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        {{-- ...article... --}}
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

                    {{-- Affichage de la moyenne des ratings sous le blog --}}
                    @php
                        $avgRating = $blog->reviews->count() ? round($blog->reviews->avg('rating'), 2) : null;
                    @endphp
                    <div class="mb-3">
                        <strong>Moyenne des notes :</strong>
                        @if($avgRating)
                            <span style="font-size:1.3rem; color:#ffd700;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color:{{ $i <= $avgRating ? '#ffd700' : '#ccc' }};">&#9733;</span>
                                @endfor
                            </span>
                            <span class="ml-2">({{ $avgRating }}/5)</span>
                        @else
                            <span class="text-muted">Aucune note pour ce blog.</span>
                        @endif
                    </div>

                    {{-- Affichage des commentaires après l'article --}}
                    <div class="mb-4">
                        <h4 class="mb-3">Commentaires</h4>
                        @forelse($blog->reviews as $review)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="font-weight-bold">{{ $review->user_name }}</span>
                                        <span>
                                            @for($i = 1; $i <= 5; $i++)
                                                <span style="color:{{ $i <= $review->rating ? '#ffd700' : '#ccc' }}; font-size:1.2rem;">&#9733;</span>
                                            @endfor
                                        </span>
                                    </div>
                                    <p class="mb-1">{{ $review->comment }}</p>
                                    <small class="text-muted">
                                        @if($review->date_posted)
                                            @php
                                                try {
                                                    $date = \Carbon\Carbon::parse($review->date_posted);
                                                    echo $date->format('d/m/Y H:i');
                                                } catch (Exception $e) {
                                                    echo $review->date_posted;
                                                }
                                            @endphp
                                        @else
                                            {{ $review->created_at->format('d/m/Y H:i') }}
                                        @endif
                                    </small>
                                    <div class="mt-2">
                                        @if(auth()->id() === $review->user_id && auth()->user()->role !== 'auteur')
                                            <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                                        @endif
                                        @if(auth()->id() === $review->user_id || auth()->id() === $blog->author_id)
                                            <!-- Bouton pour ouvrir le modal -->
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteReviewModal{{ $review->id }}">Supprimer</button>
                                            <!-- Modal Bootstrap -->
                                            <div class="modal fade" id="deleteReviewModal{{ $review->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteReviewLabel{{ $review->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteReviewLabel{{ $review->id }}">Confirmation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Voulez-vous supprimer ce commentaire ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Confirmer</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center">Aucun commentaire pour ce blog.</div>
                        @endforelse
                    </div>

                    {{-- Affichage de la moyenne des ratings sous le blog --}}
                    @php
                        $avgRating = $blog->reviews->count() ? round($blog->reviews->avg('rating'), 2) : null;
                    @endphp
                    <div class="mb-3">
                        <strong>Moyenne des notes :</strong>
                        @if($avgRating)
                            <span style="font-size:1.3rem; color:#ffd700;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color:{{ $i <= $avgRating ? '#ffd700' : '#ccc' }};">&#9733;</span>
                                @endfor
                            </span>
                            <span class="ml-2">({{ $avgRating }}/5)</span>
                        @else
                            <span class="text-muted">Aucune note pour ce blog.</span>
                        @endif
                    </div>

                    {{-- Espace commentaire/rating pour l'utilisateur --}}
                    @if(auth()->check() && auth()->user()->role === 'user')
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Laisser un commentaire ou un rating</h5>
                                <form action="{{ route('reviews.store', $blog->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label for="rating">Note :</label>
                                        <div id="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" style="display:none;" required {{ $i == 1 ? 'checked' : '' }}>
                                                <label for="star{{ $i }}" style="font-size:2rem; color:#ffd700; cursor:pointer;">
                                                    &#9733;
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <script>
                                        // JS pour colorer les étoiles sélectionnées
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const stars = document.querySelectorAll('#star-rating label');
                                            const radios = document.querySelectorAll('#star-rating input[type=radio]');
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
                                    <div class="form-group mb-2">
                                        <label for="comment">Commentaire :</label>
                                        <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">Envoyer</button>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if(auth()->id() === $blog->author_id)
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('auteur.blogs.edit', $blog) }}" class="btn btn-warning" style="border-radius:20px;"><i class="fas fa-edit"></i> Modifier</a>
                            <!-- Bouton pour ouvrir le modal -->
                            <button type="button" class="btn btn-danger" style="border-radius:20px;" data-toggle="modal" data-target="#deleteBlogModal{{ $blog->id }}"><i class="fas fa-trash"></i> Supprimer</button>
                            <!-- Modal Bootstrap -->
                            <div class="modal fade" id="deleteBlogModal{{ $blog->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteBlogLabel{{ $blog->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteBlogLabel{{ $blog->id }}">Confirmation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous supprimer cet article ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <form action="{{ route('auteur.blogs.destroy', $blog) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Confirmer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('auteur.blogs.index') }}" class="btn btn-secondary" style="border-radius:20px;">Retour à la liste</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
