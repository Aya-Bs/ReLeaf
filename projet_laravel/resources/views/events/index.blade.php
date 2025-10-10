@extends('layouts.frontend')

@section('title', 'Événements EcoEvents')

@section('content')
<div class="container-fluid py-4" style="background: #f8f9fa; min-height: 100vh;">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3 col-lg-2">
            <div class="sidebar-filters p-3">
                <!-- Filtres rapides -->
                <div class="filter-section mb-3">
                    <h6 class="filter-title mb-2" style="font-size: 12px;">FILTRES RAPIDES</h6>
                    <div class="filter-options">
                        <div class="filter-option mb-1 {{ request('filter') === 'all' || !request('filter') ? 'active' : '' }}" data-filter="filter" data-value="all">
                            <label class="d-flex justify-content-between align-items-center cursor-pointer" style="font-size: 11px;">
                                <span>Tous les événements</span>
                                <span class="badge bg-light text-dark" style="font-size: 9px;">{{ \App\Models\Event::published()->count() }}</span>
                            </label>
                        </div>
                        <div class="filter-option mb-1 {{ request('filter') === 'upcoming' ? 'active' : '' }}" data-filter="filter" data-value="upcoming">
                            <label class="d-flex justify-content-between align-items-center cursor-pointer" style="font-size: 11px;">
                                <span>À venir</span>
                                <span class="badge bg-light text-dark" style="font-size: 9px;">{{ \App\Models\Event::published()->upcoming()->count() }}</span>
                            </label>
                        </div>
                        <div class="filter-option mb-1 {{ request('filter') === 'available' ? 'active' : '' }}" data-filter="filter" data-value="available">
                            <label class="d-flex justify-content-between align-items-center cursor-pointer" style="font-size: 11px;">
                                <span>Places disponibles</span>
                                <span class="badge bg-light text-dark" style="font-size: 9px;">{{ \App\Models\Event::published()->withAvailableSeats()->count() }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Calendar View -->
                <div class="filter-section mb-3">
                    <h6 class="filter-title mb-2" style="font-size: 12px;">CALENDRIER</h6>
                    <div class="calendar-view">
                        <div id="mini-calendar" style="font-size: 10px;"></div>
                    </div>
                </div>

                <!-- Location Filter -->
                <div class="filter-section mb-3">
                    <h6 class="filter-title mb-2" style="font-size: 12px;">LIEUX</h6>
                    <div class="filter-options" style="min-width: 180px;">
                        <div class="filter-option mb-1 {{ !request('location') ? 'active' : '' }}" data-filter="location" data-value="all">
                            <label class="d-flex justify-content-between align-items-center cursor-pointer" style="font-size: 11px;">
                                <span>Tous les lieux</span>
                                <span class="badge bg-light text-dark" style="font-size: 9px;">{{ \App\Models\Event::published()->count() }}</span>
                            </label>
                        </div>
                        @php
                            $locations = \App\Models\Location::whereHas('events', function($q){ $q->published(); })
                                        ->withCount(['events as events_count' => function($q){ $q->published(); }])
                                        ->get();
                        @endphp
                        @foreach($locations as $location)
                        <div class="filter-option mb-1 {{ request('location') == $location->id ? 'active' : '' }}" data-filter="location" data-value="{{ $location->id }}">
                            <label class="d-flex justify-content-between align-items-center cursor-pointer" style="font-size: 11px;">
                                <span>{{ \Illuminate\Support\Str::limit($location->name, 20) }}</span>
                                <span class="badge bg-light text-dark" style="font-size: 9px;">{{ $location->events_count }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Clear Filters -->
                <div class="filter-section mt-3">
                    <button id="clear-all-filters" class="btn btn-outline-secondary w-100 clear-btn" style="font-size: 11px; padding: 5px;">
                        Effacer tous les filtres
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content p-4">
                <!-- Header -->
                <div class="header-bar mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="header-title">
                            <h1>Tous les Événements</h1>
                            <p class="text-muted mb-0">Découvrez des événements incroyables autour de vous</p>
                        </div>
                        <div class="header-actions">
                            <span class="results-count">Affichage de {{ $events->count() }} sur {{ $events->total() }} résultats</span>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Events Grid -->
                <div class="row g-4" id="events-container">
                    @forelse($events as $event)
                    <div class="col-lg-6">
                        <div class="event-card card border-0 shadow-sm h-100">
                            <div class="card-header-custom d-flex justify-content-between align-items-start p-3">
                                <div class="event-info-main flex-grow-1">
                                    <h5 class="card-title text-eco mb-2">{{ $event->title }}</h5>
                                    <div class="event-meta mb-2">
                                        <div class="meta-item mb-1">
                                            <i class="fas fa-calendar me-1"></i>
                                            <span>{{ $event->date->format('d/m/Y') }} à {{ $event->date->format('H:i') }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <span>{{ Str::limit($event->location->name, 30) }}</span>
                                        </div>

                                         <div class="meta-item">
<i class="fas fa-chair me-1"></i>                                            <span>Places : {{ $event->availableSeats }}/{{ $event->max_participants }} disponibles
</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="event-image-small">


@if($event->images && count($event->images) > 0)
                                @foreach($event->images as $idx => $image)
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="{{ $event->title }}"
class="event-thumbnail"                                         
data-carousel-index="{{ $idx }}">
                                @endforeach
                            @else
                            
                            <div class="event-thumbnail-placeholder bg-eco d-flex align-items-center justify-content-center">
                                            <i class="fas fa-calendar text-white"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-body d-flex flex-column pt-0">
                                <p class="card-text flex-grow-1 mb-3">
                                    {{ Str::limit($event->description, 120) }}
                                </p>
                                
                            
                                
                                <!-- Actions -->
                                <div class="card-actions mt-auto">
                                    @if($event->userReservation)
                                      
                                        <a href="{{ route('reservations.confirmation', $event->userReservation) }}" 
                                           class="btn btn-outline-eco btn-sm w-100">
                                            <i class="fas fa-eye me-2"></i>Voir ma réservation
                                        </a>
                                    @elseif($event->userInWaitingList)
                                        <div class="alert alert-warning py-2 mb-2">
                                            <small>
                                                <i class="fas fa-clock me-1"></i>
                                                Vous êtes dans la liste d'attente
                                                @php
                                                    $position = \App\Models\WaitingList::getUserPosition(auth()->id(), $event->id);
                                                @endphp
                                                @if($position)
                                                    - Position {{ $position }}
                                                @endif
                                            </small>
                                        </div>
                                      
                                    @elseif($event->availableSeats > 0 && auth()->check())
                                        <a href="{{ route('events.seats', $event) }}" 
                                           class="btn btn-eco w-100">
                                            <i class="fas fa-ticket-alt me-2"></i>Réserver une place
                                        </a>
                                    @elseif($event->isFull && auth()->check())
                                        <form action="{{ route('waiting-list.join', $event) }}" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="btn btn-warning w-100">
                                                <i class="fas fa-user-plus me-2"></i>Rejoindre la liste d'attente
                                            </button>
                                        </form>
                                    @elseif($event->isFull)
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-times me-2"></i>Événement complet
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-eco w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i>Connectez-vous pour réserver
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Par {{ $event->user->name }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $event->date->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">Aucun événement trouvé</h4>
                            <p class="text-muted">Il n'y a pas d'événements correspondant à vos critères.</p>
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('backend.events.create') }}" class="btn btn-eco">
                                        <i class="fas fa-plus me-2"></i>Créer un événement
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $events->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Variables de couleurs */
:root {
    --eco-primary: #2d5a27;
    --eco-primary-dark: #234420;
    --eco-secondary: #4a7c59;
    --eco-light: #e8f5e8;
}

/* Couleurs Eco */
.bg-eco {
    background-color: var(--eco-primary);
}
.text-eco {
    color: var(--eco-primary);
}
.btn-eco {
    background-color: var(--eco-primary);
    border-color: var(--eco-primary);
    color: white;
}
.btn-eco:hover {
    background-color: var(--eco-primary-dark);
    border-color: var(--eco-primary-dark);
    color: white;
}
.btn-outline-eco {
    border-color: var(--eco-primary);
    color: var(--eco-primary);
}
.btn-outline-eco:hover {
    background-color: var(--eco-primary);
    border-color: var(--eco-primary);
    color: white;
}

/* Sidebar Filters */
.sidebar-filters {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.filter-section {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 1.5rem;
}

.filter-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.filter-title {
    font-weight: 700;
    color: var(--eco-primary);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-option {
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid transparent;
}

.filter-option:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: translateX(5px);
}

.filter-option.active {
    background: linear-gradient(135deg, var(--eco-primary) 0%, var(--eco-secondary) 100%);
    color: white;
    border-color: var(--eco-primary);
}

.filter-option.active .badge {
    background: rgba(255,255,255,0.2) !important;
    color: white !important;
}

.filter-option label {
    width: 100%;
    font-size: 14px;
    font-weight: 500;
}

/* Calendar Styles - SMALLER VERSION */
.calendar-container {
    background: white;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid var(--eco-light);
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.calendar-nav-btn {
    background: linear-gradient(135deg, var(--eco-primary) 0%, var(--eco-secondary) 100%);
    border: none;
    border-radius: 8px;
    padding: 4px 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}

.calendar-nav-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(45, 90, 39, 0.3);
}

.calendar-title {
    font-weight: 700;
    color: var(--eco-primary);
    margin: 0;
    font-size: 12px;
    text-align: center;
    flex: 1;
    padding: 0 8px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.calendar-day-header {
    text-align: center;
    font-weight: 700;
    font-size: 9px;
    color: var(--eco-primary);
    padding: 4px 2px;
    text-transform: uppercase;
    background: #f8f9fa;
    border-radius: 4px;
}

.calendar-day {
    text-align: center;
    padding: 6px 2px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 10px;
    font-weight: 600;
    border: 1px solid transparent;
    background: #fafafa;
    position: relative;
    min-height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.calendar-day:hover {
    background: linear-gradient(135deg, var(--eco-primary) 0%, var(--eco-secondary) 100%);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(45, 90, 39, 0.2);
}

.calendar-day.empty {
    background: none;
    cursor: default;
    border: none;
}

.calendar-day.empty:hover {
    background: none;
    transform: none;
    box-shadow: none;
}

.calendar-day.today {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    color: white;
    border-color: #ff6b6b;
}

.calendar-day.selected {
    background: linear-gradient(135deg, var(--eco-primary) 0%, var(--eco-secondary) 100%);
    color: white;
    border-color: var(--eco-primary);
    box-shadow: 0 2px 6px rgba(45, 90, 39, 0.3);
}

.calendar-day.has-events::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 3px;
    height: 3px;
    background: var(--eco-primary);
    border-radius: 50%;
}

.calendar-day.selected.has-events::after,
.calendar-day:hover.has-events::after {
    background: white;
}

/* Form Elements */
.date-input {
    border: 2px solid var(--eco-light);
    border-radius: 12px;
    padding: 10px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #fafafa;
}

.date-input:focus {
    border-color: var(--eco-primary);
    box-shadow: 0 0 0 3px rgba(45, 90, 39, 0.1);
    background: white;
}

.clear-btn {
    border: 2px solid #6c757d;
    border-radius: 12px;
    padding: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.clear-btn:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
}

/* Event Cards - NEW STYLE */
.event-card {
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15) !important;
}

.card-header-custom {
    background: white;
    border-bottom: 1px solid #f0f0f0;
}

.event-info-main {
    padding-right: 15px;
}

.event-image-small {
    flex-shrink: 0;
}

.event-thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid var(--eco-light);
}

.event-thumbnail-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    border: 2px solid var(--eco-light);
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--eco-primary);
    line-height: 1.3;
    margin-bottom: 10px;
}

.event-meta {
    margin-bottom: 10px;
}

.meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
    font-size: 13px;
    color: #666;
}

.meta-item i {
    margin-right: 8px;
    width: 14px;
    color: var(--eco-primary);
}

/* Header */
.header-bar {
    background: white;
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.header-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--eco-primary);
    margin: 0 0 5px 0;
}

.results-count {
    color: #666;
    font-size: 14px;
    font-weight: 500;
}

/* Progress Bar */
.progress {
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar-filters {
        margin-bottom: 2rem;
    }
    
    .header-bar {
        padding: 20px;
    }
    
    .calendar-grid {
        gap: 3px;
    }
    
    .calendar-day {
        padding: 4px 1px;
        font-size: 9px;
        min-height: 20px;
    }
    
    .event-thumbnail,
    .event-thumbnail-placeholder {
        width: 60px;
        height: 60px;
    }
    
    .card-header-custom {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .event-image-small {
        margin-top: 10px;
        align-self: flex-end;
    }
}

/* Pagination */
.pagination {
    justify-content: center;
}

.page-link {
    border: 2px solid var(--eco-light);
    color: var(--eco-primary);
    padding: 10px 16px;
    margin: 0 4px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: var(--eco-primary);
    color: white;
    border-color: var(--eco-primary);
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, var(--eco-primary) 0%, var(--eco-secondary) 100%);
    border-color: var(--eco-primary);
    color: white;
}

/* Cursor pointer */
.cursor-pointer {
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    initializeCalendar();
    
    // Calendar functionality
    function initializeCalendar() {
        const calendarElement = document.getElementById('mini-calendar');
        if (!calendarElement) return;

        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = currentDate.getMonth();

        function renderCalendar(year, month) {
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();

            const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ];

            // Calendar header
            let calendarHTML = `
                <div class="calendar-container">
                    <div class="calendar-header">
                        <button class="calendar-nav-btn prev-month" data-action="prev">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h4 class="calendar-title">${monthNames[month].substring(0, 3)} ${year}</h4>
                        <button class="calendar-nav-btn next-month" data-action="next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header">D</div>
                        <div class="calendar-day-header">L</div>
                        <div class="calendar-day-header">M</div>
                        <div class="calendar-day-header">M</div>
                        <div class="calendar-day-header">J</div>
                        <div class="calendar-day-header">V</div>
                        <div class="calendar-day-header">S</div>
            `;

            // Empty cells for days before the first day of the month
            for (let i = 0; i < startingDay; i++) {
                calendarHTML += `<div class="calendar-day empty"></div>`;
            }

            // Days of the month
            const today = new Date();
            const selectedDate = new URLSearchParams(window.location.search).get('date');
            
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = today.getDate() === day && 
                               today.getMonth() === month && 
                               today.getFullYear() === year;
                const isSelected = selectedDate === dateStr;
                
                let dayClass = 'calendar-day';
                if (isToday) dayClass += ' today';
                if (isSelected) dayClass += ' selected';
                if (hasEventsOnDate(dateStr)) dayClass += ' has-events';
                
                calendarHTML += `<div class="${dayClass}" data-date="${dateStr}">${day}</div>`;
            }

            calendarHTML += '</div></div>';
            calendarElement.innerHTML = calendarHTML;

            // Add event listeners
            document.querySelector('.prev-month').addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentYear, currentMonth);
            });

            document.querySelector('.next-month').addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentYear, currentMonth);
            });

            // Add click events to calendar days
            document.querySelectorAll('.calendar-day[data-date]').forEach(day => {
                day.addEventListener('click', function() {
                    const date = this.dataset.date;
                    applyDateFilter(date);
                });
            });
        }

        // Use server-provided dates for which published events exist
        const eventDates = @json($allEventDates ?? []);
        function hasEventsOnDate(dateStr) {
            return eventDates.includes(dateStr);
        }

        renderCalendar(currentYear, currentMonth);
    }

    // Filter functionality
    function initializeFilters() {
        const clearAllFilters = document.getElementById('clear-all-filters');

        // Filter options - click to apply
        const filterOptions = document.querySelectorAll('.filter-option');
        filterOptions.forEach(option => {
            option.addEventListener('click', function() {
                const filterType = this.dataset.filter;
                const filterValue = this.dataset.value;
                
                // Remove active class from siblings
                const siblings = this.parentElement.querySelectorAll('.filter-option');
                siblings.forEach(sib => sib.classList.remove('active'));
                
                // Add active class to clicked option
                this.classList.add('active');
                
                applyFilters();
            });
        });

        // Clear all filters
        if (clearAllFilters) {
            clearAllFilters.addEventListener('click', function() {
                // Clear URL parameters
                window.location.href = "{{ route('events.index') }}";
            });
        }

        // Initialize active states
        function initializeActiveStates() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Set active filter options
            ['filter', 'location'].forEach(filterType => {
                const value = urlParams.get(filterType);
                if (value) {
                    const activeOption = document.querySelector(`.filter-option[data-filter="${filterType}"][data-value="${value}"]`);
                    if (activeOption) {
                        activeOption.classList.add('active');
                    }
                }
            });
        }

        initializeActiveStates();
    }

    // Apply filters function
    function applyFilters() {
        const url = new URL(window.location.href);
        
        // Get active filters
        const activeFilters = document.querySelectorAll('.filter-option.active');
        activeFilters.forEach(filter => {
            const type = filter.dataset.filter;
            const value = filter.dataset.value;
            if (value !== 'all') {
                url.searchParams.set(type, value);
            } else {
                url.searchParams.delete(type);
            }
        });

        // Remove page parameter when filters change
        url.searchParams.delete('page');

        // Navigate to new URL
        window.location.href = url.toString();
    }

    // Apply date filter function
    function applyDateFilter(date) {
        const url = new URL(window.location.href);
        
        if (date) {
            url.searchParams.set('date', date);
        } else {
            url.searchParams.delete('date');
        }

        // Remove page parameter when filters change
        url.searchParams.delete('page');

        // Navigate to new URL
        window.location.href = url.toString();
    }

    // Initialize everything
    initializeFilters();
});
</script>
@endsection