@extends('backend.layouts.app')

@section('title', 'Gestion des Événements')
@section('page-title', 'Gestion des Événements')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item active">Événements</li>
@endsection

@section('content')
<style>
    /* Custom pagination styles */
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
    .events-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .events-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    position: relative;
    }
    
    .events-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .event-search-bar {
        display: flex;
        align-items: center;
        margin-left: auto;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.25rem 0.75rem;
        box-shadow: 0 1px 4px rgba(45,90,39,0.04);
        min-width: 220px;
        transition: border 0.2s;
    }
    .event-search-bar input[type="text"] {
        border: none;
        background: transparent;
        outline: none;
        font-size: 15px;
        color: #2d5a27;
        width: 100%;
        padding: 0.4rem 0.2rem;
    }
    .event-search-bar i {
        color: #3d7a37;
        font-size: 18px;
        margin-right: 0.5rem;
    }
    
    
    .events-icon {
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
    
    .events-title-text h1 {
    margin: 0;
    font-size: 23px;
    font-weight: 600;
    color: #2d5a27;
    }
    
    .events-title-text p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }
    

    

    
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
    
    .events-list-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    
    .events-list-header {
        padding: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .events-list-header h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #2d5a27;
    }
    
    .events-list-header i {
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
    
    .event-title {
        font-weight: 500;
        color: #2d5a27;
        font-size: 15px;
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
    
    .badge-modern.badge-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        color: #856404;
        border: 1px solid #ffeaa7;
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
    
    .badge-modern.badge-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        color: #0c5460;
        border: 1px solid #abdde5;
    }
    
    .badge-modern.badge-light {
        background: linear-gradient(135deg, #fdfdfe 0%, #f8f9fa 100%);
        color: #495057;
        border: 1px solid #e9ecef;
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
        .events-header-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .table-responsive {
            border-radius: 3;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="events-header">
        <div class="events-header-content">
            <div class="events-title-section">
                <div class="events-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="events-title-text">
                    <h1>Gestion des Événements</h1>
                </div>
            </div>
            <form class="event-search-bar" id="event-search-form" autocomplete="off" onsubmit="return false;">
                <i class="fas fa-search"></i>
                <input type="text" id="event-search-input" name="search" placeholder="Rechercher..." value="{{ request('search', '') }}" />
            </form>

             </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('event-search-input');
    const form = document.getElementById('event-search-form');
    let timer = null;
    // Submit on Enter (form submit)
    form && form.addEventListener('submit', function(e) {
        e.preventDefault();
        const value = input.value.trim();
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set('search', value);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
    // Debounced search on input
    input && input.addEventListener('input', function() {
        clearTimeout(timer);
        timer = setTimeout(function() {
            const value = input.value.trim();
            const url = new URL(window.location.href);
            if (value) {
                url.searchParams.set('search', value);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('page'); // Always reset to first page on new search
            window.location.href = url.toString();
        }, 350);
    });
});
</script>
   

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">Total Événements</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        <div class="stat-number">{{ $allEvents->count() }}</div>
        </div>

        <div class="stat-card orange">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">En Attente</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        <div class="stat-number">{{ $allEvents->where('status', 'pending')->count() }}</div>
        </div>

        <div class="stat-card green">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">Publiés</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        <div class="stat-number">{{ $allEvents->where('status', 'published')->count() }}</div>
        </div>

        <div class="stat-card red">
            <div class="stat-card-header">
                <div>
                    <div class="stat-label">Rejetés</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        <div class="stat-number">{{ $allEvents->whereIn('status', ['cancelled', 'rejected'])->count() }}</div>
        </div>
    </div>

    <!-- Events List -->
    <div class="events-list-card">
        <div class="events-list-header">
            <i class="fas fa-list"></i>
            <h3>Liste des Événements</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th><strong>Titre</strong></th>
                            <th><strong>Organisateur</strong></th>
                            <th><strong>Date</strong></th>
                            <th><strong>Lieu</strong></th>
                            <th><strong>Statut</strong></th>
                            <th><strong>Participants</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($events->count() > 0)
                            @foreach($events as $event)
                            <tr onclick="window.location='{{ route('backend.events.show', $event) }}'">
                                <td style="font-size:14px"> {{ $event->title }}</td>
                                <td>{{ $event->user->name }}</td>
                                <td>
                                    <span class="badge-modern badge-light">
                                        <i class="far fa-calendar"></i>
                                        {{ $event->date->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                    {{ $event->location ? $event->location->name : 'Inconnu' }}
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
    @php
        $reservedSeats = $event->reservations()->where('status', 'confirmed')->count();
        $availableSeats = $event->max_participants - $reservedSeats;
    @endphp
    {{ $reservedSeats }} / {{ $event->max_participants ?? '∞' }}
</span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color:#bdbdbd; font-size:16px;">
                                    <i class="fas fa-search-minus fa-lg mb-2" style="display:block;"></i>
                                    Aucun titre ne correspond à votre recherche.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center p-4">
                {{ $events->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection