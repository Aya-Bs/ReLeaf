@extends('backend.layouts.app')

@section('title', 'Gestion des Événements')
@section('page-title', 'Gestion des Événements')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Événements</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-eco">
                <div class="card-header">
                    <h3 class="card-title">Liste des événements</h3>
                </div>

                <div class="card-body">
                    @if($events->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Titre</th>
                                    <th>Organisateur</th>
                                    <th>Date</th>
                                    <th>Lieu</th>
                                    <th>Statut</th>
                                    <th>Participants</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
<tr onclick="window.location='{{ route('backend.events.show', $event) }}'" style="cursor: pointer;">
        <td>
        <strong>{{ $event->title }}</strong>
    </td>
    <td>{{ $event->user->name }}</td>
    <td>
        <span class="badge badge-light">
            {{ $event->date->format('d/m/Y H:i') }}
        </span>
    </td>
    <td>{{ $event->location }}</td>
    <td>
        @if($event->isPending())
            <span class="badge badge-warning">En attente</span>
        @elseif($event->isPublished())
            <span class="badge badge-success">Publié</span>
        @elseif($event->isDraft())
            <span class="badge badge-secondary">Brouillon</span>
        @elseif($event->isRejected())
            <span class="badge badge-danger">Rejeté</span>
        @elseif($event->isCancelled())
            <span class="badge badge-danger">Annulé</span>
        @endif
    </td>
    <td>
        <span class="badge badge-info">
            {{ $event->participants_count ?? 0 }} / 
            {{ $event->max_participants ?? '∞' }}
        </span>
    </td>
</tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $events->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun événement trouvé</h4>
                        <p class="text-muted">Aucun événement n'a été créé pour le moment.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection