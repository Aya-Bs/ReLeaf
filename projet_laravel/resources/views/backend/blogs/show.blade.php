@extends('backend.layouts.app')

@section('title', $blog->title)
@section('page-title', $blog->title)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('auteur.blogs.index') }}">Blogs</a></li>
<li class="breadcrumb-item active">Détails</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-eco shadow-lg border-0 rounded-4 p-4" style="overflow:hidden;">
            <div class="row align-items-center">
                <div class="col-md-5 text-center mb-3 mb-md-0">
                    @if($blog->image_url)
                    <img src="{{ asset($blog->image_url) }}" alt="Image du blog" class="img-fluid" style="max-height:320px; object-fit:cover; border-radius:16px; box-shadow:0 2px 12px #ccc;">
                    @endif
                </div>
                <div class="col-md-7">
                    <h2 class="font-weight-bold mb-2 text-center" style="font-size:2rem; letter-spacing:1px;">{{ $blog->title }}</h2>
                    <div class="mb-3 text-center">
                        @php(
                        $tagColorClasses = [
                        'tag-color-a','tag-color-b','tag-color-c','tag-color-d','tag-color-e','tag-color-f','tag-color-g'
                        ]
                        )
                        <style>
                            .tag-color-a {
                                background-color: #2d5a27;
                                color: #fff;
                            }

                            .tag-color-b {
                                background-color: #4a7c59;
                                color: #fff;
                            }

                            .tag-color-c {
                                background-color: #8bc34a;
                                color: #fff;
                            }

                            .tag-color-d {
                                background-color: #1b3a17;
                                color: #fff;
                            }

                            .tag-color-e {
                                background-color: #5c7cfa;
                                color: #fff;
                            }

                            .tag-color-f {
                                background-color: #f59f00;
                                color: #fff;
                            }

                            .tag-color-g {
                                background-color: #d6336c;
                                color: #fff;
                            }
                        </style>
                        @foreach(explode(',', $blog->tags ?? '') as $tag)
                        @php($t = trim($tag))
                        @if($t !== '')
                        @php($cls = $tagColorClasses[$loop->index % count($tagColorClasses)])
                        <span class="badge badge-pill {{ $cls }}" style="margin-right:8px;font-size:1rem;">{{ $t }}</span>
                        @endif
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-between mb-2 align-items-center flex-wrap">
                        <span class="text-muted mb-2 mb-sm-0"><i class="far fa-calendar-alt"></i> {{ $blog->date_posted?->format('d/m/Y H:i') }}</span>
                        @if($blog->author)
                        <span class="d-flex align-items-center">
                            <img src="{{ $blog->author->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($blog->author->name) }}" alt="Avatar" class="img-circle img-size-32 mr-2" style="width:32px;height:32px;object-fit:cover;">
                            <span>{{ ($blog->author->profile->first_name ?? '') }} {{ ($blog->author->profile->last_name ?? '') }}</span>
                        </span>
                        @endif
                    </div>
                    <hr>
                    <div class="mb-4" style="font-size:1.05rem; color:#333; line-height:1.6; white-space:pre-line;">{{ $blog->content }}</div>

                    @php($avgRating = $blog->reviews->count() ? round($blog->reviews->avg('rating'), 2) : null)
                    <div class="mb-4">
                        <strong>Moyenne des notes :</strong>
                        @if($avgRating)
                        <span style="font-size:1.3rem; color:#ffd700;">
                            @for($i=1;$i<=5;$i++)
                                <span style="color:{{ $i <= $avgRating ? '#ffd700':'#ccc' }};">&#9733;</span>
                        @endfor
                        </span>
                        <span class="ml-2">({{ $avgRating }}/5)</span>
                        @else
                        <span class="text-muted">Aucune note pour ce blog.</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-3">Commentaires</h4>
                        @forelse($blog->reviews as $review)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="font-weight-bold">{{ $review->user_name }}</span>
                                    <span>
                                        @for($i=1;$i<=5;$i++)
                                            <span style="color:{{ $i <= $review->rating ? '#ffd700':'#ccc' }}; font-size:1.05rem;">&#9733;</span>
                                    @endfor
                                    </span>
                                </div>
                                <p class="mb-1">{{ $review->comment }}</p>
                                <small class="text-muted">
                                    {{ $review->date_posted ? (optional($review->date_posted)->format('d/m/Y H:i') ?? \Illuminate\Support\Carbon::parse($review->date_posted)->format('d/m/Y H:i')) : $review->created_at->format('d/m/Y H:i') }}
                                </small>
                                @if(Auth::id() === $review->user_id)
                                <div class="mt-2">
                                    <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce commentaire ?')">Supprimer</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-info text-center">Aucun commentaire pour ce blog.</div>
                        @endforelse
                    </div>

                    @if(Auth::check() && Auth::user()->role === 'user')
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Laisser un commentaire ou une note</h5>
                            <form action="{{ route('reviews.store', $blog->id) }}" method="POST">
                                @csrf
                                <div class="form-group mb-2">
                                    <label>Note :</label>
                                    <div id="star-rating" class="mb-2">
                                        @for($i=1;$i<=5;$i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" style="display:none;" required>
                                            <label for="star{{ $i }}" style="font-size:2rem;color:#ccc;cursor:pointer;">&#9733;</label>
                                            @endfor
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="comment">Commentaire :</label>
                                    <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Envoyer</button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
                        @if(Auth::check() && (Auth::id() === $blog->author_id || Auth::user()->role === 'admin'))
                        <a href="{{ route('auteur.blogs.edit', $blog) }}" class="btn btn-warning mb-2" style="border-radius:20px;"><i class="fas fa-edit"></i> Modifier</a>
                        <button type="button" class="btn btn-danger mb-2" style="border-radius:20px;" data-toggle="modal" data-target="#deleteBlogModal">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                        @endif
                        <a href="{{ route('auteur.blogs.cards') }}" class="btn btn-secondary mb-2" style="border-radius:20px;">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::check() && (Auth::id() === $blog->author_id || Auth::user()->role === 'admin'))
<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteBlogModal" tabindex="-1" role="dialog" aria-labelledby="deleteBlogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteBlogModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Voulez-vous supprimer cet article ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form action="{{ route('auteur.blogs.destroy', $blog) }}" method="POST" class="m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
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
@endpush
@endsection