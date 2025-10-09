@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.events.index') }}">Gestion des Événements</a></li>
    <li class="breadcrumb-item active">Événements rejetés</li>
@endsection
@extends('backend.layouts.app')

@section('title', 'Événements rejetés')

@section('content')
<style>
    .rejected-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .rejected-header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .rejected-icon {
        width: 32px;
        height: 32px;
    background: linear-gradient(135deg, #EB442C 0%, #c82333 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        box-shadow: 0 2px 6px rgba(220,53,69,0.18);
    }
    .rejected-title-text h1 {
        margin: 0;
        font-size: 23px;
        font-weight: 600;
    color: #EB442C;
    }
    .rejected-title-text p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }
    .rejected-list-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }
    .rejected-table thead th {
        background: #f8f9fa;
        border: none;
        padding: 0.75rem 1.2rem;
        font-weight: 500;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #dc3545;
    }
    .rejected-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }
    .rejected-table tbody tr:hover {
        background: #f8d7da;
        transform: scale(1.01);
    }
    .rejected-table tbody td {
        padding: 0.85rem 1.2rem;
        vertical-align: middle;
        border: none;
        font-size: 13px;
    }
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
    color: #EB442C;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin: 0 2px;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 4px rgba(220,53,69,0.04);
        text-decoration: none;
    }
    .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #EB442C 0%, #c82333 100%);
    color: #fff;
    border-color: #EB442C;
    box-shadow: 0 2px 8px rgba(235,68,44,0.10);
    }
    .pagination .page-link:hover {
    background: #ffe5e0;
    color: #EB442C;
    }
    .pagination .page-item.disabled .page-link {
        color: #bdbdbd;
        background: #f4f4f4;
        border-color: #e0e0e0;
        cursor: not-allowed;
    }
    @media (max-width: 768px) {
        .rejected-header-content { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="rejected-header">
        <div class="rejected-header-content">
            <div class="rejected-icon">
                <i class="fas fa-times"></i>
            </div>
            <div class="rejected-title-text">
                <h1>Événements rejetés</h1>
                <p>Liste des événements qui ont été rejetés par l'administration.</p>
            </div>
        </div>
    </div>

    <!-- Rejected Events Table -->
    <div class="rejected-list-card">
        <div class="card-body p-0">
            <table class="table rejected-table align-middle mb-0">
                <thead>
                    <tr>

                        <th><strong>Titre</strong></th>
                        <th><strong>Organisateur</strong></th>
                        <th><strong>Date</strong></th>
                        <th><strong>Lieu</strong></th>
                        <th><strong>Actions</strong></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->user->name ?? '-' }}</td>
                        <td>{{ $event->date ? $event->date->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $event->location->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('backend.events.show', $event->id) }}" class="btn btn-sm btn-danger" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucun événement rejeté.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 mb-4">
        {{ $events->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
