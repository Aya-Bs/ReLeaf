@extends('backend.layouts.app')

@section('title', 'Demandes de Suppression de Campagnes')
@section('page-title', 'Demandes de Suppression')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.campaigns.index') }}">Campagnes</a></li>
    <li class="breadcrumb-item active">Demandes de suppression</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-eco">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Demandes de suppression en attente</h3>
                    @php
                        $pendingCount = \App\Models\CampaignDeletionRequest::pending()->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning">
                            {{ $pendingCount }} demande(s) en attente
                        </span>
                    @endif
                </div>

                <div class="card-body">
                    @php
                        // Récupérer les demandes directement dans la vue si nécessaire
                        $deletionRequests = \App\Models\CampaignDeletionRequest::with(['campaign', 'user'])
                            ->pending()
                            ->latest()
                            ->paginate(10);
                    @endphp

                    @if($deletionRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Campagne</th>
                                    <th>Demandeur</th>
                                    <th>Raison</th>
                                    <th>Date de demande</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deletionRequests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($request->campaign->image_url)
                                                <img src="{{ Storage::url($request->campaign->image_url) }}" 
                                                     alt="{{ $request->campaign->name }}" 
                                                     class="rounded me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-leaf text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $request->campaign->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $request->campaign->category }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $request->user->name }}</td>
                                    <td>{{ $request->reason ?? 'Aucune raison fournie' }}</td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $request->id }}">
                                                <i class="fas fa-check"></i> Approuver
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $request->id }}">
                                                <i class="fas fa-times"></i> Rejeter
                                            </button>
                                        </div>

                                        <!-- Modal pour l'approbation -->
                                        <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Approuver la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('backend.campaigns.process-deletion-request', $request) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir approuver la suppression de la campagne <strong>"{{ $request->campaign->name }}"</strong> ?</p>
                                                            <div class="mb-3">
                                                                <label for="admin_notes_approve{{ $request->id }}" class="form-label">Notes (optionnel)</label>
                                                                <textarea class="form-control" id="admin_notes_approve{{ $request->id }}" 
                                                                          name="admin_notes" rows="3" placeholder="Notes pour le demandeur..."></textarea>
                                                            </div>
                                                            <input type="hidden" name="action" value="approve">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-success">Confirmer l'approbation</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal pour le rejet -->
                                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rejeter la demande</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('backend.campaigns.process-deletion-request', $request) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir rejeter la demande de suppression pour la campagne <strong>"{{ $request->campaign->name }}"</strong> ?</p>
                                                            <div class="mb-3">
                                                                <label for="admin_notes_reject{{ $request->id }}" class="form-label">Raison du rejet</label>
                                                                <textarea class="form-control" id="admin_notes_reject{{ $request->id }}" 
                                                                          name="admin_notes" rows="3" placeholder="Pourquoi rejetez-vous cette demande ?" required></textarea>
                                                            </div>
                                                            <input type="hidden" name="action" value="reject">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $deletionRequests->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h4 class="text-muted">Aucune demande en attente</h4>
                        <p class="text-muted">Toutes les demandes de suppression ont été traitées.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection