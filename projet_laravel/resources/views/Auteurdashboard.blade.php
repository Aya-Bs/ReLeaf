@extends('backend.layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('breadcrumb')
    <li class="breadcrumb-item active">Tableau de bord</li>
@endsection

@section('content')
<!-- Nombre de blogs -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-eco">
            <div class="card-body text-center">
                <h4 class="mb-0">Vous avez <span class="badge badge-success">{{ \App\Models\Blog::where('author_id', auth()->id())->count() }}</span> blogs créés</h4>
            </div>
        </div>
    </div>
</div>


@endsection

