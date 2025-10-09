@extends('backend.layouts.app')

@section('title', 'Demandes de Sponsoring en Attente')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Demandes de Sponsoring en Attente</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des demandes</h6>
        </div>
        <div class="card-body">
            @if($pendingSponsors->isEmpty())
            <p>Aucune demande de sponsoring en attente.</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Type</th>
                            <th>Date de la demande</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingSponsors as $sponsor)
                        <tr>
                            <td>{{ $sponsor->company_name }}</td>
                            <td>{{ $sponsor->contact_email }}</td>
                            <td>{{ $sponsor->contact_phone }}</td>
                            <td>{{ $sponsor->sponsorship_type }}</td>
                            <td>{{ $sponsor->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('backend.sponsors.show', $sponsor) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <form action="{{ route('backend.sponsors.validate', $sponsor) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Accepter
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal-{{ $sponsor->id }}">
                                    <i class="fas fa-times"></i> Rejeter
                                </button>
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal-{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel-{{ $sponsor->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectModalLabel-{{ $sponsor->id }}">Rejeter le Sponsor</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('backend.sponsors.reject', $sponsor) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="rejection_reason">Raison du rejet</label>
                                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Rejeter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $pendingSponsors->links() }}
            @endif
        </div>
    </div>
</div>
@endsection