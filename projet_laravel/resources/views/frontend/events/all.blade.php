@extends('layouts.frontend')

@section('title', 'All Events')

@section('content')
<div class="container-fluid py-4" style="background: #f8f9fa; min-height: 100vh;">
    <div class="row">
       <!-- Sidebar Filters -->
<div class="col-md-3 col-lg-2"> <!-- Reduced column width -->
    <div class="sidebar-filters p-3"> <!-- Reduced padding -->
        <!-- Calendar View -->
        <div class="filter-section mb-3">
            <h6 class="filter-title mb-2" style="font-size: 12px;">CALENDAR</h6>
            <div class="calendar-view">
                <div id="mini-calendar" style="font-size: 10px;"></div>
            </div>
        </div>

        <!-- Location Filter - Slightly wider than calendar -->
        <div class="filter-section mb-3">
            <h6 class="filter-title mb-2" style="font-size: 12px;">LOCATIONS</h6>
            <div class="filter-options" style="min-width: 180px;"> <!-- Added fixed width -->
                <div class="filter-option mb-1 active" data-filter="location" data-value="all">
                    <label class="d-flex justify-content-between align-items-center cursor-pointer" style="font-size: 11px;">
                        <span>All Locations</span>
                        <span class="badge bg-light text-dark" style="font-size: 9px;">{{ \App\Models\Event::count() }}</span>
                    </label>
                </div>
                @php
                    $locations = \App\Models\Location::has('events')->withCount('events')->get();
                @endphp
                @foreach($locations as $location)
                <div class="filter-option mb-1" data-filter="location" data-value="{{ $location->id }}">
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
                Clear All Filters
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
                            <h1>All Events</h1>
                            <p class="text-muted mb-0">Discover amazing events around you</p>
                        </div>
                        <div class="header-actions">
                            <span class="results-count">Showing {{ $events->count() }} of {{ $events->total() }} results</span>
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
                        <div class="event-card card border-0 shadow-sm h-100 d-flex flex-row">                            
                            @foreach($event->images as $image)
                            <div class="card-img-left" style="width: 40%;">
                                <img src="{{ asset('storage/' . $image) }}" alt="Image du lieu {{ $event->name }}">
                            </div>
                            @endforeach

                            <!-- Info Section -->
                            <div class="card-body" style="width: 60%;">
                                <!-- Event Title -->
                                <h6 class="card-title">{{ Str::limit($event->title, 40) }}</h6>

                                <!-- Date and Location -->
                                <div class="event-meta mb-2">
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ Str::limit($event->location->name ?? 'TBD', 25) }}</span>
                                    </div>
                                </div>

                                <!-- Price and Duration -->
                                <div class="event-info mb-2">
                                    <span class="price-badge">
                                        {{ $event->price ? '$' . $event->price : 'Free' }}
                                    </span>
                                    <span class="duration">
                                        • {{ $event->duration }}
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="card-text">
                                    {{ Str::limit($event->description, 60) }}
                                </p>

                                <!-- Action Buttons -->
                                <div class="event-actions">
                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-success btn-details">
                                        Details
                                    </a>
                                    @if($event->status === 'published' && $event->max_participants - $event->reservations()->where('status', 'confirmed')->count() > 0)
                                        <a href="{{ route('events.show', $event->id) }}#reservation" class="btn btn-success btn-book">
                                            Book Now
                                        </a>
                                    @elseif($event->status === 'published')
                                        <button class="btn btn-secondary btn-sold-out" disabled>
                                            Sold Out
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <h4>No events available</h4>
                        <p class="mb-4">There are no events matching your filters.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                <div class="mt-5">
                    {{ $events->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
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
    color: #2d5a27;
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
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
    color: white;
    border-color: #2d5a27;
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
    border: 1px solid #e8f5e8;
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
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
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
    color: #2d5a27;
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
    color: #2d5a27;
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
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
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
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
    color: white;
    border-color: #2d5a27;
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
    background: #2d5a27;
    border-radius: 50%;
}

.calendar-day.selected.has-events::after,
.calendar-day:hover.has-events::after {
    background: white;
}

/* Form Elements */
.date-input {
    border: 2px solid #e8f5e8;
    border-radius: 12px;
    padding: 10px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #fafafa;
}

.date-input:focus {
    border-color: #2d5a27;
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

/* Event Cards */
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

.event-image-carousel {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.event-carousel-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    transition: opacity 0.5s ease-in-out;
}

.event-image-placeholder {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #6c757d;
    font-size: 2rem;
}

.carousel-indicators {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 4px;
}

.carousel-indicator {
    width: 8px;
    height: 8px;
    border: none;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    transition: all 0.3s ease;
    cursor: pointer;
}

.carousel-indicator.active {
    background: white;
    transform: scale(1.2);
}

/* Badges */
.status-badge {
    font-size: 10px;
    font-weight: 700;
    padding: 6px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.published {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.status-badge.cancelled {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    color: white;
}

.status-badge.pending {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
}

.status-badge.draft {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.seats-badge {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 6px 10px;
    border-radius: 20px;
}

/* Card Body */
.card-body {
    padding: 20px;
}

.card-title {
    font-size: 16px;
    font-weight: 700;
    color: #2d5a27;
    line-height: 1.4;
    margin-bottom: 12px;
}

.event-meta {
    margin-bottom: 15px;
}

.meta-item {
    display: flex;
    align-items: center;
    margin-bottom: 6px;
    font-size: 12px;
    color: #666;
}

.meta-item i {
    margin-right: 8px;
    width: 14px;
    color: #2d5a27;
}

.event-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.price-badge {
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-right: 8px;
}

.duration {
    color: #666;
    font-size: 12px;
    font-weight: 500;
}

.card-text {
    color: #666;
    line-height: 1.5;
    font-size: 13px;
    margin-bottom: 20px;
}

.event-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-details {
    border: 2px solid #2d5a27;
    color: #2d5a27;
    border-radius: 25px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-details:hover {
    background: #2d5a27;
    color: white;
    transform: translateY(-2px);
}

.btn-book {
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
    border: none;
    border-radius: 25px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-book:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(45, 90, 39, 0.3);
}

.btn-sold-out {
    border-radius: 25px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
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
    color: #2d5a27;
    margin: 0 0 5px 0;
}

.results-count {
    color: #666;
    font-size: 14px;
    font-weight: 500;
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
    
    .event-image-carousel {
        height: 160px;
    }
    
    .event-carousel-img {
        height: 160px;
    }
}

/* Pagination */
.pagination {
    justify-content: center;
}

.page-link {
    border: 2px solid #e8f5e8;
    color: #2d5a27;
    padding: 10px 16px;
    margin: 0 4px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.page-link:hover {
    background: #2d5a27;
    color: white;
    border-color: #2d5a27;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #2d5a27 0%, #4a7c59 100%);
    border-color: #2d5a27;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    initializeCalendar();
    
    // Image carousel functionality
    function initializeCarousels() {
        const carousels = document.querySelectorAll('.event-image-carousel');
        
        carousels.forEach(carousel => {
            const images = carousel.querySelectorAll('.event-carousel-img');
            const indicators = carousel.querySelectorAll('.carousel-indicator');
            
            if (images.length > 1) {
                let currentIndex = 0;
                
                // Auto-rotate images every 5 seconds
                const interval = setInterval(() => {
                    // Hide current image
                    images[currentIndex].style.opacity = '0';
                    indicators[currentIndex].classList.remove('active');
                    indicators[currentIndex].style.backgroundColor = 'rgba(255,255,255,0.5)';
                    
                    // Move to next image
                    currentIndex = (currentIndex + 1) % images.length;
                    
                    // Show next image
                    images[currentIndex].style.opacity = '1';
                    indicators[currentIndex].classList.add('active');
                    indicators[currentIndex].style.backgroundColor = '#fff';
                    
                }, 5000);
                
                // Add click event to indicators
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', () => {
                        clearInterval(interval);
                        
                        // Hide current image
                        images[currentIndex].style.opacity = '0';
                        indicators[currentIndex].classList.remove('active');
                        indicators[currentIndex].style.backgroundColor = 'rgba(255,255,255,0.5)';
                        
                        // Show clicked image
                        currentIndex = index;
                        images[currentIndex].style.opacity = '1';
                        indicators[currentIndex].classList.add('active');
                        indicators[currentIndex].style.backgroundColor = '#fff';
                    });
                });
            }
        });
    }

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

            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
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
                        <div class="calendar-day-header">S</div>
                        <div class="calendar-day-header">M</div>
                        <div class="calendar-day-header">T</div>
                        <div class="calendar-day-header">W</div>
                        <div class="calendar-day-header">T</div>
                        <div class="calendar-day-header">F</div>
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

        function hasEventsOnDate(dateStr) {
            // This would typically check if there are events on this date
            // For now, we'll return true for some random days for demonstration
            const randomDays = [2, 5, 8, 12, 15, 18, 22, 25, 28];
            const day = parseInt(dateStr.split('-')[2]);
            return randomDays.includes(day);
        }

        renderCalendar(currentYear, currentMonth);
    }

    // Filter functionality
    function initializeFilters() {
        const dateFilter = document.getElementById('event-date-filter');
        const clearAllFilters = document.getElementById('clear-all-filters');

        // Date filter - update on change
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                applyDateFilter(this.value);
            });
        }

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
                window.location.href = "{{ route('events.all') }}";
            });
        }

        // Initialize active states
        function initializeActiveStates() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Set active filter options
            ['status', 'location'].forEach(filterType => {
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
    initializeCarousels();
    initializeFilters();
});
</script>
@endsection