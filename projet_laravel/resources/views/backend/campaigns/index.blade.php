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

            <!-- Statistiques & Graphiques des Campagnes -->
            @php
                $allCampaigns = \App\Models\Campaign::select('status','category','goal','funds_raised','created_at')->get();

                $totalCampaigns = $allCampaigns->count();
                $activeCampaigns = $allCampaigns->where('status','active')->count();
                $completedCampaigns = $allCampaigns->where('status','completed')->count();
                $totalFunds = $allCampaigns->sum('funds_raised');

                $byStatus = $allCampaigns->groupBy('status')->map->count();
                $byCategory = $allCampaigns->groupBy('category')->map->count();

                $months = collect(range(5,0))->map(fn($i)=>now()->subMonths($i)->format('Y-m'));
                $byMonth = $months->mapWithKeys(fn($m)=>[
                    $m => $allCampaigns->filter(fn($c)=>optional($c->created_at)->format('Y-m') === $m)->count()
                ]);
            @endphp

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2" style="color: var(--eco-green, #2d5a27)"></i>
                        Statistiques des Campagnes
                    </h3>
                </div>
                <div class="card-body">
                    <!-- KPI Cards -->
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                                <div class="text-eco fw-bold">Total</div>
                                <div class="fs-4 fw-bold">{{ number_format($totalCampaigns) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                                <div class="text-eco fw-bold">Actives</div>
                                <div class="fs-4 fw-bold">{{ number_format($activeCampaigns) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                                <div class="text-eco fw-bold">Terminées</div>
                                <div class="fs-4 fw-bold">{{ number_format($completedCampaigns) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-2 rounded-3 border text-center kpi-box" style="border-color:#e9ecef;">
                                <div class="text-eco fw-bold">Fonds collectés</div>
                                <div class="fs-4 fw-bold">{{ number_format($totalFunds, 0, ',', ' ') }} TND</div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <h6 class="text-eco fw-bold mb-2">Répartition par Statut</h6>
                            <div class="chart-wrap"><canvas id="statusChart" height="120"></canvas></div>
                        </div>
                        <div class="col-lg-4">
                            <h6 class="text-eco fw-bold mb-2">Répartition par Catégorie</h6>
                            <div class="chart-wrap"><canvas id="categoryChart" height="120"></canvas></div>
                        </div>
                        <div class="col-lg-4">
                            <h6 class="text-eco fw-bold mb-2">Créations (6 derniers mois)</h6>
                            <div class="chart-wrap"><canvas id="monthlyChart" height="120"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>

@isset($campaigns)
<!-- Liste des Campagnes (style moderne, inspiré des événements) -->
<div class="campaigns-list-card">
    <div class="campaigns-list-header">
        <i class="fas fa-list"></i>
        <h3>Liste des Campagnes</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th><strong>Nom</strong></th>
                        <th><strong>Organisateur</strong></th>
                        <th><strong>Dates</strong></th>
                        <th><strong>Catégorie</strong></th>
                        <th><strong>Statut</strong></th>
                        <th><strong>Visibilité</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @if($campaigns->count() > 0)
                        @foreach($campaigns as $campaign)
                        <tr onclick="window.location='{{ route('backend.campaigns.show', $campaign) }}'">
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($campaign->image_url)
                                        <img src="{{ Storage::url($campaign->image_url) }}"
                                             alt="{{ $campaign->name }}"
                                             class="rounded me-3"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                             style="width: 40px; height: 40px;">
                                             <i class="fas fa-leaf text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="campaign-title">{{ $campaign->name }}</div>
                                </div>
                            </td>
                            <td>{{ $campaign->organizer->name ?? '—' }}</td>
                            <td>
                                <span class="badge-modern badge-light">
                                    <i class="far fa-calendar"></i>
                                    {{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>{{ ucfirst($campaign->category) }}</td>
                            <td>
                                @php $st = $campaign->status; @endphp
                                @if($st === 'active')
                                    <span class="badge-modern badge-success"><i class="fas fa-check"></i> Active</span>
                                @elseif($st === 'inactive')
                                    <span class="badge-modern badge-secondary"><i class="fas fa-pause"></i> Inactive</span>
                                @elseif($st === 'completed')
                                    <span class="badge-modern badge-primary"><i class="fas fa-flag-checkered"></i> Terminée</span>
                                @elseif($st === 'cancelled')
                                    <span class="badge-modern badge-danger"><i class="fas fa-ban"></i> Annulée</span>
                                @else
                                    <span class="badge-modern badge-light">{{ $st }}</span>
                                @endif
                            </td>
                            <td>
                                @if($campaign->visibility)
                                    <span class="badge-modern badge-success"><i class="fas fa-eye"></i> Publique</span>
                                @else
                                    <span class="badge-modern badge-warning"><i class="fas fa-eye-slash"></i> Privée</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color:#bdbdbd; font-size:16px;">
                                <i class="fas fa-search-minus fa-lg mb-2" style="display:block;"></i>
                                Aucune campagne à afficher.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center p-4">
            {{ $campaigns->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endisset

@push('styles')
<style>
    /* Campaigns modern list (inspiré des événements backend) */
    .campaigns-list-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
        margin-bottom: 1.25rem;
    }
    .campaigns-list-header {
        padding: 1.25rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .campaigns-list-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #2d5a27;
    }
    .campaigns-list-header i {
        color: #2d5a27;
        font-size: 20px;
    }
    .table-modern {
        margin: 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .table-modern thead th {
        background: #f8f9fa;
        border: none;
        padding: 0.75rem 1.2rem;
        font-weight: 500;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2d5a27;
    }
    .table-modern tbody tr {
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }
    .table-modern tbody tr:hover {
        background: #f4f8f4;
        transform: scale(1.01);
    }
    .table-modern tbody td {
        padding: 0.85rem 1.2rem;
        vertical-align: middle;
        border: none;
        font-size: 13px;
    }
    .campaign-title {
        font-weight: 500;
        color: #2d5a27;
        font-size: 15px;
    }

    .badge-modern {
        padding: 0.45rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid transparent;
    }
    .badge-modern.badge-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        color: #856404;
        border-color: #ffeaa7;
    }
    .badge-modern.badge-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-color: #b1dfbb;
    }
    .badge-modern.badge-secondary {
        background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
        color: #383d41;
        border-color: #c6c8ca;
    }
    .badge-modern.badge-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-color: #f1b0b7;
    }
    .badge-modern.badge-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
        border-color: #abdde5;
    }
    .badge-modern.badge-light {
        background: linear-gradient(135deg, #fdfdfe 0%, #f8f9fa 100%);
        color: #495057;
        border-color: #e9ecef;
    }
    .badge-modern.badge-primary {
        background: linear-gradient(135deg, #cfe2ff 0%, #9ec5fe 100%);
        color: #084298;
        border-color: #9ec5fe;
    }
</style>
@endpush
            <!-- Duplicate campaigns list removed -->

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
                    <div class="row g-3">
                        @foreach($deletionRequests as $request)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        @if($request->campaign->image_url)
                                            <img src="{{ Storage::url($request->campaign->image_url) }}" alt="{{ $request->campaign->name }}" class="rounded me-3" style="width:56px; height:56px; object-fit:cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width:56px; height:56px;">
                                                <i class="fas fa-leaf text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1">{{ $request->campaign->name }}</h6>
                                            <small class="text-muted">{{ ucfirst($request->campaign->category) }} · {{ $request->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Demandeur</small>
                                        <span class="fw-semibold">{{ $request->user->name }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Raison</small>
                                        <div style="-webkit-line-clamp:3; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">
                                            {{ $request->reason ?? 'Aucune raison fournie' }}
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-success btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">
                                            <i class="fas fa-check me-1"></i> Approuver
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                            <i class="fas fa-times me-1"></i> Rejeter
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal pour l'approbation -->
                            <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approuver la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ url('admin/campaigns/deletion-requests/'.$request->id.'/process') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir approuver la suppression de la campagne <strong>"{{ $request->campaign->name }}"</strong> ?</p>
                                                <div class="mb-3">
                                                    <label for="admin_notes_approve{{ $request->id }}" class="form-label">Notes (optionnel)</label>
                                                    <textarea class="form-control" id="admin_notes_approve{{ $request->id }}" name="admin_notes" rows="3" placeholder="Notes pour le demandeur..."></textarea>
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
                                        <form action="{{ url('admin/campaigns/deletion-requests/'.$request->id.'/process') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Êtes-vous sûr de vouloir rejeter la demande de suppression pour la campagne <strong>"{{ $request->campaign->name }}"</strong> ?</p>
                                                <div class="mb-3">
                                                    <label for="admin_notes_reject{{ $request->id }}" class="form-label">Raison du rejet</label>
                                                    <textarea class="form-control" id="admin_notes_reject{{ $request->id }}" name="admin_notes" rows="3" placeholder="Pourquoi rejetez-vous cette demande ?" required></textarea>
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
                        </div>
                        @endforeach
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

@push('styles')
<style>
    .text-eco { color: var(--eco-green, #2d5a27) !important; }
    .card-eco .card-title { color: var(--eco-green, #2d5a27); font-weight: 700; }

    /* KPI compacts */
    .kpi-box .text-eco { font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
    .kpi-box .fs-4 { font-size: 1.25rem !important; }

    /* Charts compacts */
    .chart-wrap { height: 160px; }
    .chart-wrap canvas { max-height: 160px !important; width: 100% !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const byStatus = @json($byStatus ?? []);
        const byCategory = @json($byCategory ?? []);
        const months = @json($months ?? collect());
        const byMonth = @json($byMonth ?? []);

        // Helper palette
        const eco = getComputedStyle(document.documentElement).getPropertyValue('--eco-green') || '#2d5a27';
        const ecoLight = getComputedStyle(document.documentElement).getPropertyValue('--eco-light-green') || '#4a7c59';
        const palette = ['#2d5a27', '#4a7c59', '#6c757d', '#17a2b8', '#ffc107', '#dc3545', '#20c997'];

        // Status Pie
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(byStatus),
                    datasets: [{
                        data: Object.values(byStatus),
                        backgroundColor: palette.slice(0, Object.keys(byStatus).length),
                        borderWidth: 0
                    }]
                },
                options: {
                    plugins: { legend: { position: 'bottom' } },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Category Bar
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(byCategory).map(c => titleCase(c)),
                    datasets: [{
                        label: 'Campagnes',
                        data: Object.values(byCategory),
                        backgroundColor: eco,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Monthly Line
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(byMonth).map(m => m),
                    datasets: [{
                        label: 'Créations',
                        data: Object.values(byMonth),
                        borderColor: eco,
                        backgroundColor: ecoLight,
                        tension: 0.25,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: eco
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function titleCase(s) { return (s || '').toString().replace(/_/g,' ').replace(/\w\S*/g, t => t.charAt(0).toUpperCase() + t.substr(1).toLowerCase()); }
    })();
</script>
@endpush