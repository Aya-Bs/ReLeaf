@extends('layouts.frontend')
@section('title', $event->title . ' | Événements')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
  
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white">
        <!-- Breadcrumb Path Top Right -->
        
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-stretch">
                <!-- Left Column - Event Information -->
                <div class="lg:col-span-3 flex flex-col justify-center space-y-6 order-2 lg:order-1 p-0">
                  


                   <!-- Event Information Card -->
<div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
    <!-- Event Title -->
    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
    
    <!-- Event Description -->
    <p class="text-gray-600 mb-6 leading-relaxed">{{ $event->description }}</p>
    
    <!-- Event Details -->
    <div class="space-y-4">
        <!-- Date -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-[#2d5a27]/10 rounded-lg">
                <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Date</p>
                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Location -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-[#2d5a27]/10 rounded-lg">
                <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Lieu</p>
                <p class="text-xs text-gray-500">{{ $event->location->name ?? 'Location TBD' }}</p>
            </div>
        </div>

        <!-- Participants -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-[#2d5a27]/10 rounded-lg">
                <svg class="w-5 h-5 text-[#2d5a27]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Participants</p>
                <p class="text-xs text-gray-500">{{$event->reservations()->count()}} / {{ $event->max_participants ?? 'Illimité' }}</p>

            </div>
        </div>

        

       

        <!-- Price -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-[#2d5a27]/10 rounded-lg">
                <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700">Prix</p>
                <p class="text-xs text-gray-500">{{ $event->price ? $event->price . ' €' : 'Gratuit' }}</p>
            </div>
        </div>
    </div>
</div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 mt-8">
                        @if($event->status === 'published')
                            @if($event->availableSeats > 0 && auth()->check())
                                <a href="{{ route('events.seats', $event) }}" 
                                   class="bg-[#2d5a27] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#234420] transition-colors">
                                    Réserver une place
                                </a>
                            @elseif($event->isFull && auth()->check())
                                <form action="{{ route('waiting-list.join', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-yellow-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                                        Rejoindre la liste d'attente
                                    </button>
                                </form>
                            @elseif($event->isFull)
                                <button class="bg-gray-400 text-white px-8 py-3 rounded-lg font-semibold cursor-not-allowed" disabled>
                                    Événement complet
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="bg-[#2d5a27] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#234420] transition-colors">
                                    Connectez-vous pour réserver
                                </a>
                            @endif
                        @endif
                    
                    </div>
                </div>

                <!-- Right Column - Framed Photo Card -->
                <div class="lg:col-span-2 flex items-center justify-center order-1 lg:order-2 p-0 m-0">
                    <div class="relative flex flex-col items-center justify-center">
                        <!-- Framed Photo -->
                        <div class="photo-card relative w-[340px] h-[340px] sm:w-[380px] sm:h-[380px] md:w-[420px] md:h-[420px] flex items-center justify-center">
                            <div class="photo-frame bg-white rounded-2xl shadow-lg flex items-center justify-center" style="width:100%;height:100%;border-radius:18px;padding:18px;box-shadow:0 12px 30px rgba(0,0,0,0.08);">
                                <div style="width:100%;height:100%;background:linear-gradient(180deg,#fff,#fbfffb);border-radius:12px;padding:6px;box-shadow:inset 0 0 0 6px rgba(243,250,246,0.9);overflow:hidden;">
                                    @if($event->images && count($event->images) > 0)
                                        <img src="{{ asset('storage/' . $event->images[0]) }}" alt="{{ $event->title }}" class="w-full h-full object-cover rounded-xl" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[#f3faf6] rounded-xl">
                                            <svg class="w-28 h-28 text-[#2d5a27] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- small top-right profile-style photo (organizer avatar) -->
                            <div class="absolute top-6 right-6 bg-white rounded-full p-1 shadow-md" style="width:64px;height:64px;border-radius:9999px;">
                                @php
                                    $avatar = optional($event->user)->avatar ?? null; 
                                    $avatarUrl = $avatar ? asset('storage/' . $avatar) : asset('images/avatar-placeholder.png');
                                @endphp
                                <img src="{{ $avatarUrl }}" alt="{{ optional($event->user)->name ?? 'Organisateur' }}" class="w-full h-full object-cover rounded-full" />
                            </div>

                            <!-- Date Badge (keeps left top) -->
                            <div class="absolute top-4 left-4 bg-[#2d5a27] text-white rounded-lg shadow-xl overflow-hidden">
                                <div class="px-4 py-2 text-center bg-[#2d5a27]">
                                    <div class="text-3xl font-bold leading-none">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</div>
                                    <div class="text-xs font-medium uppercase mt-1">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Optional carousel dots (kept small) -->
                        @if($event->images && count($event->images) > 1)
                            <div class="flex items-center gap-2 mt-4">
                                @foreach($event->images as $idx => $image)
                                    <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300 {{ $idx === 0 ? 'bg-[#2d5a27] w-6' : '' }}"
                                            data-carousel-index="{{ $idx }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Small Cards Section - Centered at Bottom -->
    <div class="small-cards-strip py-8 flex justify-center items-center">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-6 max-w-4xl mx-auto">

            <!-- Price Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-4 min-w-[140px] text-center group cursor-pointer shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Heure</p>
                    <p class="text-xs text-gray-500 mt-1"> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</p>

                </div>
                <!-- Status Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-4 min-w-[140px] text-center group cursor-pointer shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-[#2d5a27] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Statut</p>
                    <p class="text-xs text-gray-500 mt-1 capitalize">
                        @if($event->status === 'published')
                            Publié
                        @elseif($event->status === 'pending')
                            En attente 
                        @elseif($event->status === 'draft')
                            Brouillon
                        @elseif($event->status === 'cancelled')
                            Annulé
                        @elseif($event->status === 'rejected')
                            Rejeté
                        @else
                            Événement
                        @endif
                    </p>
                </div>

                <!-- Organizer Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-4 min-w-[140px] text-center group cursor-pointer shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Ville</p>
                    <p class="text-xs text-gray-500 mt-1">{{$event->location->city, 12 }}</p>
                </div>

                <!-- Organizer Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-4 min-w-[140px] text-center group cursor-pointer shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Durée</p>
                    <p class="text-xs text-gray-500 mt-1">{{$event->duration, 12 }}</p>
                </div>

                

                

                <!-- Available Seats Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-4 min-w-[140px] text-center group cursor-pointer shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-[#2d5a27] transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Places</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $event->max_participants ?? '∞' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles for Event Show Page */
.min-h-screen {
    min-height: 100vh;
}

.bg-gradient-to-br {
    background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
}

.from-gray-50 {
    --tw-gradient-from: #f9fafb;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(249, 250, 251, 0));
}

.to-gray-100 {
    --tw-gradient-to: #f3f4f6;
}

.bg-white {
    background-color: #ffffff;
}

.relative {
    position: relative;
}

.overflow-hidden {
    overflow: hidden;
}

.absolute {
    position: absolute;
}

.container {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1rem;
    padding-right: 1rem;
}

@media (min-width: 640px) {
    .container {
        max-width: 640px;
    }
}

@media (min-width: 768px) {
    .container {
        max-width: 768px;
    }
}

@media (min-width: 1024px) {
    .container {
        max-width: 1024px;
    }
}

@media (min-width: 1280px) {
    .container {
        max-width: 1280px;
    }
}

.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.py-12 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

.gap-8 {
    gap: 2rem;
}

.items-stretch {
    align-items: stretch;
}

.flex {
    display: flex;
}

.flex-col {
    flex-direction: column;
}

.justify-center {
    justify-content: center;
}

.space-y-6 > * + * {
    margin-top: 1.5rem;
}

.order-2 {
    order: 2;
}

.order-1 {
    order: 1;
}

.p-0 {
    padding: 0;
}

.m-0 {
    margin: 0;
}

.inline-block {
    display: inline-block;
}

.text-\[\#2d5a27\] {
    color: #2d5a27;
}

.font-semibold {
    font-weight: 600;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.uppercase {
    text-transform: uppercase;
}

.tracking-wider {
    letter-spacing: 0.05em;
}

.text-4xl {
    font-size: 2.25rem;
    line-height: 2.5rem;
}

.font-bold {
    font-weight: 700;
}

.text-black {
    color: #000000;
}

.leading-tight {
    line-height: 1.25;
}

.prose {
    color: var(--tw-prose-body);
    max-width: 65ch;
}

.prose-lg {
    font-size: 1.125rem;
    line-height: 1.7777778;
}

.max-w-none {
    max-width: none;
}

.text-gray-600 {
    color: #4b5563;
}

.gap-4 {
    gap: 1rem;
}

.mt-6 {
    margin-top: 1.5rem;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.border {
    border-width: 1px;
}

.border-gray-200 {
    border-color: #e5e7eb;
}

.p-4 {
    padding: 1rem;
}

.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.items-center {
    align-items: center;
}

.gap-3 {
    gap: 0.75rem;
}

.w-10 {
    width: 2.5rem;
}

.h-10 {
    height: 2.5rem;
}

.bg-\[\#2d5a27\]\/10 {
    background-color: rgba(45, 90, 39, 0.1);
}

.rounded-lg {
    border-radius: 0.5rem;
}

.w-5 {
    width: 1.25rem;
}

.h-5 {
    height: 1.25rem;
}

.text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

.text-gray-500 {
    color: #6b7280;
}

.text-gray-700 {
    color: #374151;
}

.mt-8 {
    margin-top: 2rem;
}

.bg-\[\#2d5a27\] {
    background-color: #2d5a27;
}

.text-white {
    color: #ffffff;
}

.px-8 {
    padding-left: 2rem;
    padding-right: 2rem;
}

.py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.hover\:bg-\[\#234420\]:hover {
    background-color: #234420;
}

.transition-colors {
    transition-property: color, background-color, border-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.border-\[\#2d5a27\] {
    border-color: #2d5a27;
}

.hover\:bg-\[\#2d5a27\]:hover {
    background-color: #2d5a27;
}

.hover\:text-white:hover {
    color: #ffffff;
}

.bg-yellow-500 {
    background-color: #eab308;
}

.hover\:bg-yellow-600:hover {
    background-color: #ca8a04;
}

.bg-gray-400 {
    background-color: #9ca3af;
}

.cursor-not-allowed {
    cursor: not-allowed;
}

.rounded-2xl {
    border-radius: 1rem;
}

.border-4 {
    border-width: 4px;
}

.border-\[\#f3faf6\] {
    border-color: #f3faf6;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.object-cover {
    object-fit: cover;
}

.transition-opacity {
    transition-property: opacity;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.duration-700 {
    transition-duration: 700ms;
}

.hidden {
    display: none;
}

.opacity-30 {
    opacity: 0.3;
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.overflow-hidden {
    overflow: hidden;
}

.text-3xl {
    font-size: 1.875rem;
    line-height: 2.25rem;
}

.leading-none {
    line-height: 1;
}

.uppercase {
    text-transform: uppercase;
}

.mt-1 {
    margin-top: 0.25rem;
}

.mt-4 {
    margin-top: 1rem;
}

.w-3 {
    width: 0.75rem;
}

.h-3 {
    height: 0.75rem;
}

.rounded-full {
    border-radius: 9999px;
}

.bg-gray-300 {
    background-color: #d1d5db;
}

.duration-300 {
    transition-duration: 300ms;
}

.w-6 {
    width: 1.5rem;
}

.py-8 {
    padding-top: 2rem;
    padding-bottom: 2rem;
}

.flex-wrap {
    flex-wrap: wrap;
}

.max-w-4xl {
    max-width: 56rem;
}

.mx-auto {
    margin-left: auto;
    margin-right: auto;
}

.flex-shrink-0 {
    flex-shrink: 0;
}

.border-2 {
    border-width: 2px;
}

.hover\:border-\[\#2d5a27\]:hover {
    border-color: #2d5a27;
}

.group:hover .group-hover\:bg-\[\#2d5a27\]\/10 {
    background-color: rgba(45, 90, 39, 0.1);
}

.group:hover .group-hover\:text-\[\#2d5a27\] {
    color: #2d5a27;
}

.cursor-pointer {
    cursor: pointer;
}

.w-12 {
    width: 3rem;
}

.h-12 {
    height: 3rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.bg-gray-100 {
    background-color: #f3f4f6;
}

.w-6 {
    width: 1.5rem;
}

.h-6 {
    height: 1.5rem;
}

.text-gray-600 {
    color: #4b5563;
}

.capitalize {
    text-transform: capitalize;
}

/* Responsive Design */
@media (min-width: 1024px) {
    .lg\:grid-cols-5 {
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }
    
    .lg\:col-span-3 {
        grid-column: span 3 / span 3;
    }
    
    .lg\:col-span-2 {
        grid-column: span 2 / span 2;
    }
    
    .lg\:order-1 {
        order: 1;
    }
    
    .lg\:order-2 {
        order: 2;
    }
    
    .lg\:text-5xl {
        font-size: 3rem;
        line-height: 1;
    }
}

@media (min-width: 640px) {
    .sm\:w-\[380px\] {
        width: 380px;
    }
    
    .sm\:h-\[380px\] {
        height: 380px;
    }
}

@media (min-width: 768px) {
    .md\:w-\[420px\] {
        width: 420px;
    }
    
    .md\:h-\[420px\] {
        height: 420px;
    }
}

/* Custom widths */
.w-\[340px\] {
    width: 340px;
}

.h-\[340px\] {
    height: 340px;
}

.min-w-\[140px\] {
    min-width: 140px;
}

/* Z-index */
.z-10 {
    z-index: 10;
}

/* Top and Right positioning */
.top-0 {
    top: 0;
}

.right-0 {
    right: 0;
}

.mt-6 {
    margin-top: 1.5rem;
}

.mr-8 {
    margin-right: 2rem;
}

/* Navigation styles */
nav {
    display: flex;
    align-items: center;
    font-size: 1rem;
    font-weight: 400;
}

.space-x-2 > * + * {
    margin-left: 0.5rem;
}

.hover\:underline:hover {
    text-decoration: underline;
}

.mx-1 {
    margin-left: 0.25rem;
    margin-right: 0.25rem;
}

/* Carousel dot active state */
.carousel-dot.bg-\[\#2d5a27\] {
    background-color: #2d5a27;
}

/* Transition all */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Custom shadow */
.shadow-\[0_8px_32px_0_rgba\(45\2c 90\2c 39\2c 0\.15\)\] {
    box-shadow: 0 8px 32px 0 rgba(45, 90, 39, 0.15);
}

/* Confirmation banner */
.confirmation-banner{
    background: linear-gradient(90deg,#2d5a27,#3d7a37);
    color: #fff;
    font-size: 16px;
}

/* Photo card tweaks */
.photo-card{ border-radius: 14px; }
.photo-frame img{ display:block; }
.photo-card .text-xs{ color:#6b7280 }

/* Framed photo specific styling to match screenshot */
.photo-frame{
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(45,90,39,0.06);
}
.photo-frame > div{
    border-radius: 12px;
    overflow: hidden;
    height: calc(100% - 12px);
}
.photo-frame img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.photo-card .organizer-avatar{
    width:64px;
    height:64px;
    border-radius:9999px;
    border:4px solid #fff; /* subtle white ring */
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

.photo-card .date-badge{
    background:#2d5a27;
    color:white;
    border-radius:10px;
}

.event-meta-row .prose{ margin-top:0; }

/* Small cards strip */
.small-cards-strip{ background: linear-gradient(180deg, #f3faf6 0%, #ffffff 100%); }
.small-cards-strip .flex-shrink-0{ background: white; border-radius: 12px; padding: 16px; box-shadow: 0 6px 20px rgba(45,90,39,0.06); }

</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.event-carousel-img');
    const dots = document.querySelectorAll('.carousel-dot');
    
    if (images.length > 1) {
        let currentIndex = 0;
        
        // Auto-rotate images
        const interval = setInterval(() => {
            // Hide current image
            images[currentIndex].classList.add('hidden');
            dots[currentIndex].classList.remove('bg-[#2d5a27]', 'w-6');
            dots[currentIndex].classList.add('bg-gray-300', 'w-3');
            
            // Move to next image
            currentIndex = (currentIndex + 1) % images.length;
            
            // Show next image
            images[currentIndex].classList.remove('hidden');
            dots[currentIndex].classList.remove('bg-gray-300', 'w-3');
            dots[currentIndex].classList.add('bg-[#2d5a27]', 'w-6');
        }, 3000);

        // Add click event to dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                clearInterval(interval);
                
                // Hide current image
                images[currentIndex].classList.add('hidden');
                dots[currentIndex].classList.remove('bg-[#2d5a27]', 'w-6');
                dots[currentIndex].classList.add('bg-gray-300', 'w-3');
                
                // Show clicked image
                currentIndex = index;
                images[currentIndex].classList.remove('hidden');
                dots[currentIndex].classList.remove('bg-gray-300', 'w-3');
                dots[currentIndex].classList.add('bg-[#2d5a27]', 'w-6');
            });
        });
    }
});
</script>
@endpush
@endsection