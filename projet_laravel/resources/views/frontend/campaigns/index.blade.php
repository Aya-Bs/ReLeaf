@extends('layouts.frontend')

@section('title', 'Gestion des Campagnes')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Gestion des Campagnes</h1>
                    <p class="text-muted mb-0">Gérez et organisez vos campagnes écologiques pour maximiser leur impact</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Search and Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <!-- Search Input -->
                        <div class="position-relative">
                            <input type="text" 
                                   id="searchInput" 
                                   class="form-control form-control-sm" 
                                   placeholder="Recherche..." 
                                   style="width: 200px;"
                                   value="{{ request('search') }}">
                            <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2 text-muted" style="font-size: 0.8rem;"></i>
                        </div>
                        
                        <!-- Category Filter -->
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
                        
                        <!-- Status Filter -->
                        <div>
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Terminée</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>⏸️ En pause</option>
                            </select>
                        </div>
                        
                        <!-- Sort Filter -->
                        <div>
                            <select id="sortFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Par nom</option>
                                <option value="funds" {{ request('sort') == 'funds' ? 'selected' : '' }}>Par financement</option>
                            </select>
                        </div>
                        
                        <!-- Clear Filters Button -->
                        <button id="clearFilters" class="btn btn-outline-eco btn-sm">
                            <i class="fas fa-times me-1"></i>Effacer
                        </button>
                        
                        <!-- Create Campaign Button (only for admin and organizer) -->
                        @php $role = optional(auth()->user())->role; @endphp
                        @if(in_array($role, ['admin', 'organizer']))
                        <a href="{{ route('campaigns.create') }}" class="btn btn-eco btn-sm">
                            <i class="fas fa-plus me-2"></i>Créer une campagne
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loadingIndicator" class="text-center py-4" style="display: none;">
        <div class="spinner-border text-eco" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="mt-2 text-muted">Filtrage des campagnes...</p>
    </div>

    <!-- Content Container -->
    @php
        $userCampaigns = $campaigns->filter(function($campaign) {
            return $campaign->organizer_id === auth()->id();
        });
    @endphp

    <!-- Updated Campaigns Carousel -->
    <div id="contentContainer">
        <div class="carousel-wrapper">
            <button id="prevButton" class="carousel-nav left">&#8249;</button>
            <div id="campaignsCarousel" class="carousel-container">
                @php $lastCampaign = $userCampaigns->last(); $firstCampaign = $userCampaigns->first(); @endphp
                @if($lastCampaign)
                <div class="campaign-card clone">
                    <div class="card">
                        <img src="{{ $lastCampaign->image_url ? Storage::url($lastCampaign->image_url) : asset('images/events/jardin-urbain.svg') }}" class="card-img-top" alt="{{ $lastCampaign->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $lastCampaign->name }}</h5>
                            <p class="card-text">{{ ucfirst($lastCampaign->category) }}</p>
                            <p class="card-text text-muted">{{ $lastCampaign->start_date->format('d/m/Y') }} - {{ $lastCampaign->end_date->format('d/m/Y') }}</p>
                            <a href="{{ route('campaigns.show', $lastCampaign) }}" class="btn btn-eco">Voir plus</a>
                        </div>
                    </div>
                </div>
                @endif
                @foreach($userCampaigns as $campaign)
                <div class="campaign-card">
                    <div class="card">
                        <img src="{{ $campaign->image_url ? Storage::url($campaign->image_url) : asset('images/events/jardin-urbain.svg') }}" class="card-img-top" alt="{{ $campaign->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $campaign->name }}</h5>
                            <p class="card-text">{{ ucfirst($campaign->category) }}</p>
                            <p class="card-text text-muted">{{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-eco">Voir plus</a>
                                <a href="{{ route('campaigns.carbon-report', $campaign) }}" class="btn btn-outline-eco">
                                    <i class="fas fa-leaf me-1"></i>Bilan carbone
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($firstCampaign)
                <div class="campaign-card clone">
                    <div class="card">
                        <img src="{{ $firstCampaign->image_url ? Storage::url($firstCampaign->image_url) : asset('images/events/jardin-urbain.svg') }}" class="card-img-top" alt="{{ $firstCampaign->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $firstCampaign->name }}</h5>
                            <p class="card-text">{{ ucfirst($firstCampaign->category) }}</p>
                            <p class="card-text text-muted">{{ $firstCampaign->start_date->format('d/m/Y') }} - {{ $firstCampaign->end_date->format('d/m/Y') }}</p>
                            <a href="{{ route('campaigns.show', $firstCampaign) }}" class="btn btn-eco">Voir plus</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <button id="nextButton" class="carousel-nav right">&#8250;</button>
        </div>
    </div>

    <!-- JavaScript for Highlighting Center Card -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('campaignsCarousel');
            const cards = Array.from(document.querySelectorAll('.campaign-card'));
            let cardWidth = cards[0] ? cards[0].offsetWidth : 0;
            let scrollAmount = 0;

            function setCenterPadding() {
                cardWidth = cards[0] ? cards[0].offsetWidth : 0;
                carousel.style.paddingLeft = '0px';
                carousel.style.paddingRight = '0px';
            }

            function highlightCenterCard() {
                const viewportCenter = carousel.scrollLeft + carousel.offsetWidth / 2;
                let closestIndex = 0;
                let closestDist = Infinity;

                cards.forEach((card, idx) => {
                    const cardCenter = card.offsetLeft + (card.offsetWidth / 2);
                    const dist = Math.abs(cardCenter - viewportCenter);
                    if (dist < closestDist) {
                        closestDist = dist;
                        closestIndex = idx;
                    }
                });

                cards.forEach((card, idx) => {
                    if (idx === closestIndex) {
                        card.classList.add('highlight');
                    } else {
                        card.classList.remove('highlight');
                    }
                });
            }

            // Init padding so the first card is centered on load
            setCenterPadding();

            // Manual Navigation
            document.getElementById('prevButton').addEventListener('click', () => {
                scrollAmount = Math.max(0, carousel.scrollLeft - cardWidth);
                carousel.scrollTo({ left: scrollAmount, behavior: 'smooth' });
                setTimeout(highlightCenterCard, 300);
            });

            document.getElementById('nextButton').addEventListener('click', () => {
                const maxScrollLeft = carousel.scrollWidth - carousel.offsetWidth;
                scrollAmount = Math.min(maxScrollLeft, carousel.scrollLeft + cardWidth);
                carousel.scrollTo({ left: scrollAmount, behavior: 'smooth' });
                setTimeout(highlightCenterCard, 300);
            });

            // Initial alignment: with padding-left applied, left=0 centers first card
            // Avec le clone de fin au début, défiler d'une carte pour avoir la 1ère réelle au centre
            carousel.scrollTo({ left: cardWidth, behavior: 'auto' });
            highlightCenterCard();

            // Update on resize and scroll
            window.addEventListener('resize', () => {
                setCenterPadding();
                highlightCenterCard();
            });
            carousel.addEventListener('scroll', highlightCenterCard);
        });
    </script>

    <!-- Styles for Highlighted Center Card -->
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
            height: 500px; /* Increased height for the container */
            align-items: center; /* Center align cards vertically */
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
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        margin: 0 2px;
    }
    
    /* Boutons cohérents avec le thème écologique */
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
    
    /* Badges écologiques */
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
    
    .bg-eco {
        background-color: var(--eco-green) !important;
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
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .d-flex.flex-wrap {
            gap: 1rem !important;
        }
        
        .d-flex.flex-wrap > div {
            flex: 1 1 100%;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group-sm > .btn {
            padding: 0.125rem 0.25rem;
            margin: 0 1px;
        }
        
        .campaign-image-table,
        .campaign-image-placeholder-table {
            width: 40px;
            height: 40px;
        }
        
        .pagination-links .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    const clearFilters = document.getElementById('clearFilters');
    const contentContainer = document.getElementById('contentContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const filterInfo = document.getElementById('filterInfo');

    let searchTimeout;

    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.get('category')) {
        categoryFilter.value = urlParams.get('category');
    }
    if (urlParams.get('status')) {
        statusFilter.value = urlParams.get('status');
    }
    if (urlParams.get('sort')) {
        sortFilter.value = urlParams.get('sort');
    }
    updateFilterInfo();

    // Search input event
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateFilters, 500);
    });

    // Filter events
    categoryFilter.addEventListener('change', updateFilters);
    statusFilter.addEventListener('change', updateFilters);
    sortFilter.addEventListener('change', updateFilters);

    // Clear filters event
    clearFilters.addEventListener('click', function() {
        searchInput.value = '';
        categoryFilter.value = '';
        statusFilter.value = '';
        sortFilter.value = 'latest';
        updateFilters();
    });

    function updateFilters() {
        // Show loading
        loadingIndicator.style.display = 'block';
        contentContainer.style.opacity = '0.5';

        const filters = {
            search: searchInput.value,
            category: categoryFilter.value,
            status: statusFilter.value,
            sort: sortFilter.value
        };

        // Update URL without page reload
        const url = new URL(window.location);
        if (filters.search) {
            url.searchParams.set('search', filters.search);
        } else {
            url.searchParams.delete('search');
        }
        if (filters.category) {
            url.searchParams.set('category', filters.category);
        } else {
            url.searchParams.delete('category');
        }
        if (filters.status) {
            url.searchParams.set('status', filters.status);
        } else {
            url.searchParams.delete('status');
        }
        if (filters.sort) {
            url.searchParams.set('sort', filters.sort);
        } else {
            url.searchParams.delete('sort');
        }
        window.history.pushState({}, '', url);

        // Fetch updated content
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extract the content container from the response
            const newContent = doc.getElementById('contentContainer');
            if (newContent) {
                contentContainer.innerHTML = newContent.innerHTML;
            }
            
            updateFilterInfo();
        })
        .catch(error => {
            console.error('Error:', error);
        })
        .finally(() => {
            // Hide loading
            loadingIndicator.style.display = 'none';
            contentContainer.style.opacity = '1';
        });
    }

    function updateFilterInfo() {
        const search = searchInput.value;
        const category = categoryFilter.value;
        const status = statusFilter.value;
        const sort = sortFilter.value;
        
        let info = '';
        if (search || category || status || sort !== 'latest') {
            info = '(Filtrés: ';
            const filters = [];
            
            if (search) filters.push(`"${search}"`);
            if (category) filters.push(`Catégorie: ${categoryFilter.options[categoryFilter.selectedIndex].text}`);
            if (status) filters.push(`Statut: ${statusFilter.options[statusFilter.selectedIndex].text}`);
            if (sort !== 'latest') filters.push(`Tri: ${sortFilter.options[sortFilter.selectedIndex].text}`);
            
            info += filters.join(' | ');
            info += ')';
        }
        
        filterInfo.textContent = info;
    }

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        searchInput.value = urlParams.get('search') || '';
        categoryFilter.value = urlParams.get('category') || '';
        statusFilter.value = urlParams.get('status') || '';
        sortFilter.value = urlParams.get('sort') || 'latest';
        updateFilters();
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('campaignsCarousel');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        const cards = document.querySelectorAll('.campaign-card');
        const cardWidth = cards[0].offsetWidth;

        function updateNavButtons() {
            // Hide the left arrow if at the start
            if (carousel.scrollLeft <= 0) {
                prevButton.style.display = 'none';
            } else {
                prevButton.style.display = 'block';
            }

            // Hide the right arrow if at the end
            if (carousel.scrollLeft + carousel.offsetWidth >= carousel.scrollWidth) {
                nextButton.style.display = 'none';
            } else {
                nextButton.style.display = 'block';
            }
        }

        // Align the first real card at center (using left clone)
        function alignFirstCard() {
            carousel.scrollTo({ left: cardWidth, behavior: 'smooth' });
        }

        // Initial check
        updateNavButtons();
        alignFirstCard();

        // Update buttons on scroll
        carousel.addEventListener('scroll', updateNavButtons);

        // Manual Navigation
        prevButton.addEventListener('click', () => {
            const newScrollLeft = Math.max(0, carousel.scrollLeft - cardWidth);
            carousel.scrollTo({ left: newScrollLeft, behavior: 'smooth' });
        });

        nextButton.addEventListener('click', () => {
            const maxScrollLeft = carousel.scrollWidth - carousel.offsetWidth;
            const newScrollLeft = Math.min(maxScrollLeft, carousel.scrollLeft + cardWidth);
            carousel.scrollTo({ left: newScrollLeft, behavior: 'smooth' });
        });

        // Card click behavior
        cards.forEach((card, index) => {
            card.addEventListener('click', () => {
                const centerIndex = Math.round(carousel.scrollLeft / cardWidth);
                if (index < centerIndex) {
                    // Scroll left if the clicked card is to the left of the center
                    const newScrollLeft = Math.max(0, carousel.scrollLeft - cardWidth);
                    carousel.scrollTo({ left: newScrollLeft, behavior: 'smooth' });
                } else if (index > centerIndex) {
                    // Scroll right if the clicked card is to the right of the center
                    const maxScrollLeft = carousel.scrollWidth - carousel.offsetWidth;
                    const newScrollLeft = Math.min(maxScrollLeft, carousel.scrollLeft + cardWidth);
                    carousel.scrollTo({ left: newScrollLeft, behavior: 'smooth' });
                }
            });
        });
    });
</script>
@endpush