@extends('backend.layouts.app')

@section('title', $resource->name)
@section('page-title', 'Détails de la ressource')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Tableau de bord</a></li>
    <li class="breadcrumb-item"><a href="{{ route('backend.resources.index') }}">Ressources</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($resource->name, 30) }}</li>
@endsection

@section('content')
<style>
    /* Header */
    .resource-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .resource-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .resource-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .resource-icon {
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
    .resource-title-text h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #2d5a27;
    }
    .resource-title-text p {
        margin: 0;
        color: #6c757d;
        font-size: 14px;
    }

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
    .badge-modern.badge-success { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724; border-color: #b1dfbb; }
    .badge-modern.badge-secondary { background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%); color: #383d41; border-color: #c6c8ca; }
    .badge-modern.badge-danger { background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); color: #721c24; border-color: #f1b0b7; }
    .badge-modern.badge-warning { background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); color: #856404; border-color: #ffeaa7; }
    .badge-modern.badge-primary { background: linear-gradient(135deg, #cfe2ff 0%, #9ec5fe 100%); color: #084298; border-color: #9ec5fe; }
    .badge-modern.badge-info { background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); color: #0c5460; border-color: #abdde5; }
    .badge-modern.badge-light { background: linear-gradient(135deg, #fdfdfe 0%, #f8f9fa 100%); color: #495057; border-color: #e9ecef; }

    /* Icon KPIs */
    .icon-cards-section { margin-bottom: 1.5rem; }
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
    .icon-card:hover { border-color: #2d5a27; transform: translateY(-4px); box-shadow: 0 8px 24px rgba(45, 90, 39, 0.15); }
    .icon-card-icon {
        width: 56px; height: 56px; margin: 0 auto 0.75rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .icon-card-icon i { font-size: 1.35rem; color: #6c757d; transition: all 0.3s ease; }
    .icon-card:hover .icon-card-icon { background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%); }
    .icon-card:hover .icon-card-icon i { color: white; }
    .icon-card-label { font-size: 13px; font-weight: 600; color: #495057; margin-bottom: 0.15rem; }
    .icon-card-value { font-size: 16px; font-weight: 700; color: #2d5a27; }

    /* Cover image */
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
        width: 100%; height: 420px;
        display: flex; align-items: center; justify-content: center;
        color: #adb5bd;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .cover-placeholder i { font-size: 4rem; opacity: 0.5; }

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
        display: flex; align-items: center; gap: 0.5rem;
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
    .info-label { font-size: 12px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-size: 14px; font-weight: 500; color: #2d5a27; }

    @media (max-width: 992px) { .cover-img, .cover-placeholder { height: 360px; } }
    @media (max-width: 768px) { .cover-img, .cover-placeholder { height: 300px; } }
    @media (max-width: 576px) { .cover-img, .cover-placeholder { height: 240px; } }
</style>

<div class="container-fluid">
    <!-- Back -->
    <div class="mb-3">
        <a href="{{ route('backend.resources.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <!-- Header -->
    <div class="resource-header">
        <div class="resource-header-content">
            <div class="resource-title-section">
                <div class="resource-icon"><i class="fas fa-box"></i></div>
                <div class="resource-title-text">
                    <h1>{{ $resource->name }}</h1>
                    <p>Détails de la ressource</p>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if($resource->status === 'needed')
                    <span class="badge-modern badge-warning"><i class="fas fa-exclamation-circle"></i> Nécessaire</span>
                @elseif($resource->status === 'pledged')
                    <span class="badge-modern badge-info"><i class="fas fa-hand-holding-heart"></i> Promis</span>
                @elseif($resource->status === 'received')
                    <span class="badge-modern badge-success"><i class="fas fa-check"></i> Reçu</span>
                @elseif($resource->status === 'in_use')
                    <span class="badge-modern badge-primary"><i class="fas fa-play"></i> En utilisation</span>
                @else
                    <span class="badge-modern badge-light">{{ $resource->status }}</span>
                @endif

                @if($resource->priority === 'urgent')
                    <span class="badge-modern badge-danger"><i class="fas fa-bolt"></i> Urgent</span>
                @elseif($resource->priority === 'high')
                    <span class="badge-modern badge-warning"><i class="fas fa-arrow-up"></i> Haute</span>
                @elseif($resource->priority === 'medium')
                    <span class="badge-modern badge-info"><i class="fas fa-minus"></i> Moyenne</span>
                @elseif($resource->priority === 'low')
                    <span class="badge-modern badge-success"><i class="fas fa-arrow-down"></i> Basse</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Icon Cards (en haut) -->
    <div class="icon-cards-section">
        <div class="icon-cards-container">
            <div class="icon-card">
                <div class="icon-card-icon"><i class="fas fa-balance-scale"></i></div>
                <div class="icon-card-label">Quantité</div>
                <div class="icon-card-value">{{ $resource->quantity_pledged }} / {{ $resource->quantity_needed }} {{ $resource->unit }}</div>
            </div>
            <div class="icon-card">
                <div class="icon-card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="icon-card-label">Manquant</div>
                <div class="icon-card-value">{{ $resource->missing_quantity }} {{ $resource->unit }}</div>
            </div>
            <div class="icon-card">
                <div class="icon-card-icon"><i class="fas fa-chart-line"></i></div>
                <div class="icon-card-label">Progression</div>
                <div class="icon-card-value">{{ $resource->progress_percentage }}%</div>
            </div>
        </div>
    </div>

    <!-- Cover Image -->
    <div class="cover-wrap">
        @if($resource->image_url)
            <img class="cover-img" src="{{ Storage::url($resource->image_url) }}" alt="{{ $resource->name }}">
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
                @if($resource->description)
                <div class="info-section">
                    <h4><i class="fas fa-align-left"></i> Description</h4>
                    <div class="info-content">{{ $resource->description }}</div>
                </div>
                @endif

                <div class="info-section">
                    <h4><i class="fas fa-info-circle"></i> Informations de base</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Type de ressource</span>
                            <span class="info-value">
                                @switch($resource->resource_type)
                                    @case('money') 💰 Argent @break
                                    @case('food') 🍕 Nourriture @break
                                    @case('clothing') 👕 Vêtements @break
                                    @case('medical') 🏥 Médical @break
                                    @case('equipment') 🔧 Équipement @break
                                    @case('human') 👥 Main d'œuvre @break
                                    @default 📦 {{ ucfirst($resource->resource_type) }}
                                @endswitch
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Catégorie</span>
                            <span class="info-value">
                                @switch($resource->category)
                                    @case('materiel') 📦 Matériel @break
                                    @case('financier') 💰 Financier @break
                                    @case('humain') 👥 Humain @break
                                    @case('technique') 🔧 Technique @break
                                    @default {{ ucfirst($resource->category) }}
                                @endswitch
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Unité</span>
                            <span class="info-value">{{ $resource->unit }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Fournisseur</span>
                            <span class="info-value">{{ $resource->provider ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>

                @if($resource->notes)
                <div class="info-section">
                    <h4><i class="fas fa-sticky-note"></i> Notes</h4>
                    <div class="info-content">{{ $resource->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Right: Meta -->
        <div class="col-lg-4 mb-4">
            <div class="info-card">
                <div class="info-section">
                    <h4><i class="fas fa-database"></i> Informations</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Campagne</span>
                            <span class="info-value">
                                @if($resource->campaign)
                                    <strong>{{ $resource->campaign->name }}</strong>
                                    <br><small class="text-muted">Organisateur: {{ $resource->campaign->organizer->name }}</small>
                                @else
                                    <span class="text-muted">Aucune campagne</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Créée le</span>
                            <span class="info-value">{{ $resource->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mise à jour</span>
                            <span class="info-value">{{ $resource->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h4><i class="fas fa-percentage"></i> Progression</h4>
                    <div class="mb-2">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $resource->progress_percentage }}%"
                                 aria-valuenow="{{ $resource->progress_percentage }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ $resource->quantity_pledged }} / {{ $resource->quantity_needed }} {{ $resource->unit }}
                            ({{ $resource->progress_percentage }}%)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection