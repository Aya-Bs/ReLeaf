@extends('layouts.frontend')

@section('title', 'Toutes les Ressources')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Toutes les Ressources</h1>
                    <p class="text-muted mb-0">Parcourez l'ensemble des ressources nécessaires aux campagnes</p>
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

                        <!-- Campaign Filter -->
                        <div>
                            <select id="campaignFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Toutes les campagnes</option>
                                @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ Str::limit($campaign->name, 25) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select id="statusFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Tous les statuts</option>
                                <option value="needed" {{ request('status') == 'needed' ? 'selected' : '' }}>⏳ Nécessaire</option>
                                <option value="pledged" {{ request('status') == 'pledged' ? 'selected' : '' }}>📋 Promis</option>
                                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>✅ Reçu</option>
                                <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>🔄 Utilisé</option>
                            </select>
                        </div>

                        <!-- Sort Filter -->
                        <div>
                            <select id="sortFilter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciennes</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Par nom</option>
                                <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Par priorité</option>
                            </select>
                        </div>
                        <!-- Hidden type select to reuse logic -->
                        <select id="typeFilter" class="d-none">
                            <option value=""></option>
                            <option value="money"></option>
                            <option value="food"></option>
                            <option value="clothing"></option>
                            <option value="medical"></option>
                            <option value="equipment"></option>
                            <option value="human"></option>
                        </select>

                        <!-- Clear Filters Button -->
                        <button id="clearFilters" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Effacer
                        </button>

                        <!-- Create Resource Button (only for admin and organizer) -->
                        @php $role = optional(auth()->user())->role; @endphp
                        @if(in_array($role, ['admin', 'organizer']))
                        <a href="{{ route('resources.create') }}" class="btn btn-eco btn-sm">
                            <i class="fas fa-plus me-2"></i>Créer une ressource
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Type Filter Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-center flex-wrap gap-2" id="typeFilterBar">
                        <button class="resource-type-filter btn btn-outline-eco btn-sm active" data-type="">
                            <span class="me-2">📦</span>Tous
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="money">
                            <span class="me-2">💰</span>Argent
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="food">
                            <span class="me-2">🍎</span>Nourriture
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="clothing">
                            <span class="me-2">👕</span>Vêtements
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="medical">
                            <span class="me-2">🏥</span>Médical
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="equipment">
                            <span class="me-2">🛠️</span>Équipement
                        </button>
                        <button class="resource-type-filter btn btn-outline-eco btn-sm" data-type="human">
                            <span class="me-2">👥</span>Main d'œuvre
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div id="contentContainer">
        <!-- Use all resources directly (no owner filtering) -->
        @if($resources->count() > 0)
        <div class="row g-4" id="resourcesGrid">
            @foreach($resources as $resource)
            <div class="col-12 col-md-6 col-lg-4 resource-card" data-type="{{ $resource->resource_type }}">
                <div class="card h-100 resource-card-item shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <span class="resource-type-icon me-2 fs-4">
                                @switch($resource->resource_type)
                                @case('money') 💰 @break
                                @case('food') 🍎 @break
                                @case('clothing') 👕 @break
                                @case('medical') 🏥 @break
                                @case('equipment') 🛠️ @break
                                @case('human') 👥 @break
                                @default 🔧 @break
                                @endswitch
                            </span>
                            <div>
                                <h6 class="mb-0">{{ Str::limit($resource->name, 20) }}</h6>
                                <small class="text-muted">{{ optional($resource->campaign)->name ? Str::limit($resource->campaign->name, 25) : 'Campagne inconnue' }}</small>
                            </div>
                        </div>
                        <a href="{{ route('resources.show', $resource) }}" class="btn btn-sm btn-outline-secondary" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>

                    <div class="card-body">
                        @if($resource->description)
                        <p class="card-text">{{ Str::limit($resource->description, 100) }}</p>
                        @endif

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Progression</span>
                                <span class="fw-bold">{{ $resource->progress_percentage }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $resource->progress_percentage == 100 ? 'success' : ($resource->progress_percentage > 50 ? 'warning' : 'danger') }}"
                                    data-progress="{{ (int) $resource->progress_percentage }}"></div>
                            </div>
                        </div>

                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="fw-bold text-success">{{ $resource->quantity_pledged }}</div>
                                    <small class="text-muted">Promis</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-eco">{{ $resource->quantity_needed }}</div>
                                <small class="text-muted">Nécessaire</small>
                            </div>
                        </div>

                        @if($resource->missing_quantity > 0)
                        <div class="alert alert-warning py-2 mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Manque {{ $resource->missing_quantity }} {{ $resource->unit }}
                        </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $resource->status == 'received' ? 'success' : ($resource->status == 'pledged' ? 'info' : ($resource->status == 'in_use' ? 'primary' : 'secondary')) }}">
                                @switch($resource->status)
                                @case('needed') ⏳ @break
                                @case('pledged') 📋 @break
                                @case('received') ✅ @break
                                @case('in_use') 🔄 @break
                                @default ❓ @break
                                @endswitch
                                {{ ucfirst($resource->status) }}
                            </span>

                            <span class="badge bg-{{ $resource->priority == 'urgent' ? 'danger' : ($resource->priority == 'high' ? 'warning' : ($resource->priority == 'medium' ? 'info' : 'success')) }}">
                                @switch($resource->priority)
                                @case('urgent') 🚨 @break
                                @case('high') ⚠️ @break
                                @case('medium') 📊 @break
                                @default 📌 @break
                                @endswitch
                                {{ ucfirst($resource->priority) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if($resource->provider)
                                Fourni par: {{ Str::limit($resource->provider, 15) }}
                                @else
                                Aucun fournisseur
                                @endif
                            </small>
                            <small class="text-muted">{{ $resource->unit }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($resources->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="pagination-info">
                <p class="mb-0 text-muted">
                    Affichage de <strong>{{ $resources->firstItem() }}</strong> à <strong>{{ $resources->lastItem() }}</strong>
                    sur <strong>{{ $resources->total() }}</strong> ressources
                </p>
            </div>
            <div class="pagination-links">
                {{ $resources->links() }}
            </div>
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucune ressource trouvée.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .resource-type-icon {
        font-size: 1.25rem;
    }

    .resource-card-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .resource-card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
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
</style>
@endpush

@push('scripts')
<script>
    // Minimal client-side filters wiring (same behavior contract as index)
    (function() {
        const searchInput = document.getElementById('searchInput');
        const campaignFilter = document.getElementById('campaignFilter');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');
        const typeFilter = document.getElementById('typeFilter');
        const clearFilters = document.getElementById('clearFilters');
        const typeFilterBar = document.getElementById('typeFilterBar');

        let searchTimeout = null;

        function updateFilters() {
            const url = new URL(window.location);
            const params = {
                search: searchInput?.value || '',
                campaign_id: campaignFilter?.value || '',
                status: statusFilter?.value || '',
                resource_type: typeFilter?.value || '',
                sort: sortFilter?.value || 'latest'
            };
            Object.keys(params).forEach(key => {
                if (params[key]) url.searchParams.set(key, params[key]);
                else url.searchParams.delete(key);
            });
            window.location.href = url;
        }

        searchInput?.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateFilters, 500);
        });
        campaignFilter?.addEventListener('change', updateFilters);
        statusFilter?.addEventListener('change', updateFilters);
        sortFilter?.addEventListener('change', updateFilters);
        clearFilters?.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            if (campaignFilter) campaignFilter.value = '';
            if (statusFilter) statusFilter.value = '';
            if (typeFilter) typeFilter.value = '';
            // reset active type button
            document.querySelectorAll('.resource-type-filter').forEach(btn => btn.classList.remove('active'));
            document.querySelector('.resource-type-filter[data-type=""]')?.classList.add('active');
            if (sortFilter) sortFilter.value = 'latest';
            updateFilters();
        });

        // Apply progress widths
        document.querySelectorAll('.progress-bar[data-progress]')
            .forEach(el => {
                const v = parseInt(el.getAttribute('data-progress') || '0', 10);
                el.style.width = Math.max(0, Math.min(100, v)) + '%';
            });

        // Handle type filter bar clicks
        typeFilterBar?.addEventListener('click', (e) => {
            const btn = e.target.closest('.resource-type-filter');
            if (!btn) return;
            document.querySelectorAll('.resource-type-filter').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            if (typeFilter) typeFilter.value = btn.dataset.type || '';
            updateFilters();
        });

        // Initialize type from URL param
        (function initFromURL() {
            const url = new URL(window.location);
            const t = url.searchParams.get('resource_type') || '';
            if (typeFilter) typeFilter.value = t;
            document.querySelectorAll('.resource-type-filter').forEach(b => {
                if ((b.dataset.type || '') === t) b.classList.add('active');
                else b.classList.remove('active');
            });
        })();
    })();
</script>
@endpush