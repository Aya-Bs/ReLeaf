@extends('backend.layouts.app')

@section('title', $campaign->name)
@section('page-title', 'Détails de la campagne')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.campaigns.index') }}">Campagnes</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($campaign->name, 30) }}</li>
@endsection

@section('content')
<style>
    /* Header (style inspiré des événements) */
    .campaign-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .campaign-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .campaign-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .campaign-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        box-shadow: 0 2px 6px rgba(45, 90, 39, 0.18);
    }
    .campaign-title-text h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #2d5a27;
    }
    .campaign-title-text p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }

    /* Cover (image de couverture) */
    .cover-wrap {
        border-radius: 16px;
        overflow: hidden;
        background: #f8f9fa;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
    }
    .cover-img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        display: block;
    }
    .cover-placeholder {
        width: 100%;
        height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .cover-placeholder i {
        font-size: 4rem;
        opacity: 0.5;
    }

    /* Icon cards (KPIs en haut) */
    .icon-cards-section {
        margin-bottom: 1.5rem;
    }
    .icon-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .icon-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 2px solid #f0f0f0;
        text-align: center;
        transition: all 0.3s ease;
    }
    .icon-card:hover {
        border-color: #2d5a27;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(45, 90, 39, 0.15);
    }
    .icon-card-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 0.75rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .icon-card-icon i {
        font-size: 1.35rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .icon-card:hover .icon-card-icon {
        background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
    }
    .icon-card:hover .icon-card-icon i {
        color: white;
    }
    .icon-card-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.15rem;
    }
    .icon-card-value {
        font-size: 16px;
        font-weight: 700;
        color: #2d5a27;
    }

    /* Info cards */
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        height: 100%;
    }
    .info-section { margin-bottom: 1.25rem; }
    .info-section:last-child { margin-bottom: 0; }
    .info-section h4 {
        color: #2d5a27;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .info-section h4 i { color: #2d5a27; }
    .info-content { color: #495057; font-size: 14px; line-height: 1.55; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.85rem 1rem;
        margin-top: 0.35rem;
    }
    .info-item { display: flex; flex-direction: column; gap: 0.2rem; }
    .info-label {
        font-size: 12px; font-weight: 600; color: #6c757d;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .info-value { font-size: 14px; font-weight: 500; color: #2d5a27; }

    /* Badges */
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
    .badge-modern.badge-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border-color: #ffeaa7;
    }
    .badge-modern.badge-primary {
        background: linear-gradient(135deg, #cfe2ff 0%, #9ec5fe 100%);
        color: #084298;
        border-color: #9ec5fe;
    }
    .badge-modern.badge-light {
        background: linear-gradient(135deg, #fdfdfe 0%, #f8f9fa 100%);
        color: #495057;
        border-color: #e9ecef;
    }

    @media (max-width: 992px) {
        .cover-img, .cover-placeholder { height: 360px; }
    }
    @media (max-width: 768px) {
        .cover-img, .cover-placeholder { height: 300px; }
    }
    @media (max-width: 576px) {
        .cover-img, .cover-placeholder { height: 240px; }
    }
</style>

<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('backend.campaigns.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <!-- Header -->
    <div class="campaign-header">
        <div class="campaign-header-content">
            <div class="campaign-title-section">
                <div class="campaign-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="campaign-title-text">
                    <h1>{{ $campaign->name }}</h1>
                    <p>Détails de la campagne</p>
                </div>
            </div>
            <div>
                @if($campaign->status === 'active')
                    <span class="badge-modern badge-success"><i class="fas fa-check"></i> Active</span>
                @elseif($campaign->status === 'inactive')
                    <span class="badge-modern badge-secondary"><i class="fas fa-pause"></i> Inactive</span>
                @elseif($campaign->status === 'completed')
                    <span class="badge-modern badge-primary"><i class="fas fa-flag-checkered"></i> Terminée</span>
                @elseif($campaign->status === 'cancelled')
                    <span class="badge-modern badge-danger"><i class="fas fa-ban"></i> Annulée</span>
                @else
                    <span class="badge-modern badge-light">{{ $campaign->status }}</span>
                @endif
                @if(!$campaign->visibility)
                    <span class="badge-modern badge-warning ms-1"><i class="fas fa-eye-slash"></i> Non visible</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Icon Cards (en haut) -->
    <div class="icon-cards-section">
        <div class="icon-cards-container">
            <div class="icon-card">
                <div class="icon-card-icon"><i class="fas fa-boxes"></i></div>
                <div class="icon-card-label">Ressources</div>
                <div class="icon-card-value">{{ $campaign->resources_count ?? 0 }}</div>
            </div>
            <div class="icon-card">
                <div class="icon-card-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="icon-card-label">Événements</div>
                <div class="icon-card-value">{{ $campaign->events_count ?? 0 }}</div>
            </div>
            <div class="icon-card">
                <div class="icon-card-icon"><i class="fas fa-users"></i></div>
                <div class="icon-card-label">Participants</div>
                <div class="icon-card-value">{{ $campaign->participants_count ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Cover Image -->
    <div class="cover-wrap">
        @if($campaign->image_url)
            <img class="cover-img" src="{{ asset('storage/' . $campaign->image_url) }}" alt="{{ $campaign->name }}">
        @else
            <div class="cover-placeholder">
                <i class="fas fa-image"></i>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left: Details -->
        <div class="col-lg-8 mb-4">
            <div class="info-card">
                @if($campaign->description)
                <div class="info-section">
                    <h4><i class="fas fa-align-left"></i> Description</h4>
                    <div class="info-content">{{ $campaign->description }}</div>
                </div>
                @endif

                <div class="info-section">
                    <h4><i class="fas fa-info-circle"></i> Informations de base</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Catégorie</span>
                            <span class="info-value">
                                @switch($campaign->category)
                                    @case('reforestation') 🌲 Reforestation @break
                                    @case('nettoyage') 🧹 Nettoyage @break
                                    @case('sensibilisation') 📢 Sensibilisation @break
                                    @case('recyclage') ♻️ Recyclage @break
                                    @case('biodiversite') 🦋 Biodiversité @break
                                    @case('energie_renouvelable') ⚡ Énergie Renouvelable @break
                                    @default {{ ucfirst($campaign->category) }}
                                @endswitch
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date de début</span>
                            <span class="info-value">{{ $campaign->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date de fin</span>
                            <span class="info-value">{{ $campaign->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jours restants</span>
                            <span class="info-value">
                                @if($campaign->end_date->isFuture())
                                    J-{{ $campaign->days_remaining }}
                                @else
                                    Terminée
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h4><i class="fas fa-donate"></i> Finances</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Objectif</span>
                            <span class="info-value">
                                {{ $campaign->goal ? number_format($campaign->goal, 2, ',', ' ') . ' €' : 'Non défini' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Collecté</span>
                            <span class="info-value">{{ number_format($campaign->funds_raised, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                    @if($campaign->goal && $campaign->goal > 0)
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $campaign->funds_progress_percentage }}%"
                             aria-valuenow="{{ $campaign->funds_progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">
                        {{ number_format($campaign->funds_raised, 2, ',', ' ') }} € /
                        {{ number_format($campaign->goal, 2, ',', ' ') }} €
                    </small>
                    @endif
                </div>

                @if($campaign->environmental_impact)
                <div class="info-section">
                    <h4><i class="fas fa-seedling"></i> Impact environnemental</h4>
                    <div class="info-content">{{ $campaign->environmental_impact }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Meta + Actions -->
        <div class="col-lg-4 mb-4">
            <div class="info-card">
                <div class="info-section">
                    <h4><i class="fas fa-user"></i> Informations</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Organisateur</span>
                            <span class="info-value">{{ $campaign->organizer->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Créée le</span>
                            <span class="info-value">{{ $campaign->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mise à jour</span>
                            <span class="info-value">{{ $campaign->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Visibilité</span>
                            <span class="info-value">
                                @if($campaign->visibility)
                                    <span class="badge-modern badge-success"><i class="fas fa-eye"></i> Publique</span>
                                @else
                                    <span class="badge-modern badge-warning"><i class="fas fa-eye-slash"></i> Privée</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <div class="d-grid gap-2">
                        @if($campaign->visibility)
                        <form action="{{ route('campaigns.toggle-visibility', $campaign) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-eye-slash me-2"></i> Rendre privée
                            </button>
                        </form>
                        @else
                        <form action="{{ route('campaigns.toggle-visibility', $campaign) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-eye me-2"></i> Rendre publique
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection