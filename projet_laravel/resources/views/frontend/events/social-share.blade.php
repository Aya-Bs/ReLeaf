@extends('layouts.frontend')

@section('title', 'Partager ' . $event->title)

@section('content')
<style>
    .carbon-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

      .carbon-cardr {
        background: #2d5a27;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .horizontal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 12px;
        align-items: start;
    }

    .stat-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
    }

    .stat-number {
        font-size: 20px;
        font-weight: bold;
        color: #2d5a27;
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .platform-btn {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 8px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .platform-btn:hover {
        border-color: #2d5a27;
        transform: translateY(-1px);
    }

    .platform-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 6px;
        font-size: 14px;
    }

    .facebook-icon {
        background: #1877F2;
        color: white;
    }

    .whatsapp-icon {
        background: #25D366;
        color: white;
    }

    .copy-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 12px;
    }

    .copy-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px;
        font-size: 11px;
        color: #475569;
        transition: all 0.2s ease;
        cursor: pointer;
        text-align: center;
    }

    .copy-btn:hover {
        background: #2d5a27;
        color: white;
        border-color: #2d5a27;
    }

    .history-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 6px;
        font-size: 11px;
    }

    .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 6px;
        color: #2d5a27;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-12">
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="carbon-cardr text-center mb-4" style ="color:white" >
            <h1 class="text-base font-bold text-gray-900 mb-1" >Partager l'événement</h1>
            <p class="text-sm text-gray-600">{{ $event->title }}</p>
        </div>

        <!-- Stats + Share Buttons - HORIZONTAL -->
        <div class="carbon-card">
            <h2 class="section-title">
                <i class="fas fa-chart-simple"></i>
                Partage & Statistiques
            </h2>
            
            <div class="horizontal-grid">
                <!-- Stats -->
                <div class="stat-card">
                    <div class="stat-number">{{ $shareStats['total_shares'] ?? 0 }}</div>
                    <div class="stat-label">Total</div>
                </div>
                
                <!-- Facebook Button -->
                <div class="platform-btn" onclick="shareOnPlatform('facebook')">
                    <div class="platform-icon facebook-icon">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="text-xs font-medium text-gray-700">Facebook</div>
                    <div class="text-xs text-gray-500 mt-1">{{ ($shareStats['platform_breakdown']['facebook'] ?? 0) }} partages</div>
                </div>
                
                <!-- WhatsApp Button -->
                <div class="platform-btn" onclick="shareOnPlatform('whatsapp')">
                    <div class="platform-icon whatsapp-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="text-xs font-medium text-gray-700">WhatsApp</div>
                    <div class="text-xs text-gray-500 mt-1">{{ ($shareStats['platform_breakdown']['whatsapp'] ?? 0) }} partages</div>
                </div>
            </div>

            <!-- Copy Buttons - HORIZONTAL -->
            <div class="copy-section">
                <button onclick="copyToClipboard('{{ route('events.show', $event) }}')" 
                        class="copy-btn">
                    <i class="fas fa-link mr-1"></i>Lien
                </button>
                
                <button onclick="copyMessage()" 
                        class="copy-btn">
                    <i class="fas fa-message mr-1"></i>Message
                </button>
            </div>
        </div>

        <!-- Recent History -->
        @if(isset($shareStats['recent_shares']) && $shareStats['recent_shares']->count() > 0)
        <div class="carbon-card">
            <h2 class="section-title">
                <i class="fas fa-history"></i>
                Historique récent
            </h2>
            
            <div class="space-y-2">
                @foreach($shareStats['recent_shares'] as $share)
                <div class="history-item">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded flex items-center justify-center mr-2 
                                {{ $share->platform == 'facebook' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                                <i class="fab fa-{{ $share->platform }} text-xs"></i>
                            </div>
                            <span class="text-xs text-gray-700">{{ ucfirst($share->platform) }}</span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $share->shared_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Footer -->
       
    </div>
</div>



           
<script>
console.log('Social Share Page Loaded', {
    eventId: {{ $event->id }},
    eventTitle: '{{ $event->title }}'
});

function shareOnPlatform(platform) {
    console.log('Share button clicked:', platform);
    
    const modal = document.getElementById('loadingModal');
    modal.classList.remove('hidden');

    fetch(`{{ route('events.social-share.store', $event) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ platform: platform })
    })
    .then(response => response.json())
    .then(data => {
        modal.classList.add('hidden');
        
        if (data.success) {
            if (data.type === 'direct' && data.url) {
                window.open(data.url, `${platform}-share`, 'width=600,height=400');
                showNotification('Partage ouvert!', 'success');
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        } else {
            showNotification(data.error || 'Erreur lors du partage', 'error');
        }
    })
    .catch(error => {
        modal.classList.add('hidden');
        showNotification('Erreur réseau', 'error');
    });
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Lien copié!', 'success');
    }).catch(err => {
        showNotification('Erreur de copie', 'error');
    });
}

function copyMessage() {
    const message = `🎉 Événement écologique: {{ $event->title }}

📅 {{ $event->date->format('d/m/Y à H:i') }}
📍 {{ $event->location->name ?? 'Lieu à confirmer' }}

{{ Str::limit($event->description, 150) }}

Plus d'infos: {{ route('events.show', $event) }}

#Écologie #Événement`;
    
    copyToClipboard(message);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-3 rounded-lg text-sm z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 2000);
}
</script>
@endsection