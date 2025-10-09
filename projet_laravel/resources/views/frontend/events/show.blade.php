@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white">
        <!-- Breadcrumb Path Top Right -->
        <div class="absolute top-0 right-0 mt-6 mr-8 z-10">
            <nav class="flex items-center text-base font-normal text-[#2d5a27] space-x-2" aria-label="Breadcrumb">
                <a href="/" class="hover:underline">Accueil</a>
                <span class="mx-1">/</span>
                <a href="/events" class="hover:underline">Événements</a>
                <span class="mx-1">/</span>
                <span class="font-bold">{{ Str::limit($event->title, 24) }}</span>
            </nav>
        </div>
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-0 items-stretch">
                <!-- Left Column - Image Carousel -->
                <div class="lg:col-span-3 flex items-center justify-center order-1 lg:order-1 p-0 m-0">
                    <div class="relative flex flex-col items-center justify-center">
                        <!-- Carousel Images -->
                        <div id="event-carousel" class="relative w-[340px] h-[340px] sm:w-[380px] sm:h-[380px] md:w-[420px] md:h-[420px] rounded-2xl border-4 border-[#f3faf6] bg-white flex items-center justify-center overflow-hidden shadow-lg">
                            @if($event->images && count($event->images) > 0)
                                @foreach($event->images as $idx => $image)
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="{{ $event->title }}"
                                         class="event-carousel-img absolute inset-0 w-full h-full object-cover transition-opacity duration-700 rounded-2xl {{ $idx === 0 ? '' : 'hidden' }}"
                                         data-carousel-index="{{ $idx }}">
                                @endforeach
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-32 h-32 text-[#2d5a27] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <!-- Date Badge -->
                            <div class="absolute top-4 left-4 bg-[#2d5a27] text-white rounded-lg shadow-xl overflow-hidden">
                                <div class="px-4 py-2 text-center bg-[#2d5a27]">
                                    <div class="text-3xl font-bold leading-none">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</div>
                                    <div class="text-xs font-medium uppercase mt-1">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Column - Event Info -->
                <div class="lg:col-span-2 flex flex-col justify-center space-y-6 order-2 lg:order-2 p-0 mr-10000">
                    <!-- Event Type Badge -->
                    <div class="inline-block">
                        <span class="text-[#2d5a27] font-semibold text-sm uppercase tracking-wider">
                            @if($event->status === 'published')
                                Événement publié
                            @elseif($event->status === 'pending')
                                En attente de validation
                            @elseif($event->status === 'draft')
                                Événement Brouillon
                            @elseif($event->status === 'cancelled')
                                Événement Annulé
                            @elseif($event->status === 'rejected')
                                Événement Rejeté 
                            @else
                                Événement
                            @endif
                        </span>
                    </div>

                    <!-- Event Title -->
                    <h1 class="text-4xl lg:text-5xl font-bold text-black leading-tight">
                        {{ $event->title }}
                    </h1>

                    <!-- Event Meta Info -->
                    <div class="flex items-center gap-4 text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</span>
                        </div>
                        <span class="text-gray-400">|</span>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium">{{ $event->location->name ?? 'Location TBD' }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
              

                    <!-- Pagination Dots -->
                 <div class="flex items-center gap-2">

                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </h2>
                <div class="prose prose-lg max-w-none text-gray-600">
                    {!! nl2br(e($event->description)) !!}
                </div>
                </div>
                </div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.event-carousel-img');
    if (images.length > 1) {
        let idx = 0;
        setInterval(() => {
            images[idx].classList.add('hidden');
            idx = (idx + 1) % images.length;
            images[idx].classList.remove('hidden');
        }, 2000);
    }
});
</script>
@endpush
            </div>
        </div>
    </div>

    <!-- Icon Cards Section -->
    <div class="bg-gradient-to-b from-[#e6f4ea] to-white py-12 flex justify-center items-center">
        <div class="container mx-auto px-4 flex justify-center">
            <div class="flex items-center gap-4 overflow-x-auto pb-4 justify-center">
                <!-- Date Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-6 min-w-[160px] text-center group cursor-pointer shadow-[0_8px_32px_0_rgba(45,90,39,0.15)]">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#2d5a27] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Date</p>
                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</p>
                </div>

                <!-- Duration Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-6 min-w-[160px] text-center group cursor-pointer shadow-[0_8px_32px_0_rgba(45,90,39,0.15)]">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#2d5a27] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Durée</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $event->duration }} </p>
                </div>

                <!-- Participants Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-6 min-w-[160px] text-center group cursor-pointer shadow-[0_8px_32px_0_rgba(45,90,39,0.15)]">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#2d5a27] transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Participants</p>
                    <p class="text-xs text-gray-500 mt-1">Max {{ $event->max_participants }}</p>
                </div>

                <!-- Location Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-6 min-w-[160px] text-center group cursor-pointer shadow-[0_8px_32px_0_rgba(45,90,39,0.15)]">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#2d5a27] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Lieu</p>
                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($event->location->name ?? 'TBD', 15) }}</p>
                </div>

                <!-- Status Card -->
                <div class="flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:border-[#2d5a27] transition-all duration-300 p-6 min-w-[160px] text-center group cursor-pointer shadow-[0_8px_32px_0_rgba(45,90,39,0.15)]">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-gray-100 rounded-lg group-hover:bg-[#2d5a27]/10 transition-colors">
                        <svg class="w-8 h-8 text-gray-600 group-hover:text-[#2d5a27] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Statut</p>
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

                
            </div>
        </div>
    </div>

    

    <!-- Delete Button (if authorized) -->
    @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->id === $event->user_id))
    <div class="container mx-auto px-4 pb-12">
        <div class="max-w-4xl mx-auto">
           
        </div>
    </div>
    @endif
</div>
@endsection