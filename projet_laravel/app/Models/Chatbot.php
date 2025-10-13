<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;

class Chatbot extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'language',
        'conversation_history',
        'last_intent',
        'user_preferences',
        'is_active',
        'last_activity',
    ];

    protected $casts = [
        'conversation_history' => 'array',
        'user_preferences' => 'array',
        'is_active' => 'boolean',
        'last_activity' => 'datetime',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir ou créer une session chatbot
     */
    public static function getOrCreateSession(): self
    {
        $sessionId = Session::getId();
        
        $chatbot = self::where('session_id', $sessionId)
                      ->where('is_active', true)
                      ->first();

        if (!$chatbot) {
            $chatbot = self::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'language' => self::detectLanguage(),
                'conversation_history' => [],
                'is_active' => true,
                'last_activity' => now(),
            ]);
        } else {
            $chatbot->update([
                'last_activity' => now(),
                'user_id' => auth()->id(), // Mettre à jour si l'utilisateur se connecte
            ]);
        }

        return $chatbot;
    }

    /**
     * Détecter la langue préférée
     */
    private static function detectLanguage(): string
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'en' => 'en',
            'ar' => 'ar',
            default => 'fr'
        };
    }

    /**
     * Ajouter un message à l'historique
     */
    public function addMessage(string $role, string $content, array $metadata = []): void
    {
        $history = $this->conversation_history ?? [];
        
        $history[] = [
            'role' => $role, // 'user' ou 'assistant'
            'content' => $content,
            'timestamp' => now()->toISOString(),
            'metadata' => $metadata,
        ];

        // Garder seulement les 20 derniers messages
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }

        $this->update([
            'conversation_history' => $history,
            'last_activity' => now(),
        ]);
    }

    /**
     * Obtenir les messages récents
     */
    public function getRecentMessages(int $limit = 10): array
    {
        $history = $this->conversation_history ?? [];
        return array_slice($history, -$limit);
    }

    /**
     * Mettre à jour les préférences utilisateur
     */
    public function updatePreferences(array $preferences): void
    {
        $currentPreferences = $this->user_preferences ?? [];
        $updatedPreferences = array_merge($currentPreferences, $preferences);
        
        $this->update(['user_preferences' => $updatedPreferences]);
    }

    /**
     * Fermer la session
     */
    public function closeSession(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Nettoyer les sessions inactives (à exécuter périodiquement)
     */
    public static function cleanupInactiveSessions(): void
    {
        self::where('is_active', true)
            ->where('last_activity', '<', now()->subHours(24))
            ->update(['is_active' => false]);
    }
}
