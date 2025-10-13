@extends('backend.layouts.app')

@section('title', 'Mes Blogs')
@section('page-title', 'Mes Blogs')

@section('breadcrumb')
    <li class="breadcrumb-item active">Blogs</li>
@endsection

@section('content')
<div class="d-flex justify-content-between mb-3">
    <form method="GET" class="form-inline">
        <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="Rechercher un titre...">
        <button class="btn btn-primary">Rechercher</button>
    </form>
    <a href="{{ route('auteur.blogs.create') }}" class="btn btn-success">
        <i class="fas fa-plus-circle mr-1"></i> Nouveau blog
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($blogs->isEmpty())
    <div class="alert alert-info mb-0">Aucun blog trouvé.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date</th>
                    <th>Tags</th>
                    <th style="width:190px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($blogs as $blog)
                <tr>
                    <td><a href="{{ route('auteur.blogs.show', $blog) }}">{{ $blog->title }}</a></td>
                    <td>
                        @php($author = $blog->author)
                        {{ $author ? trim(($author->profile->first_name ?? '').' '.($author->profile->last_name ?? '')) ?: $author->name : '—' }}
                    </td>
                    <td>{{ $blog->date_posted?->format('d/m/Y H:i') }}</td>
                    <td>{{ $blog->tags }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('auteur.blogs.show', $blog) }}" class="btn btn-sm btn-info" title="Voir"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('auteur.blogs.edit', $blog) }}" class="btn btn-sm btn-warning" title="Modifier"><i class="fas fa-edit"></i></a>
                        <button type="button" class="btn btn-sm btn-danger" title="Supprimer" data-toggle="modal" data-target="#deleteBlogModal" data-action="{{ route('auteur.blogs.destroy', $blog) }}" data-title="{{ $blog->title }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
        <!-- Modal global de confirmation de suppression -->
        <div class="modal fade" id="deleteBlogModal" tabindex="-1" role="dialog" aria-labelledby="deleteBlogModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteBlogModalLabel">Supprimer le blog</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteBlogMessage" class="mb-0">Voulez-vous supprimer ce blog ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <form id="deleteBlogForm" action="#" method="POST" class="m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
        var modal = document.getElementById('deleteBlogModal');
        if(!modal) return;
        $('#deleteBlogModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var action = button.data('action');
                var title = button.data('title');
                var form = document.getElementById('deleteBlogForm');
                var msg = document.getElementById('deleteBlogMessage');
                form.setAttribute('action', action);
                msg.textContent = 'Voulez-vous supprimer l\'article : "' + title + '" ?';
        });
});
</script>
@endpush
@endsection
