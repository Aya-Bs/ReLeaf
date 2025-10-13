@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.events.index') }}">Événements</a></li>
    <li class="breadcrumb-item active">Demandes en attente</li>
@endsection
@extends('backend.layouts.app')

@section('title', 'Demandes d\'événements en attente')

@section('content')
<style>
    /* Force custom pagination style and override Bootstrap */
    .pagination .page-link,
    .pagination .page-item .page-link {
        color: #F8B324 !important;
        background: #f8f9fa !important;
        border: 1px solid #e0e0e0 !important;
        border-radius: 8px !important;
        padding: 0.5rem 1rem !important;
        margin: 0 2px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s !important;
        box-shadow: 0 1px 4px rgba(248,179,36,0.04) !important;
        text-decoration: none !important;
    }
    .pagination .page-item.active .page-link,
    .pagination .active > .page-link {
        background: linear-gradient(135deg, #F8B324 0%, #e09e14 100%) !important;
        color: #fff !important;
        border-color: #F8B324 !important;
        box-shadow: 0 2px 8px rgba(248,179,36,0.10) !important;
    }
    .pagination .page-link:hover {
        background: #fff7e3 !important;
        color: #F8B324 !important;
    }
    .pagination .page-item.disabled .page-link {
        color: #bdbdbd !important;
        background: #f4f4f4 !important;
        border-color: #e0e0e0 !important;
        cursor: not-allowed !important;
    }
    .pending-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .pending-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
    }
    .pending-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .pending-icon {
        width: 32px;
        height: 32px;
    background: linear-gradient(135deg, #F8B324 0%, #e09e14 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 2px 6px rgba(255, 152, 0, 0.18);
    }
    .pending-title-text h1 {
        margin: 0;
        font-size: 23px;
        font-weight: 600;
    color: #F8B324;
    /* Custom pagination styles (yellow-orange) */
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
        color: #F8B324;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin: 0 2px;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 4px rgba(248,179,36,0.04);
        text-decoration: none;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #F8B324 0%, #e09e14 100%);
        color: #fff;
        border-color: #F8B324;
        box-shadow: 0 2px 8px rgba(248,179,36,0.10);
    }
    .pagination .page-link:hover {
        background: #fff7e3;
        color: #F8B324;
    }
    .pagination .page-item.disabled .page-link {
        color: #bdbdbd;
        background: #f4f4f4;
        border-color: #e0e0e0;
        cursor: not-allowed;
    }
    }
    .pending-title-text p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }
    .pending-stats-grid {
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
    .stat-card.orange .stat-label { color: #ff9800; }
    .stat-card.orange .stat-icon { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); }
    .stat-card.orange .stat-number { color: #ff9800; }
    .stat-number {
        font-size: 22px;
        font-weight: 600;
        line-height: 1;
    }
    .pending-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }
    .pending-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .pending-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        transform: translateY(-4px);
    }
    .pending-card-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }
    .pending-card-body {
        padding: 1.25rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }
    .pending-card-title {
        font-size: 17px;
        font-weight: 600;
    color: #ffc107;
        margin-bottom: 0.5rem;
    }
    .pending-card-title-Z {
        font-size: 17px;
        font-weight: 600;
    color: #2d5a27;
        margin-bottom: 0.5rem;
    }
    .pending-card-meta {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    .pending-card-actions {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: auto;
    }
    @media (max-width: 768px) {
        .pending-header-content { flex-direction: column; align-items: flex-start; }
        .pending-grid { grid-template-columns: 1fr; }
        .pending-stats-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="pending-header">
        <div class="pending-header-content">
            <div class="pending-title-section">
                <div class="pending-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="pending-title-text">
                    <h1 style="display:flex; align-items:center; gap:1rem;">
                        Demandes d'événements en attente
                        
                    </h1>
                    <p>Modérez et gérez les demandes d'événements en attente de validation.</p>
                </div>
            </div>
        </div>
    </div>



    <!-- Pending Events Grid -->
    <div class="pending-grid">
        @forelse($events as $event)
            @php
                $hasPhoto = $event->images && is_array($event->images) && count($event->images) > 0;
                $img = $hasPhoto ? asset('storage/' . $event->images[0]) : null;
            @endphp
            <div class="pending-card">
                @if($hasPhoto)
                    <img src="{{ $img }}" class="pending-card-img" alt="Image événement">
                @else
                    <div class="pending-card-img d-flex align-items-center justify-content-center" style="height:180px; background: #f8f9fa; color: #F8B324; font-size: 48px;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                @endif
                <div class="pending-card-body">
                    <div class="pending-card-title-Z">{{ $event->title }}</div>
                    <div class="pending-card-meta">
                        <i class="fas fa-user me-1"></i> {{ $event->user->name ?? '-' }}<br>
                        <i class="fas fa-calendar-alt me-1"></i> {{ $event->date ? $event->date->format('d/m/Y H:i') : '-' }}<br>
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $event->location->name ?? '-' }}
                    </div>
                    <div class="pending-card-actions">
                        <a href="{{ route('backend.events.show', $event->id) }}" class="btn btn-outline-success btn-sm" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('backend.events.approve', $event->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-eco btn-sm" onclick="return confirm('Approuver cet événement ?')" title="Approuver">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('backend.events.reject', $event->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Rejeter cet événement ?')" title="Rejeter">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted" style="grid-column: 1/-1; padding: 3rem 0; font-size: 18px;">
                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                Aucune demande en attente.
            </div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
