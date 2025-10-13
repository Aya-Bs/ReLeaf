@extends('backend.layouts.app')
@section('title', 'Liste des lieux')


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Locations</li>
@endsection


@section('content')
<style>
    /* Modern styles from events page */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.25rem;
        margin-top: 1.5rem;
        margin-bottom: 0;
        padding-left: 0;
        list-style: none;
    }
    .pagination li {
        display: inline-block;
    }
    .pagination .page-link {
        color: #2d5a27;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin: 0 2px;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 4px rgba(45,90,39,0.04);
        text-decoration: none;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
        color: #fff;
        border-color: #2d5a27;
        box-shadow: 0 2px 8px rgba(45,90,39,0.10);
    }
    .pagination .page-link:hover {
        background: #e9f5e6;
        color: #2d5a27;
    }
    .pagination .page-item.disabled .page-link {
        color: #bdbdbd;
        background: #f4f4f4;
        border-color: #e0e0e0;
        cursor: not-allowed;
    }
    
    /* Header Styles */
    .locations-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .locations-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }
    
    .locations-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .locations-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 2px 6px rgba(45, 90, 39, 0.18);
    }
    
    .locations-title-text h1 {
        margin: 0;
        font-size: 23px;
        font-weight: 600;
        color: #2d5a27;
    }
    
    .locations-title-text p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    
    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .stat-label {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        font-weight: 400;
    }
    
    .stat-number {
        font-size: 22px;
        font-weight: 600;
        line-height: 1;
    }
    
    /* Stat Card Colors */
    .stat-card.blue .stat-label { color: #0066cc; }
    .stat-card.blue .stat-icon { background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); }
    .stat-card.blue .stat-number { color: #0066cc; }
    
    .stat-card.orange .stat-label { color: #ff9800; }
    .stat-card.orange .stat-icon { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); }
    .stat-card.orange .stat-number { color: #ff9800; }
    
    .stat-card.green .stat-label { color: #2d5a27; }
    .stat-card.green .stat-icon { background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%); }
    .stat-card.green .stat-number { color: #2d5a27; }
    
    .stat-card.red .stat-label { color: #dc3545; }
    .stat-card.red .stat-icon { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
    .stat-card.red .stat-number { color: #dc3545; }
    
    /* Table Styles */
    .locations-list-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    
    .locations-list-header {
        padding: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .locations-list-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #2d5a27;
    }
    
    .locations-list-header i {
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
    
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .badge-modern.badge-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 1px solid #b1dfbb;
    }
    
    .badge-modern.badge-secondary {
        background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
        color: #383d41;
        border: 1px solid #c6c8ca;
    }
    
    .badge-modern.badge-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 1px solid #f1b0b7;
    }
    
    .badge-modern.badge-light {
        background: linear-gradient(135deg, #fdfdfe 0%, #f8f9fa 100%);
        color: #495057;
        border: 1px solid #e9ecef;
    }
    
    .events-count-badge {
        background: linear-gradient(135deg, var(--eco-green) 0%, var(--eco-light-green) 100%);
        color: white;
        border-radius: 20px;
        padding: 0.6rem 1rem;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 70px;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(32, 199, 26, 0.3);
        transition: all 0.3s ease;
    }
    
    .events-count-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(32, 199, 26, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 36px;
        color: #adb5bd;
    }
    
    .empty-state h4 {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #adb5bd;
        margin: 0;
    }
    
    @media (max-width: 768px) {
        .locations-header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .table-responsive {
            border-radius: 3;
        }
        
        .events-count-badge {
            min-width: 60px;
            padding: 0.5rem 0.8rem;
            font-size: 12px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="locations-header">
        <div class="locations-header-content">
            <div class="locations-title-section">
                <div class="locations-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="locations-title-text">
                    <h1>Gestion des Lieux</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">TOTAL LIEUX</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
            <div class="stat-number">{{ $allLocations->count() }}</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">RÉSERVÉS</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-lock"></i>
                </div>
            </div>
            <div class="stat-number">{{ $reservedCount }}</div>
        </div>

        <div class="stat-card green">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">LIBRES</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-unlock"></i>
                </div>
            </div>
            <div class="stat-number">{{ $notReservedCount }}</div>
        </div>

        <div class="stat-card red">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">EN RÉPARATION</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
            <div class="stat-number">{{ $inRepairCount }}</div>
        </div>
    </div>

    <!-- Locations List -->
    <div class="locations-list-card">
        <div class="locations-list-header">
            <i class="fas fa-list"></i>
            <h3>Liste des lieux</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th><strong>Nom</strong></th>
                            <th><strong>Ville</strong></th>
                            <th><strong>Adresse</strong></th>
                            <th><strong>Capacité</strong></th>
                            <th><strong>Statut</strong></th>
                            <th><strong>En Réparation</strong></th>
                            <th><strong>Événements</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($locations->count() > 0)
                            @foreach($locations as $location)
                                <tr onclick="window.location='{{ route('backend.locations.show', $location->id) }}'">
                                    <td style="font-size:14px">{{ $location->name }}</td>
                                    <td>{{ $location->city }}</td>
                                    <td>{{ $location->address }}</td>
                                    <td>
                                        <span class="badge-modern badge-light">
                                            <i class="fas fa-users"></i>
                                            {{ $location->capacity ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($location->reserved)
                                            <span class="badge-modern badge-secondary">
                                                <i class="fas fa-lock"></i> Réservé
                                            </span>
                                        @else
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-unlock"></i> Libre
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($location->in_repair)
                                            <span class="badge-modern badge-danger">
                                                <i class="fas fa-tools"></i> En réparation
                                            </span>
                                        @else
                                            <span class="badge-modern badge-light-success">
                                                <i class="fas fa-check"></i> OK
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.locations.show', $location->id) }}" class="events-count-badge">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ $location->events_count ?? 0 }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h4>Aucun lieu trouvé</h4>
                                    <p>Aucun lieu n'a été créé pour le moment.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-4">
                {{ $locations->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection