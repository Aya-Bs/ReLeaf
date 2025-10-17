@extends('layouts.frontend')

@section('title', 'Mes Blogs')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Mes Blogs</h2>
        @if(Auth::check() && Auth::user()->role === 'organizer')
            <a href="{{ route('blogs.create') }}" class="btn btn-success">Créer un blog</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($blogs->isEmpty())
        <div class="alert alert-info">Vous n'avez aucun blog.</div>
    @else
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($blog->image_url)
                            <img src="{{ $blog->image_url }}" class="card-img-top" style="height:170px; object-fit:cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ Str::limit($blog->title, 50) }}</h5>
                            <p class="card-text">{{ Str::limit($blog->content, 100) }}</p>

                            {{-- Tags colorés --}}
                            @if($blog->tags)
                                <div class="mb-2">
                                    @foreach(explode(',', $blog->tags) as $tag)
                                        @php
                                            $colors = ['primary', 'danger', 'success', 'warning', 'info', 'pink', 'purple'];
                                            $color = $colors[array_rand($colors)];
                                        @endphp
                                        <span class="badge bg-{{ $color }} me-1">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto d-flex gap-2">
                                {{-- Bouton Détails toujours visible --}}
                                <a href="{{ route('blogs.show', $blog) }}" class="btn btn-primary">Détails</a>

                                {{-- Boutons Modifier et Supprimer pour les organizers seulement --}}
                                @if(Auth::check() && Auth::user()->role === 'organizer')
                                    <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-warning">Modifier</a>

                                    <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-delete">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- SweetAlert2 pour confirmation suppression --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: 'Voulez-vous supprimer ce blog ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
