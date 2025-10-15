@extends('layouts.frontend')

@section('title', 'EcoEvents - Assistant IA')

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Sidebar avec informations -->
        <div class="col-md-3 bg-light p-4">
            <div class="text-center mb-4">
                <div class="chatbot-avatar mb-3">
                    <i class="fas fa-robot fa-3x text-eco"></i>
                </div>
                        <h4 class="text-eco">Assistant IA EcoEvents</h4>
                        <p class="text-muted small">Disponible 24/7</p>
                        @if($isGeminiEnabled)
                            <div class="gemini-status">
                                <span class="badge bg-success">
                                    <i class="fas fa-brain me-1"></i>Gemini AI Activé
                                </span>
                            </div>
                        @else
                            <div class="gemini-status">
                                <span class="badge bg-warning">
                                    <i class="fas fa-robot me-1"></i>Mode Règles
                                </span>
                            </div>
                        @endif
            </div>

            <!-- Langue actuelle -->
            <div class="language-selector mb-4">
                <label class="form-label small">Langue / Language / اللغة</label>
                <select class="form-select form-select-sm" id="languageSelector">
                    <option value="fr" {{ $chatbot->language === 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                    <option value="en" {{ $chatbot->language === 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                    <option value="ar" {{ $chatbot->language === 'ar' ? 'selected' : '' }}>🇹🇳 العربية</option>
                </select>
            </div>

            <!-- Suggestions rapides -->
            <div class="quick-suggestions">
                <h6 class="text-eco mb-3">Suggestions rapides</h6>
                <div class="suggestion-buttons" id="suggestionButtons">
                    <!-- Les suggestions seront chargées dynamiquement -->
                </div>
            </div>

            <!-- Statistiques -->
            <div class="chatbot-stats mt-4">
                <div class="card border-eco">
                    <div class="card-body p-3">
                        <h6 class="card-title text-eco mb-2">
                            <i class="fas fa-chart-line me-1"></i>Statistiques
                        </h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-eco" id="messagesCount">{{ count($recentMessages) }}</div>
                                    <div class="stat-label small">Messages</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <div class="stat-number text-eco" id="sessionTime">0</div>
                                    <div class="stat-label small">Min</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="chatbot-actions mt-3">
                <button class="btn btn-outline-eco btn-sm w-100 mb-2" onclick="clearChat()">
                    <i class="fas fa-trash me-1"></i>Effacer la conversation
                </button>
                <button class="btn btn-outline-secondary btn-sm w-100" onclick="exportChat()">
                    <i class="fas fa-download me-1"></i>Exporter
                </button>
            </div>
        </div>

        <!-- Zone de chat principale -->
        <div class="col-md-9 d-flex flex-column">
            <!-- Header du chat -->
            <div class="chat-header bg-white border-bottom p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 text-eco">
                            <i class="fas fa-comments me-2"></i>Conversation avec l'Assistant IA
                        </h5>
                        <small class="text-muted">En ligne • Réponse instantanée</small>
                    </div>
                    <div class="chat-status">
                        <span class="badge bg-success">
                            <i class="fas fa-circle me-1"></i>Actif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="chat-messages flex-grow-1 p-3" id="chatMessages">
                @if(count($recentMessages) > 0)
                    @foreach($recentMessages as $message)
                        <div class="message {{ $message['role'] === 'user' ? 'user-message' : 'bot-message' }}">
                            <div class="message-content">
                                {!! nl2br(e($message['content'])) !!}
                            </div>
                            <div class="message-time">
                                {{ \Carbon\Carbon::parse($message['timestamp'])->format('H:i') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Message de bienvenue -->
                    <div class="welcome-message text-center py-5">
                        <div class="welcome-icon mb-3">
                            <i class="fas fa-robot fa-4x text-eco"></i>
                        </div>
                        <h4 class="text-eco mb-3">Bienvenue dans l'Assistant IA EcoEvents !</h4>
                        <p class="text-muted mb-4">
                            Je suis là pour vous aider avec vos questions sur les événements, 
                            les réservations, les certificats et bien plus encore.
                        </p>
                        <div class="welcome-suggestions">
                            <button class="btn btn-outline-eco me-2 mb-2" onclick="sendMessage('Bonjour')">
                                👋 Bonjour
                            </button>
                            <button class="btn btn-outline-eco me-2 mb-2" onclick="sendMessage('Voir les événements')">
                                📅 Événements
                            </button>
                            <button class="btn btn-outline-eco me-2 mb-2" onclick="sendMessage('Aide réservation')">
                                🎫 Réservation
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Zone de saisie -->
            <div class="chat-input bg-white border-top p-3">
                <form id="chatForm" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="messageInput" 
                                   placeholder="Tapez votre message..."
                                   maxlength="1000"
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleEmoji()">
                                <i class="fas fa-smile"></i>
                            </button>
                        </div>
                        <div class="input-hint small text-muted mt-1">
                            Appuyez sur Entrée pour envoyer • Shift+Entrée pour une nouvelle ligne
                        </div>
                    </div>
                    <button type="submit" class="btn btn-eco" id="sendButton">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les emojis -->
<div class="modal fade" id="emojiModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Choisir un emoji</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="emoji-grid">
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('👋')">👋</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('😊')">😊</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('👍')">👍</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('❤️')">❤️</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('🌱')">🌱</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('🎉')">🎉</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('📅')">📅</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('🎫')">🎫</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('🏆')">🏆</button>
                    <button class="btn btn-sm btn-outline-secondary emoji-btn" onclick="insertEmoji('📄')">📄</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.chatbot-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2d5a27, #4a7c59);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 4px 15px rgba(45, 90, 39, 0.3);
}

.suggestion-buttons .btn {
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
    text-align: left;
    white-space: normal;
    height: auto;
    padding: 0.5rem;
}

.stat-item {
    padding: 0.5rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
}

.stat-label {
    color: #6c757d;
    font-size: 0.75rem;
}

.chat-messages {
    overflow-y: auto;
    max-height: calc(100vh - 200px);
    background: #f8f9fa;
}

.message {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
}

.user-message {
    align-items: flex-end;
}

.bot-message {
    align-items: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 18px;
    word-wrap: break-word;
    white-space: pre-wrap;
}

.user-message .message-content {
    background: #2d5a27;
    color: white;
    border-bottom-right-radius: 5px;
}

.bot-message .message-content {
    background: white;
    color: #333;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.message-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
    padding: 0 0.5rem;
}

.welcome-message {
    background: white;
    border-radius: 15px;
    margin: 2rem;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.welcome-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.chat-input {
    background: white;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
}

.input-hint {
    font-size: 0.75rem;
}

.emoji-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.5rem;
}

.emoji-btn {
    font-size: 1.2rem;
    padding: 0.5rem;
    border-radius: 8px;
}

.emoji-btn:hover {
    background-color: #2d5a27;
    color: white;
}

/* Scrollbar personnalisée */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #2d5a27;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #234420;
}

/* Animation pour les nouveaux messages */
.message.new-message {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Indicateur de frappe */
.typing-indicator {
    display: none;
    padding: 0.75rem 1rem;
    background: white;
    border-radius: 18px;
    border-bottom-left-radius: 5px;
    margin-bottom: 1rem;
    max-width: 70px;
}

.typing-indicator.show {
    display: block;
}

.typing-dots {
    display: flex;
    gap: 4px;
}

.typing-dot {
    width: 8px;
    height: 8px;
    background: #2d5a27;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
@endpush

@push('scripts')
<script>
let sessionStartTime = new Date();
let currentLanguage = '{{ $chatbot->language }}';

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    loadSuggestions();
    updateSessionTime();
    setInterval(updateSessionTime, 60000); // Mise à jour chaque minute
    
    // Auto-scroll vers le bas
    scrollToBottom();
});

// Gestion du formulaire de chat
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();
    sendMessage();
});

// Gestion des touches
document.getElementById('messageInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Changement de langue
document.getElementById('languageSelector').addEventListener('change', function() {
    currentLanguage = this.value;
    loadSuggestions();
    
    // Message de confirmation
    const messages = {
        'fr': 'Langue changée en français ! 🇫🇷',
        'en': 'Language changed to English! 🇺🇸',
        'ar': 'تم تغيير اللغة إلى العربية! 🇹🇳'
    };
    
    addBotMessage(messages[currentLanguage]);
});

// Envoyer un message
function sendMessage(message = null) {
    const input = document.getElementById('messageInput');
    const messageText = message || input.value.trim();
    
    if (!messageText) return;
    
    // Ajouter le message utilisateur
    addUserMessage(messageText);
    
    // Vider l'input
    if (!message) {
        input.value = '';
    }
    
    // Afficher l'indicateur de frappe
    showTypingIndicator();
    
    // Envoyer au serveur
    fetch('{{ route("chatbot.message") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            message: messageText,
            language: currentLanguage
        })
    })
    .then(response => response.json())
    .then(data => {
        hideTypingIndicator();
        
        if (data.success) {
            addBotMessage(data.response);
            
            // Afficher les suggestions si disponibles
            if (data.suggestions && data.suggestions.length > 0) {
                updateSuggestions(data.suggestions);
            }
        } else {
            addBotMessage('Désolé, une erreur est survenue. Veuillez réessayer.');
        }
    })
    .catch(error => {
        hideTypingIndicator();
        addBotMessage('Erreur de connexion. Vérifiez votre connexion internet.');
        console.error('Error:', error);
    });
}

// Ajouter un message utilisateur
function addUserMessage(content) {
    const messagesContainer = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message user-message new-message';
    messageDiv.innerHTML = `
        <div class="message-content">${escapeHtml(content)}</div>
        <div class="message-time">${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    scrollToBottom();
}

// Ajouter un message du bot
function addBotMessage(content) {
    const messagesContainer = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message bot-message new-message';
    messageDiv.innerHTML = `
        <div class="message-content">${formatBotMessage(content)}</div>
        <div class="message-time">${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    scrollToBottom();
}

// Formater le message du bot (support markdown simple)
function formatBotMessage(content) {
    return content
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/\n/g, '<br>');
}

// Échapper le HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Scroll vers le bas
function scrollToBottom() {
    const messagesContainer = document.getElementById('chatMessages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Charger les suggestions
function loadSuggestions() {
    fetch(`{{ route("chatbot.suggestions") }}?language=${currentLanguage}`)
        .then(response => response.json())
        .then(data => {
            updateSuggestions(data.suggestions);
        })
        .catch(error => {
            console.error('Error loading suggestions:', error);
        });
}

// Mettre à jour les suggestions
function updateSuggestions(suggestions) {
    const container = document.getElementById('suggestionButtons');
    container.innerHTML = '';
    
    suggestions.forEach(suggestion => {
        const button = document.createElement('button');
        button.className = 'btn btn-outline-eco btn-sm w-100';
        button.textContent = suggestion;
        button.onclick = () => sendMessage(suggestion);
        container.appendChild(button);
    });
}

// Indicateur de frappe
function showTypingIndicator() {
    const messagesContainer = document.getElementById('chatMessages');
    const indicator = document.createElement('div');
    indicator.className = 'typing-indicator show';
    indicator.id = 'typingIndicator';
    indicator.innerHTML = `
        <div class="typing-dots">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    `;
    
    messagesContainer.appendChild(indicator);
    scrollToBottom();
}

function hideTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    if (indicator) {
        indicator.remove();
    }
}

// Mettre à jour le temps de session
function updateSessionTime() {
    const now = new Date();
    const diffMs = now - sessionStartTime;
    const diffMins = Math.floor(diffMs / 60000);
    document.getElementById('sessionTime').textContent = diffMins;
}

// Effacer la conversation
function clearChat() {
    if (confirm('Êtes-vous sûr de vouloir effacer définitivement la conversation ?')) {
        // Afficher un indicateur de chargement
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-eco" role="status">
                    <span class="visually-hidden">Effacement...</span>
                </div>
                <p class="mt-3 text-muted">Effacement de la conversation...</p>
            </div>
        `;
        
        // Appeler l'API pour effacer définitivement
        fetch('{{ route("chatbot.clear") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher le message de succès
                messagesContainer.innerHTML = `
                    <div class="welcome-message text-center py-5">
                        <div class="welcome-icon mb-3">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                        <h4 class="text-success mb-3">Conversation effacée</h4>
                        <p class="text-muted mb-4">Comment puis-je vous aider ?</p>
                    </div>
                `;
                
                // Réinitialiser le compteur de messages
                document.getElementById('messagesCount').textContent = '0';
                
                // Afficher une notification de succès
                showNotification('Conversation effacée avec succès !', 'success');
            } else {
                throw new Error(data.message || 'Erreur lors de l\'effacement');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            
            // Afficher un message d'erreur
            messagesContainer.innerHTML = `
                <div class="text-center py-5">
                    <div class="welcome-icon mb-3">
                        <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                    </div>
                    <h4 class="text-danger mb-3">Erreur</h4>
                    <p class="text-muted mb-4">Impossible d'effacer la conversation. Veuillez réessayer.</p>
                    <button class="btn btn-outline-danger" onclick="location.reload()">
                        <i class="fas fa-refresh me-1"></i>Recharger la page
                    </button>
                </div>
            `;
            
            showNotification('Erreur lors de l\'effacement de la conversation', 'error');
        });
    }
}

// Afficher une notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Exporter la conversation
function exportChat() {
    const messages = document.querySelectorAll('.message');
    let exportText = 'Conversation avec l\'Assistant IA EcoEvents\n';
    exportText += '==========================================\n\n';
    
    messages.forEach(message => {
        const content = message.querySelector('.message-content').textContent;
        const time = message.querySelector('.message-time').textContent;
        const role = message.classList.contains('user-message') ? 'Utilisateur' : 'Assistant';
        
        exportText += `[${time}] ${role}: ${content}\n\n`;
    });
    
    // Créer et télécharger le fichier
    const blob = new Blob([exportText], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `conversation-ecoevents-${new Date().toISOString().split('T')[0]}.txt`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Gestion des emojis
function toggleEmoji() {
    const modal = new bootstrap.Modal(document.getElementById('emojiModal'));
    modal.show();
}

function insertEmoji(emoji) {
    const input = document.getElementById('messageInput');
    input.value += emoji;
    input.focus();
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('emojiModal'));
    modal.hide();
}
</script>
@endpush
