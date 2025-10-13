<div class="table-responsive">
    <table class="table table-modern">
        <thead>
            <tr>
                <th><strong>Titre</strong></th>
                <th><strong>Organisateur</strong></th>
                <th><strong>Date</strong></th>
                <th><strong>Statut</strong></th>
                <th><strong>Participants</strong></th>
            </tr>
        </thead>
        <tbody>
            @if($events && $events->count() > 0)
                @foreach($events as $event)
                <tr onclick="window.location='{{ route('backend.events.show', $event->id) }}'">
                    <td style="font-size:14px">{{ $event->title }}</td>
                    <td>{{ $event->user->name ?? 'Inconnu' }}</td>
                    <td>
                        <span class="badge-modern badge-light">
                            <i class="far fa-calendar"></i>
                            {{ $event->date->format('d/m/Y H:i') }}
                        </span>
                    </td>
                    <td>
                        @if($event->isPending())
                            <span class="badge-modern badge-warning">
                                <i class="fas fa-clock"></i>
                                En attente
                            </span>
                        @elseif($event->isPublished())
                            <span class="badge-modern badge-success">
                                <i class="fas fa-check"></i>
                                Publié
                            </span>
                        @elseif($event->isDraft())
                            <span class="badge-modern badge-secondary">
                                <i class="fas fa-file"></i>
                                Brouillon
                            </span>
                        @elseif($event->isRejected())
                            <span class="badge-modern badge-danger">
                                <i class="fas fa-times"></i>
                                Rejeté
                            </span>
                        @elseif($event->isCancelled())
                            <span class="badge-modern badge-danger">
                                <i class="fas fa-ban"></i>
                                Annulé
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-modern badge-info">
                            <i class="fas fa-users"></i>
                            {{ $event->participants_count ?? 0 }} / 
                            {{ $event->max_participants ?? '∞' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h4>Aucun événement</h4>
                        <p>Aucun événement n'est prévu à ce lieu pour le moment.</p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Custom Pagination -->
@if($events && $events->count() > 0)
<div class="custom-pagination">
    {{ $events->links('pagination::bootstrap-4') }}
</div>
@endif