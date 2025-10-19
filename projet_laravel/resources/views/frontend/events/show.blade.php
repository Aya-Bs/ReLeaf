@extends('layouts.frontend')
@section('title', $event->title . ' | Événements')
@section('title', $event->title . ' | Événements')

@section('content')


<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
  
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white">
        <!-- Breadcrumb Path Top Right -->

        <div class="d-flex justify-content-between align-items-center " style="margin-left: 1200px;">
                <div class="breadcrumb-nav">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);" onclick="history.back();" class="text-eco">
                                Événements
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $event->title ?? 'Événement' }}
                            </li>
                        </ol>
                    </nav>


<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
  
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white">
        <!-- Breadcrumb Path Top Right -->

        <div class="d-flex justify-content-between align-items-center " style="margin-left: 1200px;">
                <div class="breadcrumb-nav">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);" onclick="history.back();" class="text-eco">
                                Événements
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $event->title ?? 'Événement' }}
                            </li>
                        </ol>
                    </nav>
            </div>
        </div>
        
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-stretch">
                <!-- Left Column - Event Information -->
                <div class="lg:col-span-3 flex flex-col justify-center space-y-6 order-2 lg:order-1 p-0">
                  


                   <!-- Event Information Card -->
<div class="bg-white rounded-lg p-6 shadow-sm">
    <!-- Event Title with Badge -->
    <div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
    
    @php
        $confirmedCount = $event->reservations()->where('status', 'confirmed')->count();
        $isFull = $event->max_participants && $confirmedCount >= $event->max_participants;
    @endphp
    
    @if($isFull)
        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
            Complet
        </span>
    @else
        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
            Disponible
        </span>
    @endif
</div>
    
    <!-- Event Description -->
    <p class="text-gray-600 mb-6 leading-relaxed">{{ $event->description }}</p>
    
    <!-- Event Details: compact info cards (Date, Lieu, Participants, Durée) -->
    <div class="mt-2">
            <div class="flex flex-wrap justify-center gap-16 max-w-4xl mx-auto">

        
                
                
    <!-- Small Cards Section - compact horizontal info cards -->
    <div class="small-cards-strip py-6 flex justify-center items-center">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4 max-w-4xl mx-auto">

                <!-- Heure -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                            <circle cx="12" cy="12" r="9" stroke-width="2" stroke="currentColor" fill="none" />
                        </svg>
        </div>
        
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-stretch">
                <!-- Left Column - Event Information -->
                <div class="lg:col-span-3 flex flex-col justify-center space-y-6 order-2 lg:order-1 p-0">
                  


                   <!-- Event Information Card -->
<div class="bg-white rounded-lg p-6 shadow-sm">
    <!-- Event Title with Badge -->
    <div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
    
    @php
        $confirmedCount = $event->reservations()->where('status', 'confirmed')->count();
        $isFull = $event->max_participants && $confirmedCount >= $event->max_participants;
    @endphp
    
    @if($isFull)
        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
            Complet
        </span>
    @else
        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
            Disponible
        </span>
    @endif
</div>
    
    <!-- Event Description -->
    <p class="text-gray-600 mb-6 leading-relaxed">{{ $event->description }}</p>
    
    <!-- Event Details: compact info cards (Date, Lieu, Participants, Durée) -->
    <div class="mt-2">
            <div class="flex flex-wrap justify-center gap-16 max-w-4xl mx-auto">

        
                
                
    <!-- Small Cards Section - compact horizontal info cards -->
    <div class="small-cards-strip py-6 flex justify-center items-center">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-center gap-4 max-w-4xl mx-auto">

                <!-- Heure -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                            <circle cx="12" cy="12" r="9" stroke-width="2" stroke="currentColor" fill="none" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Heure</p>
                    <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</p>
                    <p class="text-xs font-semibold text-gray-700">Heure</p>
                    <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</p>
                </div>

                <!-- Durée -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Durée</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $event->duration ?? 'Non spécifiée' }}</p>
                <!-- Durée -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Durée</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $event->duration ?? 'Non spécifiée' }}</p>
                </div>

                <!-- Lieu -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Lieu</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $event->location->name ?? 'Non spécifié' }}</p>
                </div>

                <!-- Statut -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Statut</p>
                    <p class="text-sm text-gray-600 mt-1 capitalize">
                        @if($event->status === 'published') Publié
                        @elseif($event->status === 'pending') En attente
                        @elseif($event->status === 'draft') Brouillon
                        @elseif($event->status === 'cancelled') Annulé
                        @elseif($event->status === 'rejected') Rejeté
                        @else Événement
                        @endif
                    </p>
                </div>

                <!-- Lieu -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Lieu</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $event->location->name ?? 'Non spécifié' }}</p>
                </div>

                <!-- Statut -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Statut</p>
                    <p class="text-sm text-gray-600 mt-1 capitalize">
                        @if($event->status === 'published') Publié
                        @elseif($event->status === 'pending') En attente
                        @elseif($event->status === 'draft') Brouillon
                        @elseif($event->status === 'cancelled') Annulé
                        @elseif($event->status === 'rejected') Rejeté
                        @else Événement
                        @endif
                    </p>
                </div>

                <!-- Participants -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Participants</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $event->reservations()->where('status','confirmed')->count() }} / {{ $event->max_participants ?? '∞' }}</p>
                </div>
                <!-- Participants -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Participants</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $event->reservations()->where('status','confirmed')->count() }} / {{ $event->max_participants ?? '∞' }}</p>
                </div>

                <!-- Campaign -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Campagne</p>
                    <p class="text-sm text-gray-600 mt-1">{{$event->campaign->name ?? '-' }}</p>
                </div>
                <!-- Campaign -->
                <div class="small-info-card text-center">
                    <div class="icon-wrap mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-gray-700">Campagne</p>
                    <p class="text-sm text-gray-600 mt-1">{{$event->campaign->name ?? '-' }}</p>
                </div>

            </div>
        </div>
    </div>
                
               
            </div>
    </div>
</div>
            </div>
        </div>
    </div>
                
               
            </div>
    </div>
</div>

                    
                </div>
                    
                </div>

                <!-- Right Column - Framed Photo Card -->
                <div class="lg:col-span-2 flex items-center justify-center order-1 lg:order-2 p-0 m-0">
                    <div class="relative flex flex-col items-center justify-center">
                        <!-- Framed Photo -->
                        <div class="photo-card relative w-[340px] h-[340px] sm:w-[380px] sm:h-[380px] md:w-[420px] md:h-[420px] flex items-center justify-center">
                            <div id="photo-frame" class="photo-frame bg-white rounded-2xl shadow-lg flex items-center justify-center" style="width:100%;height:100%;border-radius:18px;padding:18px;box-shadow:0 12px 30px rgba(0,0,0,0.08);">
                                <div id="photo-inner" style="width:100%;height:100%;background:linear-gradient(180deg,#fff,#fbfffb);border-radius:12px;padding:6px;box-shadow:inset 0 0 0 6px rgba(243,250,246,0.9);overflow:hidden;position:relative;">
                                    @php
                                        // Prepare JS-friendly image array
                                        $eventImageUrls = [];
                                        if($event->images && count($event->images) > 0){
                                            foreach($event->images as $img){
                                                $eventImageUrls[] = asset('storage/' . $img);
                                            }
                                        }
                                    @endphp
                                    @if(!empty($eventImageUrls))
                                        <img id="main-event-image" src="{{ $eventImageUrls[0] }}" alt="{{ $event->title }}" class="w-full h-full object-cover rounded-xl" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[#f3faf6] rounded-xl">
                                            <svg class="w-28 h-28 text-[#2d5a27] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                <!-- Right Column - Framed Photo Card -->
                <div class="lg:col-span-2 flex items-center justify-center order-1 lg:order-2 p-0 m-0">
                    <div class="relative flex flex-col items-center justify-center">
                        <!-- Framed Photo -->
                        <div class="photo-card relative w-[340px] h-[340px] sm:w-[380px] sm:h-[380px] md:w-[420px] md:h-[420px] flex items-center justify-center">
                            <div id="photo-frame" class="photo-frame bg-white rounded-2xl shadow-lg flex items-center justify-center" style="width:100%;height:100%;border-radius:18px;padding:18px;box-shadow:0 12px 30px rgba(0,0,0,0.08);">
                                <div id="photo-inner" style="width:100%;height:100%;background:linear-gradient(180deg,#fff,#fbfffb);border-radius:12px;padding:6px;box-shadow:inset 0 0 0 6px rgba(243,250,246,0.9);overflow:hidden;position:relative;">
                                    @php
                                        // Prepare JS-friendly image array
                                        $eventImageUrls = [];
                                        if($event->images && count($event->images) > 0){
                                            foreach($event->images as $img){
                                                $eventImageUrls[] = asset('storage/' . $img);
                                            }
                                        }
                                    @endphp
                                    @if(!empty($eventImageUrls))
                                        <img id="main-event-image" src="{{ $eventImageUrls[0] }}" alt="{{ $event->title }}" class="w-full h-full object-cover rounded-xl" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[#f3faf6] rounded-xl">
                                            <svg class="w-28 h-28 text-[#2d5a27] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            

                            <!-- Date Badge (moved to top-right) -->
                            <div id="date-badge" class="absolute bg-[#2d5a27] text-white rounded-lg shadow-xl overflow-hidden z-30" style="min-width:60px; top:16px; right:16px;">
                                <div class="px-3 py-2 text-center bg-[#2d5a27]">
                                    <div class="text-3xl font-bold leading-none">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
                                    <div class="text-xs font-medium uppercase mt-1">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
                                    @endif
                                </div>
                            </div>

                            

                            <!-- Date Badge (moved to top-right) -->
                            <div id="date-badge" class="absolute bg-[#2d5a27] text-white rounded-lg shadow-xl overflow-hidden z-30" style="min-width:60px; top:16px; right:16px;">
                                <div class="px-3 py-2 text-center bg-[#2d5a27]">
                                    <div class="text-3xl font-bold leading-none">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
                                    <div class="text-xs font-medium uppercase mt-1">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Optional carousel dots (kept small) -->
                        @if(!empty($eventImageUrls) && count($eventImageUrls) > 1)
                            <div id="carousel-dots" class="flex items-center gap-2 mt-4" role="tablist" aria-label="Event images">
                                @foreach($eventImageUrls as $idx => $url)
                                    <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300 {{ $idx === 0 ? 'active-dot' : '' }}" aria-controls="main-event-image" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}" data-carousel-index="{{ $idx }}" data-img-src="{{ $url }}" title="Voir l'image {{ $idx + 1 }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <!-- Optional carousel dots (kept small) -->
                        @if(!empty($eventImageUrls) && count($eventImageUrls) > 1)
                            <div id="carousel-dots" class="flex items-center gap-2 mt-4" role="tablist" aria-label="Event images">
                                @foreach($eventImageUrls as $idx => $url)
                                    <button class="carousel-dot w-3 h-3 rounded-full bg-gray-300 transition-all duration-300 {{ $idx === 0 ? 'active-dot' : '' }}" aria-controls="main-event-image" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}" data-carousel-index="{{ $idx }}" data-img-src="{{ $url }}" title="Voir l'image {{ $idx + 1 }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Map Section -->
    <div class="container mx-auto px-4 my-8">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">            
            <!-- Compact Map Container with Integrated Controls -->
            <div class="compact-map-container rounded-lg overflow-hidden border border-gray-200">
                <!-- Map Header with Controls -->
                <div class="map-header bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <button id="start-navigation" class="px-4 py-2 bg-[#2d5a27] text-white rounded-md text-sm font-medium hover:bg-[#234420] transition-colors flex items-center gap-2">
                         
                            Démarrer la navigation
                        </button>
                        <button id="stop-navigation" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 transition-colors hidden flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                            Arrêter
            </div>
        </div>
    </div>

    <!-- Compact Map Section -->
    <div class="container mx-auto px-4 my-8">
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">            
            <!-- Compact Map Container with Integrated Controls -->
            <div class="compact-map-container rounded-lg overflow-hidden border border-gray-200">
                <!-- Map Header with Controls -->
                <div class="map-header bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <button id="start-navigation" class="px-4 py-2 bg-[#2d5a27] text-white rounded-md text-sm font-medium hover:bg-[#234420] transition-colors flex items-center gap-2">
                         
                            Démarrer la navigation
                        </button>
                        <button id="stop-navigation" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 transition-colors hidden flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                            </svg>
                            Arrêter
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="map-status" class="text-xs text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
                            Autorisez la géolocalisation pour activer le guidage
                        </span>
                    </div>
                </div>
                
                <!-- Map Display -->
                <div id="route-map" class="route-map" style="height: 300px;"></div>
                
                <!-- Map Footer with Info -->
                <div class="map-footer bg-gray-50 px-4 py-2 border-t border-gray-200">
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span id="distance-info">Distance: --</span>
                        <span id="time-info">Temps estimé: --</span>
                        <span>Powered by OpenStreetMap</span>
                    </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="map-status" class="text-xs text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
                            Autorisez la géolocalisation pour activer le guidage
                        </span>
                    </div>
                </div>
                
                <!-- Map Display -->
                <div id="route-map" class="route-map" style="height: 300px;"></div>
                
                <!-- Map Footer with Info -->
                <div class="map-footer bg-gray-50 px-4 py-2 border-t border-gray-200">
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span id="distance-info">Distance: --</span>
                        <span id="time-info">Temps estimé: --</span>
                        <span>Powered by OpenStreetMap</span>
                    </div>
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

/* Event image carousel small additions */
.carousel-dot {
    border: none;
    padding: 0;
    cursor: pointer;
}
.carousel-dot.active-dot {
    background: #2d5a27;
    width: 24px !important;
    height: 6px !important;
    border-radius: 999px !important;
}
.carousel-dot:focus {
    outline: 2px solid rgba(45,90,39,0.25);
}

/* Ensure date badge floats above image */
#date-badge { z-index: 30; }


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

/* Event image carousel small additions */
.carousel-dot {
    border: none;
    padding: 0;
    cursor: pointer;
}
.carousel-dot.active-dot {
    background: #2d5a27;
    width: 24px !important;
    height: 6px !important;
    border-radius: 999px !important;
}
.carousel-dot:focus {
    outline: 2px solid rgba(45,90,39,0.25);
}

/* Ensure date badge floats above image */
#date-badge { z-index: 30; }


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

/* Force badge and avatar to be positioned above the photo and anchored */
.photo-card .date-badge{ position: absolute; top: 16px; right: 16px; z-index: 40; }
.photo-card .organizer-avatar{ position: absolute; top: 24px; left: 24px; z-index: 35; }

.event-meta-row .prose{ margin-top:0; }

/* Small cards strip */
.small-cards-strip{ background: linear-gradient(180deg, #f3faf6 0%, #ffffff 100%); }
.small-cards-strip .flex-shrink-0{ background: white; border-radius: 12px; padding: 16px; box-shadow: 0 6px 20px rgba(45,90,39,0.06); }

/* Compact horizontal info cards */
.small-info-card{
    width: 150px;
    min-width: 120px;
    background: white;
    border-radius: 12px;
    border: 1px solid #eef5ef;
    padding: 10px 12px;
    box-shadow: 0 6px 16px rgba(45,90,39,0.04);
    text-align: center;
}
.small-info-card .icon-wrap{ width:36px; height:36px; background:#f3faf6; border-radius:8px; display:flex; align-items:center; justify-content:center; margin:0 auto; }
.small-info-card p{ margin:0; }
.small-info-card .text-xs{ font-size:12px; }
.small-info-card .text-sm{ font-size:13px; }

/* Compact Map Styles */
.compact-map-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.map-header {
    background: linear-gradient(90deg, #f8faf9 0%, #f1f7f2 100%);
}

.map-footer {
    background: #f9fafb;
}

.route-map {
    width: 100%;
    background: #f8faf9;
}

/* Custom marker styles */
.custom-user-marker {
    background: #2d5a27;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

@media (max-width: 640px){
    .small-info-card{ width: 45%; min-width: unset; }
    .map-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    .map-header .flex {
        flex-wrap: wrap;
    }
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

/* Force badge and avatar to be positioned above the photo and anchored */
.photo-card .date-badge{ position: absolute; top: 16px; right: 16px; z-index: 40; }
.photo-card .organizer-avatar{ position: absolute; top: 24px; left: 24px; z-index: 35; }

.event-meta-row .prose{ margin-top:0; }

/* Small cards strip */
.small-cards-strip{ background: linear-gradient(180deg, #f3faf6 0%, #ffffff 100%); }
.small-cards-strip .flex-shrink-0{ background: white; border-radius: 12px; padding: 16px; box-shadow: 0 6px 20px rgba(45,90,39,0.06); }

/* Compact horizontal info cards */
.small-info-card{
    width: 150px;
    min-width: 120px;
    background: white;
    border-radius: 12px;
    border: 1px solid #eef5ef;
    padding: 10px 12px;
    box-shadow: 0 6px 16px rgba(45,90,39,0.04);
    text-align: center;
}
.small-info-card .icon-wrap{ width:36px; height:36px; background:#f3faf6; border-radius:8px; display:flex; align-items:center; justify-content:center; margin:0 auto; }
.small-info-card p{ margin:0; }
.small-info-card .text-xs{ font-size:12px; }
.small-info-card .text-sm{ font-size:13px; }

/* Compact Map Styles */
.compact-map-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.map-header {
    background: linear-gradient(90deg, #f8faf9 0%, #f1f7f2 100%);
}

.map-footer {
    background: #f9fafb;
}

.route-map {
    width: 100%;
    background: #f8faf9;
}

/* Custom marker styles */
.custom-user-marker {
    background: #2d5a27;
    border: 3px solid white;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

@media (max-width: 640px){
    .small-info-card{ width: 45%; min-width: unset; }
    .map-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    .map-header .flex {
        flex-wrap: wrap;
    }
}

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dotsContainer = document.getElementById('carousel-dots');
        const mainImage = document.getElementById('main-event-image');
        if(!mainImage) return; // nothing to do

        // Image URLs embedded server-side
        const imageUrls = @json($eventImageUrls ?? []);
        let currentIndex = 0;

        function setActiveDot(idx) {
            const dots = dotsContainer ? dotsContainer.querySelectorAll('.carousel-dot') : [];
            dots.forEach((d, i) => {
                d.classList.toggle('active-dot', i === idx);
                d.setAttribute('aria-selected', i === idx ? 'true' : 'false');
            });
        }

        if(dotsContainer) {
            dotsContainer.addEventListener('click', function(e) {
                const btn = e.target.closest('.carousel-dot');
                if(!btn) return;
                const idx = parseInt(btn.dataset.carouselIndex, 10);
                const src = btn.dataset.imgSrc;
                if(typeof idx === 'number' && src) {
                    mainImage.src = src;
                    currentIndex = idx;
                    setActiveDot(currentIndex);
                }
            });

            // Keyboard navigation for dots container
            dotsContainer.addEventListener('keydown', function(e) {
                if(e.key === 'ArrowRight') {
                    e.preventDefault();
                    currentIndex = (currentIndex + 1) % imageUrls.length;
                    mainImage.src = imageUrls[currentIndex];
                    setActiveDot(currentIndex);
                } else if(e.key === 'ArrowLeft') {
                    e.preventDefault();
                    currentIndex = (currentIndex - 1 + imageUrls.length) % imageUrls.length;
                    mainImage.src = imageUrls[currentIndex];
                    setActiveDot(currentIndex);
                }
            });
        }

        // Allow clicking on main image to advance
        mainImage.style.cursor = 'pointer';
        mainImage.addEventListener('click', function() {
            if(!imageUrls || imageUrls.length <= 1) return;
            currentIndex = (currentIndex + 1) % imageUrls.length;
            mainImage.src = imageUrls[currentIndex];
            setActiveDot(currentIndex);
        });

        // Initialize active dot
        setActiveDot(0);
    });
</script>

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
<!-- Leaflet and routing script -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event coordinates from server - properly escaped
    const eventLat = {!! json_encode($event->location->latitude ?? null) !!};
    const eventLng = {!! json_encode($event->location->longitude ?? null) !!};

    const mapEl = document.getElementById('route-map');
    if (!mapEl || !eventLat || !eventLng) {
        document.getElementById('map-status').textContent = 'Coordonnées de l\'événement manquantes.';
        return;
    }

    // Initialize map
    const map = L.map('route-map').setView([eventLat, eventLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '\u00A9 OpenStreetMap contributors'
    }).addTo(map);

    // Create custom icons
    const eventIcon = L.divIcon({
        className: 'custom-event-marker',
        html: '<div style="background:#2d5a27; border:3px solid white; border-radius:50%; width:20px; height:20px; box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const userIcon = L.divIcon({
        className: 'custom-user-marker',
        html: '<div style="background:#dc2626; border:3px solid white; border-radius:50%; width:16px; height:16px; box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>',
        iconSize: [16, 16],
        iconAnchor: [8, 8]
    });

    // Add event marker
    const eventMarker = L.marker([eventLat, eventLng], { icon: eventIcon })
        .addTo(map)
        .bindPopup({!! json_encode($event->title) !!})
        .openPopup();

    let userMarker = null;
    let routeLayer = null;
    let watchId = null;

    function formatDistance(meters) {
        if (meters < 1000) {
            return Math.round(meters) + ' m';
        } else {
            return (meters / 1000).toFixed(1) + ' km';
        }
    }

    function formatTime(seconds) {
        const minutes = Math.round(seconds / 60);
        if (minutes < 60) {
            return minutes + ' min';
        } else {
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            return hours + 'h' + (remainingMinutes > 0 ? remainingMinutes + 'min' : '');
        }
    }

    function fetchRoute(fromLat, fromLng, toLat, toLng) {
        const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLng}?overview=full&geometries=geojson`;
        return fetch(url).then(r => r.json());
    }

    function drawRoute(geojson, routeData) {
        if (routeLayer) {
            map.removeLayer(routeLayer);
            routeLayer = null;
        }
        
        routeLayer = L.geoJSON(geojson, { 
            style: { 
                color: '#2d5a27', 
                weight: 6, 
                opacity: 0.8,
                lineCap: 'round',
                lineJoin: 'round'
            } 
        }).addTo(map);
        
        // Update route info
        if (routeData && routeData.routes && routeData.routes[0]) {
            const route = routeData.routes[0];
            document.getElementById('distance-info').textContent = 'Distance: ' + formatDistance(route.distance);
            document.getElementById('time-info').textContent = 'Temps estimé: ' + formatTime(route.duration);
        }
        
        // Fit map to show both markers and route
        const group = new L.featureGroup([eventMarker, userMarker, routeLayer]);
        map.fitBounds(group.getBounds(), { padding: [20, 20] });
    }

    function startNavigation() {
        if (!navigator.geolocation) {
            document.getElementById('map-status').textContent = 'Géolocalisation non supportée par ce navigateur.';
            return;
        }

        document.getElementById('start-navigation').classList.add('hidden');
        document.getElementById('stop-navigation').classList.remove('hidden');
        document.getElementById('map-status').textContent = 'Navigation en cours...';
        document.getElementById('map-status').className = 'text-xs text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-200';

        // Watch position and update route
        watchId = navigator.geolocation.watchPosition(async (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            if (userMarker) {
                userMarker.setLatLng([lat, lng]);
            } else {
                userMarker = L.marker([lat, lng], { icon: userIcon })
                    .addTo(map)
                    .bindPopup('Votre position actuelle')
                    .openPopup();
            }

            try {
                const data = await fetchRoute(lat, lng, eventLat, eventLng);
                if (data && data.routes && data.routes.length) {
                    drawRoute(data.routes[0].geometry, data);
                }
            } catch (err) {
                console.error('Routing error', err);
                document.getElementById('map-status').textContent = 'Erreur de calcul d\'itinéraire';
                document.getElementById('map-status').className = 'text-xs text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-200';
            }
        }, (err) => {
            let errorMessage = 'Erreur de géolocalisation: ';
            switch(err.code) {
                case err.PERMISSION_DENIED:
                    errorMessage += 'Accès refusé';
                    break;
                case err.POSITION_UNAVAILABLE:
                    errorMessage += 'Position indisponible';
                    break;
                case err.TIMEOUT:
                    errorMessage += 'Timeout';
                    break;
                default:
                    errorMessage += 'Erreur inconnue';
            }
            document.getElementById('map-status').textContent = errorMessage;
            document.getElementById('map-status').className = 'text-xs text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-200';
        }, { 
            enableHighAccuracy: true, 
            maximumAge: 10000, 
            timeout: 15000 
        });
    }

    function stopNavigation() {
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        
        document.getElementById('start-navigation').classList.remove('hidden');
        document.getElementById('stop-navigation').classList.add('hidden');
        document.getElementById('map-status').textContent = 'Navigation arrêtée';
        document.getElementById('map-status').className = 'text-xs text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200';
        
        // Clear route info
        document.getElementById('distance-info').textContent = 'Distance: --';
        document.getElementById('time-info').textContent = 'Temps estimé: --';
        
        // Remove route and user marker
        if (routeLayer) {
            map.removeLayer(routeLayer);
            routeLayer = null;
        }
        if (userMarker) {
            map.removeLayer(userMarker);
            userMarker = null;
        }
        
        // Reset map view to event
        map.setView([eventLat, eventLng], 13);
    }

    document.getElementById('start-navigation').addEventListener('click', startNavigation);
    document.getElementById('stop-navigation').addEventListener('click', stopNavigation);
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dotsContainer = document.getElementById('carousel-dots');
        const mainImage = document.getElementById('main-event-image');
        if(!mainImage) return; // nothing to do

        // Image URLs embedded server-side
        const imageUrls = @json($eventImageUrls ?? []);
        let currentIndex = 0;

        function setActiveDot(idx) {
            const dots = dotsContainer ? dotsContainer.querySelectorAll('.carousel-dot') : [];
            dots.forEach((d, i) => {
                d.classList.toggle('active-dot', i === idx);
                d.setAttribute('aria-selected', i === idx ? 'true' : 'false');
            });
        }

        if(dotsContainer) {
            dotsContainer.addEventListener('click', function(e) {
                const btn = e.target.closest('.carousel-dot');
                if(!btn) return;
                const idx = parseInt(btn.dataset.carouselIndex, 10);
                const src = btn.dataset.imgSrc;
                if(typeof idx === 'number' && src) {
                    mainImage.src = src;
                    currentIndex = idx;
                    setActiveDot(currentIndex);
                }
            });

            // Keyboard navigation for dots container
            dotsContainer.addEventListener('keydown', function(e) {
                if(e.key === 'ArrowRight') {
                    e.preventDefault();
                    currentIndex = (currentIndex + 1) % imageUrls.length;
                    mainImage.src = imageUrls[currentIndex];
                    setActiveDot(currentIndex);
                } else if(e.key === 'ArrowLeft') {
                    e.preventDefault();
                    currentIndex = (currentIndex - 1 + imageUrls.length) % imageUrls.length;
                    mainImage.src = imageUrls[currentIndex];
                    setActiveDot(currentIndex);
                }
            });
        }

        // Allow clicking on main image to advance
        mainImage.style.cursor = 'pointer';
        mainImage.addEventListener('click', function() {
            if(!imageUrls || imageUrls.length <= 1) return;
            currentIndex = (currentIndex + 1) % imageUrls.length;
            mainImage.src = imageUrls[currentIndex];
            setActiveDot(currentIndex);
        });

        // Initialize active dot
        setActiveDot(0);
    });
</script>

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
<!-- Leaflet and routing script -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event coordinates from server - properly escaped
    const eventLat = {!! json_encode($event->location->latitude ?? null) !!};
    const eventLng = {!! json_encode($event->location->longitude ?? null) !!};

    const mapEl = document.getElementById('route-map');
    if (!mapEl || !eventLat || !eventLng) {
        document.getElementById('map-status').textContent = 'Coordonnées de l\'événement manquantes.';
        return;
    }

    // Initialize map
    const map = L.map('route-map').setView([eventLat, eventLng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '\u00A9 OpenStreetMap contributors'
    }).addTo(map);

    // Create custom icons
    const eventIcon = L.divIcon({
        className: 'custom-event-marker',
        html: '<div style="background:#2d5a27; border:3px solid white; border-radius:50%; width:20px; height:20px; box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const userIcon = L.divIcon({
        className: 'custom-user-marker',
        html: '<div style="background:#dc2626; border:3px solid white; border-radius:50%; width:16px; height:16px; box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>',
        iconSize: [16, 16],
        iconAnchor: [8, 8]
    });

    // Add event marker
    const eventMarker = L.marker([eventLat, eventLng], { icon: eventIcon })
        .addTo(map)
        .bindPopup({!! json_encode($event->title) !!})
        .openPopup();

    let userMarker = null;
    let routeLayer = null;
    let watchId = null;

    function formatDistance(meters) {
        if (meters < 1000) {
            return Math.round(meters) + ' m';
        } else {
            return (meters / 1000).toFixed(1) + ' km';
        }
    }

    function formatTime(seconds) {
        const minutes = Math.round(seconds / 60);
        if (minutes < 60) {
            return minutes + ' min';
        } else {
            const hours = Math.floor(minutes / 60);
            const remainingMinutes = minutes % 60;
            return hours + 'h' + (remainingMinutes > 0 ? remainingMinutes + 'min' : '');
        }
    }

    function fetchRoute(fromLat, fromLng, toLat, toLng) {
        const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLng}?overview=full&geometries=geojson`;
        return fetch(url).then(r => r.json());
    }

    function drawRoute(geojson, routeData) {
        if (routeLayer) {
            map.removeLayer(routeLayer);
            routeLayer = null;
        }
        
        routeLayer = L.geoJSON(geojson, { 
            style: { 
                color: '#2d5a27', 
                weight: 6, 
                opacity: 0.8,
                lineCap: 'round',
                lineJoin: 'round'
            } 
        }).addTo(map);
        
        // Update route info
        if (routeData && routeData.routes && routeData.routes[0]) {
            const route = routeData.routes[0];
            document.getElementById('distance-info').textContent = 'Distance: ' + formatDistance(route.distance);
            document.getElementById('time-info').textContent = 'Temps estimé: ' + formatTime(route.duration);
        }
        
        // Fit map to show both markers and route
        const group = new L.featureGroup([eventMarker, userMarker, routeLayer]);
        map.fitBounds(group.getBounds(), { padding: [20, 20] });
    }

    function startNavigation() {
        if (!navigator.geolocation) {
            document.getElementById('map-status').textContent = 'Géolocalisation non supportée par ce navigateur.';
            return;
        }

        document.getElementById('start-navigation').classList.add('hidden');
        document.getElementById('stop-navigation').classList.remove('hidden');
        document.getElementById('map-status').textContent = 'Navigation en cours...';
        document.getElementById('map-status').className = 'text-xs text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-200';

        // Watch position and update route
        watchId = navigator.geolocation.watchPosition(async (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            if (userMarker) {
                userMarker.setLatLng([lat, lng]);
            } else {
                userMarker = L.marker([lat, lng], { icon: userIcon })
                    .addTo(map)
                    .bindPopup('Votre position actuelle')
                    .openPopup();
            }

            try {
                const data = await fetchRoute(lat, lng, eventLat, eventLng);
                if (data && data.routes && data.routes.length) {
                    drawRoute(data.routes[0].geometry, data);
                }
            } catch (err) {
                console.error('Routing error', err);
                document.getElementById('map-status').textContent = 'Erreur de calcul d\'itinéraire';
                document.getElementById('map-status').className = 'text-xs text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-200';
            }
        }, (err) => {
            let errorMessage = 'Erreur de géolocalisation: ';
            switch(err.code) {
                case err.PERMISSION_DENIED:
                    errorMessage += 'Accès refusé';
                    break;
                case err.POSITION_UNAVAILABLE:
                    errorMessage += 'Position indisponible';
                    break;
                case err.TIMEOUT:
                    errorMessage += 'Timeout';
                    break;
                default:
                    errorMessage += 'Erreur inconnue';
            }
            document.getElementById('map-status').textContent = errorMessage;
            document.getElementById('map-status').className = 'text-xs text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-200';
        }, { 
            enableHighAccuracy: true, 
            maximumAge: 10000, 
            timeout: 15000 
        });
    }

    function stopNavigation() {
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        
        document.getElementById('start-navigation').classList.remove('hidden');
        document.getElementById('stop-navigation').classList.add('hidden');
        document.getElementById('map-status').textContent = 'Navigation arrêtée';
        document.getElementById('map-status').className = 'text-xs text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200';
        
        // Clear route info
        document.getElementById('distance-info').textContent = 'Distance: --';
        document.getElementById('time-info').textContent = 'Temps estimé: --';
        
        // Remove route and user marker
        if (routeLayer) {
            map.removeLayer(routeLayer);
            routeLayer = null;
        }
        if (userMarker) {
            map.removeLayer(userMarker);
            userMarker = null;
        }
        
        // Reset map view to event
        map.setView([eventLat, eventLng], 13);
    }

    document.getElementById('start-navigation').addEventListener('click', startNavigation);
    document.getElementById('stop-navigation').addEventListener('click', stopNavigation);
});
</script>
@endpush
@endsection