@extends('layouts.frontend')

@section('title', 'Toutes les Campagnes')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Toutes les Campagnes</h1>
                    <p class="text-muted mb-0">Explorez l'ensemble des campagnes écologiques de la communauté</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts handled in layout -->

    <!-- Search and Filters Section (same UI as index) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="position-relative">
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Recherche..." style="width: 200px;" value="{{ request('search') }}">
                            <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-muted" style="font-size: 0.8rem;"></i>
                        </div>
                        <div>
                            <select id="categoryFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Toutes les catégories</option>
                                @foreach(['reforestation' => '🌲 Reforestation', 'nettoyage' => '🧹 Nettoyage', 'sensibilisation' => '📢 Sensibilisation', 'recyclage' => '♻️ Recyclage', 'biodiversite' => '🦋 Biodiversité', 'energie_renouvelable' => '⚡ Énergie Renouvelable', 'autre' => '🔧 Autre'] as $category => $label)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Terminée</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>⏸️ En pause</option>
                            </select>
                        </div>
                        <div>
                            <select id="sortFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Par nom</option>
                                <option value="funds" {{ request('sort') == 'funds' ? 'selected' : '' }}>Par financement</option>
                            </select>
                        </div>
                        <button id="clearFilters" class="btn btn-outline-eco btn-sm">
                            <i class="fas fa-times me-1"></i>Effacer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use all campaigns (no owner filtering) -->
    @php
    $displayCampaigns = $campaigns;
    @endphp

    <div id="contentContainer">
        <div class="carousel-wrapper">
            <button id="prevButton" class="carousel-nav left">&#8249;</button>
            <div id="campaignsCarousel" class="carousel-container">
                @foreach($displayCampaigns as $campaign)
                <div class="campaign-card">
                    <div class="card">
                        <img src="{{ $campaign->image_url ? Storage::url($campaign->image_url) : asset('images/events/jardin-urbain.svg') }}" class="card-img-top" alt="{{ $campaign->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $campaign->name }}</h5>
                            <p class="card-text">{{ ucfirst($campaign->category) }}</p>
                            <p class="card-text text-muted">{{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}</p>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary">Voir plus</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button id="nextButton" class="carousel-nav right">&#8250;</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('campaignsCarousel');
            const cards = document.querySelectorAll('.campaign-card');
            if (cards.length === 0) return;
            const cardWidth = cards[0].offsetWidth;
            let scrollAmount = 0;

            function highlightCenterCard() {
                const centerIndex = Math.round((carousel.scrollLeft + carousel.offsetWidth / 2) / cardWidth) - 1;
                cards.forEach((card, index) => {
                    if (index === centerIndex) {
                        card.classList.add('highlight');
                    } else {
                        card.classList.remove('highlight');
                    }
                });
            }

            document.getElementById('prevButton').addEventListener('click', () => {
                scrollAmount -= cardWidth;
                if (scrollAmount < 0) scrollAmount = 0;
                carousel.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
                highlightCenterCard();
            });

            document.getElementById('nextButton').addEventListener('click', () => {
                scrollAmount += cardWidth;
                if (scrollAmount >= carousel.scrollWidth - carousel.offsetWidth) {
                    scrollAmount = carousel.scrollWidth - carousel.offsetWidth;
                }
                carousel.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
                highlightCenterCard();
            });

            highlightCenterCard();
            carousel.addEventListener('scroll', highlightCenterCard);
        });
    </script>

    <style>
        .carousel-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .carousel-container {
            display: flex;
            overflow-x: hidden;
            scroll-behavior: smooth;
            width: 100%;
            height: 500px;
            align-items: center;
        }

        .campaign-card {
            flex: 0 0 calc(33.33% - 1rem);
            margin-right: 1rem;
            box-sizing: border-box;
            transition: transform 0.3s, filter 0.3s;
        }

        .campaign-card .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .campaign-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .campaign-card.highlight {
            transform: scale(1.2);
            filter: brightness(1.2);
            z-index: 1;
        }

        .campaign-card:not(.highlight) {
            filter: brightness(0.7);
            transform: scale(0.9);
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            z-index: 10;
        }

        .carousel-nav.left {
            left: 0;
        }

        .carousel-nav.right {
            right: 0;
        }
    </style>
</div>
@endsection

@push('styles')
<style>
    .campaign-image-table {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }

    .campaign-image-placeholder-table {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #2d5a27 0%, #3a7c30 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid #e9ecef;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #2d5a27;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        height: 6px !important;
        min-width: 80px;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        margin: 0 2px;
    }

    .btn-eco {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }

    .btn-eco:hover {
        background-color: var(--eco-green-dark);
        border-color: var(--eco-green-dark);
        color: white;
    }

    .btn-outline-eco {
        border-color: var(--eco-green);
        color: var(--eco-green);
    }

    .btn-outline-eco:hover {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }

    .btn-outline-eco-danger {
        border-color: #dc3545;
        color: #dc3545;
    }

    .btn-outline-eco-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    .bg-eco {
        background-color: var(--eco-green) !important;
    }

    .bg-eco-light {
        background-color: #e8f5e8 !important;
    }

    .bg-eco-success {
        background-color: #28a745 !important;
    }

    .bg-eco-danger {
        background-color: #dc3545 !important;
    }

    .bg-eco-secondary {
        background-color: #6c757d !important;
    }

    .text-eco {
        color: var(--eco-green) !important;
    }

    .text-eco-dark {
        color: #2d5a27 !important;
    }

    .pagination-links .pagination {
        margin: 0;
    }

    .pagination-links .page-link {
        color: var(--eco-green);
        border-color: #e9ecef;
        border-radius: 0.375rem;
        margin: 0 0.125rem;
        padding: 0.375rem 0.75rem;
    }

    .pagination-links .page-link:hover {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
        color: white;
    }

    .pagination-links .page-item.active .page-link {
        background-color: var(--eco-green);
        border-color: var(--eco-green);
    }

    @media (max-width: 768px) {
        .d-flex.flex-wrap {
            gap: 1rem !important;
        }

        .d-flex.flex-wrap>div {
            flex: 1 1 100%;
        }
    }
</style>
@endpush