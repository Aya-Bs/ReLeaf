@extends('backend.layouts.app')

@section('title', 'Mes Blogs')
@section('page-title', 'Mes Blogs')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <form method="GET" action="{{ route('auteur.blogs.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control" placeholder="Rechercher un blog par titre..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-success ml-2"><i class="fas fa-search"></i> Rechercher</button>
        </form>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('auteur.blogs.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouveau Blog
        </a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-eco">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-blog mr-1"></i> Liste des blogs</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Auteur</th>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Tags</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $blog)
                        <tr>
                            <td>
                                @if($blog->author)
                                    <img src="{{ $blog->author->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($blog->author->name) }}" alt="Avatar" class="img-circle img-size-32 mr-2">
                                    <span>{{ $blog->author->first_name }} {{ $blog->author->last_name }}</span>
                                @endif
                            </td>
                            <td>{{ $blog->title }}</td>
                            <td>{{ $blog->date_posted }}</td>
                            <td>
                                @foreach(explode(',', $blog->tags) as $tag)
                                    @if(trim($tag) !== '')
                                        <span class="badge badge-pill" style="background-color: #{{ substr(md5(trim($tag)), 0, 6) }}; color: #fff; margin-right: 2px;">{{ trim($tag) }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('auteur.blogs.show', $blog) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('auteur.blogs.edit', $blog) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('auteur.blogs.destroy', $blog) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <!-- Bouton pour ouvrir le modal -->
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteBlogModal{{ $blog->id }}"><i class="fas fa-trash"></i></button>
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
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
