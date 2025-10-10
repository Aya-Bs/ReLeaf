@extends('layouts.frontend')

@section('title', $location->name)

@section('content')
<style>
    body, .container-fluid {
        background: #f7f8fa !important;
    }
    .location-map-section {
        position: relative;
        width: 100vw;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background: #eaf3ea;
        min-height: 420px;
        overflow: visible;
        z-index: 1;
    }
    #map {
        width: 100%;
        height: 420px;
        border-radius: 0 0 32px 32px;
        box-shadow: 0 4px 24px rgba(45,90,39,0.10);
        z-index: 1;
    }
    .floating-stats {
        position: absolute;
        top: 32px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 18px;
        z-index: 10;
    }
    .stat-card {
        background: #111;
        color: #fff;
        border-radius: 18px;
        padding: 18px 28px 12px 28px;
        min-width: 120px;
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 2px 12px rgba(0,0,0,0.10);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .stat-card-label {
        font-size: 13px;
        color: #e0e0e0;
        font-weight: 400;
        margin-top: 2px;
    }
    .location-main {
        margin-top: -90px;
        z-index: 2;
        position: relative;
    }
    .location-info-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 16px rgba(45,90,39,0.10);
        padding: 32px 32px 24px 32px;
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .location-info-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .location-info-title {
        font-size: 22px;
        font-weight: 700;
        color: #222;
    }
    .location-info-heart {
        color: #f7b731;
        font-size: 22px;
        margin-left: 8px;
        cursor: pointer;
    }
    .location-info-meta {
        color: #888;
        font-size: 15px;
        margin-bottom: 8px;
    }
    .location-info-icons {
        display: flex;
        gap: 18px;
        margin-top: 10px;
    }
    .location-info-icon {
        background: #f4f7f4;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 18px;
        color: #2d5a27;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .location-stats-row {
        display: flex;
        gap: 18px;
        margin-top: 18px;
    }
    .location-stat-box {
        background: #f4f7f4;
        border-radius: 16px;
        flex: 1;
        padding: 18px 0 10px 0;
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        color: #222;
        box-shadow: 0 1px 4px rgba(45,90,39,0.04);
    }
    .location-stat-label {
        font-size: 13px;
        color: #888;
        font-weight: 400;
        margin-top: 2px;
    }
    .location-image-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 16px rgba(45,90,39,0.10);
        padding: 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 24px;
    }
    .location-image-main {
        width: 100%;
        max-width: 340px;
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(45,90,39,0.10);
        object-fit: cover;
        aspect-ratio: 4/3;
    }

    /* Pagination Styles */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 3rem;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .pagination-btn {
        padding: 10px 16px;
        border: 1px solid #2d5a27;
        background: white;
        color: #2d5a27;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 14px;
        min-width: 44px;
        text-align: center;
    }
    
    .pagination-btn:hover:not(:disabled) {
        background: #f4f7f4;
        border-color: #2d5a27;
        transform: translateY(-2px);
    }
    
    .pagination-btn.active {
        background: #2d5a27;
        color: white;
        border-color: #2d5a27;
    }
    
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        border-color: #ccc;
        color: #ccc;
        transform: none;
    }
    
    .event-page {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    .event-page.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .pagination-info {
        text-align: center;
        margin-top: 1rem;
        color: #666;
        font-size: 14px;
    }
</style>

       
 <div class="location-map-section">
    <div id="map"></div>
    <div class="floating-stats">
        <div class="stat-card">
            {{ $location->latitude ?? '-' }}
            <div class="stat-card-label">Latitude</div>
        </div>
   
        <div class="stat-card">
            {{ $location->longitude ?? '-' }}
            <div class="stat-card-label">Longitude</div>
        </div>
        <div class="stat-card">
            @php
                $iconUrl = isset($weather_icon) ? "https://openweathermap.org/img/wn/{$weather_icon}@2x.png" : null;
            @endphp
            @if(isset($iconUrl) && $iconUrl)
                <img src="{{ $iconUrl }}" alt="Weather icon" style="width:32px; height:32px; margin-buttom:0px">
            @else
                <span style="font-size: 18px; vertical-align:middle;">-</span>
            @endif
            <div class="stat-card-label">Temperature</div>
        </div>
        
    </div>
</div>

<div class="container location-main">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="location-info-card">
                <div class="location-info-header">
                    <div>
                        <div class="location-info-title">{{ $location->name }}</div>
                        <div class="location-info-meta">{{ $location->full_address ?? ($location->address . ', ' . $location->city) }}</div>
                    </div>
                    <div class="location-info-status">
                        @if($location->in_repair)
                            <i class="fas fa-tools" style="color: #dc3545; font-size: 22px;"></i>
                        @else
                            <i class="fas fa-check-circle" style="color: #28a745; font-size: 22px;"></i>
                        @endif
                    </div>
                </div>
                <div class="location-info-description" style="margin-top:10px; color:#444; font-size:16px;">
                    {{ $location->description ?? '-' }}
                </div>
                
                <div class="location-stats-row">
                     <div class="location-stat-box" >
{{ is_numeric($location->price) ? $location->price . ' TND' : '-' }}
            <div class="location-stat-label">Prix</div>
        </div>
                    <div class="location-stat-box">
                        {{ $location->capacity ?? '-' }}
                        <div class="location-stat-label">Capacity</div>
                    </div>
                    
                    <div class="location-stat-box" style="padding: 18px 0 10px 0; display: block; min-width: 120px; min-height: 60px;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <span style="font-size: 16px; font-weight: 600; color: #222; line-height: 1;">
                                {{ isset($temperature) ? $temperature : '-' }}<span style="font-size: 11px; font-weight: 400; vertical-align: super;">°C</span>
                            </span>
                        </div>
                        <div class="location-stat-label" style="margin-top: 8px; position: static; left: auto; transform: none; bottom: auto; width: 100%; text-align: center;">Temperature</div>
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="location-image-card">
                @php
                    // Safely extract first image path when items can be strings or arrays
                    $firstImage = null;
                    if(!empty($location->images) && is_array($location->images)){
                        $candidate = $location->images[0] ?? null;
                        if(is_string($candidate)){
                            $firstImage = $candidate;
                        } elseif(is_array($candidate)){
                            // Try common keys and then first string value
                            $firstImage = $candidate['path'] ?? $candidate['file'] ?? null;
                            if(!$firstImage){
                                // take first scalar value inside the array
                                foreach($candidate as $v){
                                    if(is_string($v)){
                                        $firstImage = $v;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                @endphp
                @if($firstImage)
                    <img id="location-image-carousel" src="{{ asset('storage/' . $firstImage) }}" class="location-image-main" alt="Location image">
                @else
                    <div class="location-image-main" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f4f7f4; color: #666; padding: 20px;">
                        <i class="fas fa-image" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                        <h4 style="color: #888; margin-bottom: 8px;">NO IMAGES FOR THIS LOCATION</h4>
                    </div>
                @endif
            </div>
        </div>

        @if($events && $events->count())
    <div class="event-list-section" style="margin-top: 43px; margin-bottom: 43px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                <h3 style="font-size: 19px; font-weight: 600; color: #2d5a27; margin: 0;">Events at this Location</h3>
                <div style="font-size: 14px; color: #2d5a27; font-weight: 600;">
                    <span id="total-events">{{ $events->count() }}</span> {{ $events->count() === 1 ? 'Event' : 'Events' }}
                </div>
            </div>

            {{-- Events Container with Pagination --}}
            <div id="events-container" style="font-size: 90%;">
                 @php
                    $eventsPerPage = 4;
                    $totalPages = ceil($events->count() / $eventsPerPage);
                    $currentEvents = $events;
                @endphp
                 <div id="events-container" style="font-size: 70%;">
                
                @for($page = 0; $page < $totalPages; $page++)
                <div class="event-page {{ $page === 0 ? 'active' : '' }}" data-page="{{ $page }}">
                    <div class="row g-4">
                        @foreach($currentEvents->slice($page * $eventsPerPage, $eventsPerPage) as $event)
                        <div class="col-md-3 d-flex">
                            <div class="event-card flex-fill" style="
                                background: linear-gradient(135deg, #ffffff 0%, #f9fdf9 100%);
                                border-radius: 20px;
                                box-shadow: 0 4px 20px rgba(45,90,39,0.08);
                                padding: 0;
                                overflow: hidden;
                                border: 1px solid rgba(45,90,39,0.08);
                                display: flex;
                                flex-direction: column;
                                justify-content: space-between;
                                transition: all 0.3s ease;
                                cursor: pointer;
                                height: 100%;
                            " onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 32px rgba(45,90,39,0.16)';" 
                              onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(45,90,39,0.08)';">

                                {{-- Event Header --}}
                                <div style="
                                    background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
                                    padding: 20px 20px 16px 20px;
                                    position: relative;
                                    overflow: hidden;
                                ">
                                    {{-- Decorative circles --}}
                                    <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                                    <div style="position: absolute; bottom: -10px; left: -10px; width: 50px; height: 50px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                                    
                                    <div style="position: relative; z-index: 1;">
                                        <div style="font-size: 20px; font-weight: 700; color: #ffffff; margin-bottom: 8px; line-height: 1.3;">
                                            {{ $event->title ?? 'Untitled Event' }}
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 6px; color: rgba(255,255,255,0.9); font-size: 14px;">
                                            <i class="fas fa-calendar-alt" style="font-size: 13px;"></i>
                                            <span>{{ $event->date ?? $event->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Event Body --}}
                                <div style="padding: 20px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                    {{-- Status Badge --}}
                                    @if(isset($event->status))
                                        @php
                                            $statusColors = [
                                                'published' => ['#e8f5e9', '#2d5a27'],
                                                'approved' => ['#e8f5e9', '#2d5a27'],
                                                'pending' => ['#fff3e0', '#f57c00'],
                                                'draft' => ['#fff3e0', '#f57c00'],
                                                'rejected' => ['#ffebee', '#c62828'],
                                                'cancelled' => ['#ffebee', '#c62828'],
                                            ];
                                            $bg = $statusColors[$event->status][0] ?? '#f4f4f4';
                                            $color = $statusColors[$event->status][1] ?? '#888';
                                        @endphp
                                        <span style="display:inline-block; background:{{ $bg }}; color:{{ $color }}; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">
                                            {{ strtoupper($event->status) }}
                                        </span>
                                    @endif

                                    {{-- Description --}}
                                    <div style="
                                        font-size: 15px;
                                        color: #555;
                                        line-height: 1.6;
                                        margin-bottom: 16px;
                                        min-height: 48px;
                                        display: -webkit-box;
                                        -webkit-line-clamp: 2;
                                        -webkit-box-orient: vertical;
                                        overflow: hidden;
                                    ">
                                        {{ $event->description ? Str::limit($event->description, 80) : 'No description available.' }}
                                    </div>

                                    {{-- Event Meta Info --}}
                                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; padding: 12px; background: #f8faf8; border-radius: 12px;">
                                        @if(isset($event->duration))
                                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #666;">
                                            <i class="fas fa-clock" style="color: #2d5a27; width: 16px;"></i>
                                            <span>{{ $event->duration }}</span>
                                        </div>
                                        @endif
                                        @if(isset($event->max_participants))
                                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #666;">
                                            <i class="fas fa-users" style="color: #2d5a27; width: 16px;"></i>
                                            <span>Max {{ $event->max_participants }} participants</span>
                                        </div>
                                        @endif
                                    </div>

                                    
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endfor
            </div>

            {{-- JavaScript Pagination Controls --}}
            @if($totalPages > 1)
            <div class="pagination-container">
                <button class="pagination-btn" id="prev-btn" disabled>
                    <i class="fas fa-chevron-left"></i> 
                </button>
                
                @for($i = 0; $i < $totalPages; $i++)
                <button class="pagination-btn {{ $i === 0 ? 'active' : '' }}" data-page="{{ $i }}">
                    {{ $i + 1 }}
                </button>
                @endfor
                
                <button class="pagination-btn" id="next-btn" {{ $totalPages <= 1 ? 'disabled' : '' }}>
                     <i class="fas fa-chevron-right"></i>
                </button>
            </div>

           
            @endif
        </div>
        @else
        <div class="col-12">
            <div style="text-align: center; padding: 60px 20px; color: #666;">
                <i class="fas fa-calendar-times" style="font-size: 48px; color: #ccc; margin-bottom: 16px;"></i>
                <h4 style="color: #888; margin-bottom: 8px;">No Events Found</h4>
                <p>There are no events scheduled at this location yet.</p>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @php
            // Build a clean array of image URLs for JS: only string paths, normalized
            $jsImageUrls = [];
            if(!empty($location->images) && is_array($location->images)){
                foreach($location->images as $item){
                    $path = null;
                    if(is_string($item)){
                        $path = $item;
                    } elseif(is_array($item)){
                        $path = $item['path'] ?? $item['file'] ?? null;
                        if(!$path){
                            foreach($item as $v){
                                if(is_string($v)){
                                    $path = $v; break;
                                }
                            }
                        }
                    }
                    if($path){
                        $jsImageUrls[] = asset('storage/' . $path);
                    }
                }
            }
        @endphp
        @if(count($jsImageUrls) > 1)
            let images = @json($jsImageUrls);
            let imgElem = document.getElementById('location-image-carousel');
            let idx = 0;
            setInterval(function() {
                idx = (idx + 1) % images.length;
                if(imgElem) imgElem.src = images[idx];
            }, 2000);
        @endif
    });
</script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Map initialization
    let lat = {{ $location->latitude ?? 'null' }};
    let lng = {{ $location->longitude ?? 'null' }};
    let map = L.map('map').setView([lat || 36.8065, lng || 10.1815], lat && lng ? 13 : 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    if(lat && lng) {
        const cuteIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -38],
        });
        L.marker([lat, lng], {icon: cuteIcon}).addTo(map);
    }

    // Pagination functionality
    document.addEventListener('DOMContentLoaded', function() {
        const eventsPerPage = 4;
        const eventPages = document.querySelectorAll('.event-page');
        const pageButtons = document.querySelectorAll('.pagination-btn[data-page]');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const currentPageSpan = document.getElementById('current-page');
        const totalPagesSpan = document.getElementById('total-pages');
        const showingCountSpan = document.getElementById('showing-count');
        const totalCountSpan = document.getElementById('total-count');
        
        let currentPage = 0;
        const totalPages = eventPages.length;

        function updatePaginationInfo(page) {
            const startItem = (page * eventsPerPage) + 1;
            const endItem = Math.min((page + 1) * eventsPerPage, parseInt(totalCountSpan.textContent));
            const showingText = startItem === endItem ? startItem : `${startItem}-${endItem}`;
            
            showingCountSpan.textContent = showingText;
            currentPageSpan.textContent = page + 1;
        }

        function showPage(page) {
            // Hide all pages
            eventPages.forEach(p => p.classList.remove('active'));
            
            // Show selected page
            eventPages[page].classList.add('active');
            
            // Update active button
            pageButtons.forEach(btn => {
                btn.classList.remove('active');
                if(parseInt(btn.dataset.page) === page) {
                    btn.classList.add('active');
                }
            });
            
            // Update navigation buttons
            prevBtn.disabled = page === 0;
            nextBtn.disabled = page === totalPages - 1;
            
            // Update pagination info
            updatePaginationInfo(page);
            
            currentPage = page;
            
            // Scroll to events section smoothly
            document.querySelector('.event-list-section').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }

        // Page number click event
        pageButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                showPage(parseInt(this.dataset.page));
            });
        });

        // Previous button
        prevBtn.addEventListener('click', function() {
            if(currentPage > 0) {
                showPage(currentPage - 1);
            }
        });

        // Next button
        nextBtn.addEventListener('click', function() {
            if(currentPage < totalPages - 1) {
                showPage(currentPage + 1);
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if(e.key === 'ArrowLeft' && currentPage > 0) {
                showPage(currentPage - 1);
            } else if(e.key === 'ArrowRight' && currentPage < totalPages - 1) {
                showPage(currentPage + 1);
            }
        });

        // Initialize pagination info
        if(totalPages > 0) {
            updatePaginationInfo(0);
        }
    });
</script>
@endpush

@endsection