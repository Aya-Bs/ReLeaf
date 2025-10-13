@extends('backend.layouts.app')

@section('content-header')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-certificate me-2"></i>
                    Gestion des Certifications
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item active">Certifications</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="btn-group">
                    <button class="btn btn-outline-secondary" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Actualiser
                    </button>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Événement</label>
                            <select name="event_id" class="form-select">
                                <option value="">Tous les événements</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="">Tous les types</option>
                                <option value="participation" {{ request('type') === 'participation' ? 'selected' : '' }}>Participation</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Utilisateur</label>
                            <select name="user_id" class="form-select">
                                <option value="">Tous les utilisateurs</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-eco d-block">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistiques rapides -->
            @php
                $totalCertificates = $certifications->total();
                $todayCertificates = \App\Models\Certification::whereDate('date_awarded', today())->count();
                $totalPoints = \App\Models\Certification::sum('points_earned');
                $avgPoints = $totalCertificates > 0 ? round($totalPoints / $totalCertificates, 1) : 0;
            @endphp
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ $totalCertificates }}</h3>
                            <small>Total Certificats</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>{{ $todayCertificates }}</h3>
                            <small>Délivrés Aujourd'hui</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>{{ $totalPoints }}</h3>
                            <small>Points Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3>{{ $avgPoints }}</h3>
                            <small>Moyenne Points</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Réservations en attente de certificat -->
            @if($pendingReservations->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Réservations Confirmées - Certificats à Accorder ({{ $pendingReservations->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-warning">
                                    <tr>
                                        <th>Participant</th>
                                        <th>Événement</th>
                                        <th>Date de confirmation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingReservations as $reservation)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $reservation->user->avatar_url }}" alt="Avatar" class="rounded-circle me-2" width="32">
                                                    <div>
                                                        <strong>{{ $reservation->user->name }}</strong><br>
                                                        <small class="text-muted">{{ $reservation->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $reservation->event->title }}</strong><br>
                                                <small class="text-muted">{{ $reservation->event->date->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                {{ $reservation->confirmed_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.certificates.grant', $reservation) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('Accorder un certificat à {{ $reservation->user->name }} pour l\'événement {{ $reservation->event->title }} ?')">
                                                        <i class="fas fa-certificate me-1"></i>Accorder Certificat
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Table des certifications -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="fas fa-certificate me-2"></i>
                            Certificats Accordés ({{ $certifications->total() }})
                        </h5>
                    </div>
                    
                    @if($certifications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Participant</th>
                                        <th>Événement</th>
                                        <th>Type</th>
                                        <th>Points</th>
                                        <th>Délivré le</th>
                                        <th>Délivré par</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($certifications as $certification)
                                        <tr class="certificate-row">
                                            <td>
                                                <span class="badge bg-eco font-monospace">{{ $certification->certificate_code }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $certification->reservation->user->avatar_url }}" alt="Avatar" class="rounded-circle me-2" width="32">
                                                    <div>
                                                        <strong>{{ $certification->reservation->user->name }}</strong><br>
                                                        <small class="text-muted">{{ $certification->reservation->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $certification->reservation->event->title }}</strong><br>
                                                <small class="text-muted">{{ $certification->reservation->event->date->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($certification->type) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $certification->points_earned }}</span>
                                            </td>
                                            <td>
                                                {{ $certification->date_awarded->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $certification->issuedBy->avatar_url }}" alt="Avatar" class="rounded-circle me-2" width="24">
                                                    {{ $certification->issuedBy->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('user.certificates.view', $certification->certificate_code) }}" 
                                                       class="btn btn-outline-info btn-sm" target="_blank" title="Voir le certificat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('user.certificates.download', $certification->certificate_code) }}" 
                                                       class="btn btn-outline-success btn-sm" title="Télécharger PDF">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <a href="{{ route('certificates.verify.code', $certification->certificate_code) }}" 
                                                       class="btn btn-outline-warning btn-sm" target="_blank" title="Vérifier le certificat">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $certifications->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune certification trouvée</h5>
                            <p class="text-muted">Les certifications apparaîtront ici une fois que les réservations seront confirmées.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations utiles -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        À propos des certifications
                    </h6>
                    <ul class="mb-0">
                        <li>🎯 Les certificats sont <strong>accordés manuellement</strong> par l'administrateur</li>
                        <li>🔒 Chaque certificat a un <strong>code unique</strong> impossible à falsifier</li>
                        <li>📄 Les utilisateurs peuvent <strong>télécharger</strong> leurs certificats en PDF</li>
                        <li>🛡️ Système de <strong>vérification publique</strong> pour valider l'authenticité</li>
                        <li>📧 <strong>Email automatique</strong> envoyé à l'utilisateur lors de l'accord du certificat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-eco {
    background-color: #2d5a27;
}
.text-eco {
    color: #2d5a27;
}
.btn-eco {
    background-color: #2d5a27;
    border-color: #2d5a27;
    color: white;
}
.btn-eco:hover {
    background-color: #234420;
    border-color: #234420;
    color: white;
}
.certificate-row:hover {
    background-color: #f8f9fa;
}
</style>
@endpush
@endsection
