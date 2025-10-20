<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use App\Services\GeminiService;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_id',
        'event_id',
        'seat_number',
        'status',
        'reserved_at',
        'expires_at',
        'confirmed_at',
        'confirmed_by',
        'num_guests',
        'comments',
        'seat_details'
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'seat_details' => 'array'
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function certification(): HasOne
    {
        return $this->hasOne(Certification::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }


    /**
     * Méthodes utiles
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending';
    }

    public function confirm(User $admin): bool
    {
        if ($this->canBeConfirmed()) {
            $this->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => $admin->id
            ]);
            return true;
        }
        return false;
    }

    /**
     * Créer une nouvelle réservation
     */
    public static function createWithTimeout(array $data): self
    {
        $data['reserved_at'] = now();
        $data['expires_at'] = null; // Pas d'expiration automatique
        $data['status'] = 'pending';
        
        return self::create($data);
    }

    /**
     * 🤖 IA : Suggérer la meilleure place basée sur l'historique utilisateur (avec Gemini)
     */
    public static function suggestBestSeat(Event $event, User $user): array
    {
        // Analyser l'historique de réservations de l'utilisateur
        $userHistory = self::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->pluck('seat_number')
            ->toArray();

        // Déterminer les places disponibles (A1, A2, A3 uniquement)
        $reservedSeats = self::where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('seat_number')
            ->toArray();
        
        $visibleSeats = ['A1', 'A2', 'A3'];
        $availableSeats = array_diff($visibleSeats, $reservedSeats);

        if (empty($availableSeats)) {
            return [
                'seat_number' => 'A1',
                'reason' => 'Aucune place disponible pour le moment',
                'confidence' => 100,
                'ai_powered' => false
            ];
        }

        // Essayer d'utiliser Gemini API
        try {
            $geminiService = new GeminiService();
            
            // Préparer les détails de l'événement
            $eventDetails = [
                'title' => $event->title,
                'date' => $event->date->format('d/m/Y à H:i'),
                'location' => $event->location->name ?? 'Lieu non défini'
            ];

            // Appeler Gemini pour une recommandation intelligente
            $geminiSuggestion = $geminiService->suggestSeat($userHistory, $availableSeats, $eventDetails);
            
            if ($geminiSuggestion['ai_powered']) {
                return $geminiSuggestion;
            }
            
        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
        }

        // Fallback vers l'algorithme local si Gemini échoue
        return self::getLocalSuggestion($userHistory, $availableSeats);
    }

    /**
     * Algorithme local de fallback
     */
    private static function getLocalSuggestion(array $userHistory, array $availableSeats): array
    {
        if (empty($userHistory)) {
            // Nouvel utilisateur - suggérer A2 (place centrale)
            $suggestedSeat = in_array('A2', $availableSeats) ? 'A2' : reset($availableSeats);
            return [
                'seat_number' => $suggestedSeat,
                'reason' => 'Place centrale recommandée pour une meilleure expérience',
                'confidence' => 70,
                'ai_powered' => false
            ];
        }

        // Utilisateur avec historique - analyser les préférences
        $preferences = self::analyzeUserSeatPreferences($userHistory);
        
        // Calculer le score pour chaque place disponible
        $seatScores = [];
        foreach ($availableSeats as $seat) {
            $score = self::calculateSeatScore($seat, $preferences);
            $seatScores[$seat] = $score;
        }

        // Retourner la place avec le meilleur score
        arsort($seatScores);
        $bestSeat = array_key_first($seatScores);
        
        return [
            'seat_number' => $bestSeat,
            'reason' => self::generateRecommendationReason($preferences),
            'confidence' => self::calculateConfidence($userHistory),
            'ai_powered' => false
        ];
    }

    /**
     * Analyser les préférences de places de l'utilisateur
     */
    private static function analyzeUserSeatPreferences(array $seatHistory): array
    {
        $preferences = [
            'preferred_sections' => [],
            'preferred_rows' => [],
            'preferred_numbers' => []
        ];

        foreach ($seatHistory as $seat) {
            // Extraire la section (lettre)
            if (preg_match('/^([A-Z])/', $seat, $matches)) {
                $section = $matches[1];
                $preferences['preferred_sections'][$section] = 
                    ($preferences['preferred_sections'][$section] ?? 0) + 1;
            }

            // Extraire le numéro de rang
            if (preg_match('/^[A-Z](\d+)/', $seat, $matches)) {
                $row = $matches[1];
                $preferences['preferred_rows'][$row] = 
                    ($preferences['preferred_rows'][$row] ?? 0) + 1;
            }

            // Extraire le numéro de place
            if (preg_match('/\d+$/', $seat, $matches)) {
                $number = $matches[0];
                $preferences['preferred_numbers'][$number] = 
                    ($preferences['preferred_numbers'][$number] ?? 0) + 1;
            }
        }

        // Trier par fréquence
        arsort($preferences['preferred_sections']);
        arsort($preferences['preferred_rows']);
        arsort($preferences['preferred_numbers']);

        return $preferences;
    }

    /**
     * Trouver la place optimale basée sur les préférences
     */
    private static function findOptimalSeat(Event $event, array $preferences): string
    {
        // Récupérer les places déjà réservées
        $reservedSeats = self::where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('seat_number')
            ->toArray();

        // Générer toutes les places possibles
        $allSeats = self::generateAllSeats($event->max_participants);
        
        // 🔧 CORRECTION : Filtrer pour ne considérer que les places visibles (A1, A2, A3)
        $visibleSeats = ['A1', 'A2', 'A3'];
        $availableSeats = array_diff($visibleSeats, $reservedSeats);

        if (empty($availableSeats)) {
            return 'A1'; // Place par défaut
        }

        // Calculer le score pour chaque place disponible
        $seatScores = [];
        foreach ($availableSeats as $seat) {
            $score = self::calculateSeatScore($seat, $preferences);
            $seatScores[$seat] = $score;
        }

        // Retourner la place avec le meilleur score
        arsort($seatScores);
        return array_key_first($seatScores);
    }

    /**
     * Calculer le score d'une place basé sur les préférences
     */
    private static function calculateSeatScore(string $seat, array $preferences): int
    {
        $score = 0;

        // Score pour la section préférée
        if (preg_match('/^([A-Z])/', $seat, $matches)) {
            $section = $matches[1];
            $sectionRank = array_search($section, array_keys($preferences['preferred_sections']));
            if ($sectionRank !== false) {
                $score += (10 - $sectionRank) * 3; // Plus c'est préféré, plus le score est élevé
            }
        }

        // Score pour le rang préféré
        if (preg_match('/^[A-Z](\d+)/', $seat, $matches)) {
            $row = $matches[1];
            $rowRank = array_search($row, array_keys($preferences['preferred_rows']));
            if ($rowRank !== false) {
                $score += (10 - $rowRank) * 2;
            }
        }

        // Bonus pour les places centrales (rang 5-15)
        if (preg_match('/^[A-Z](\d+)/', $seat, $matches)) {
            $rowNumber = (int)$matches[1];
            if ($rowNumber >= 5 && $rowNumber <= 15) {
                $score += 5; // Bonus pour les places centrales
            }
        }

        return $score;
    }

    /**
     * Générer toutes les places possibles pour un événement
     */
    private static function generateAllSeats(int $maxParticipants): array
    {
        $seats = [];
        
        // Pour les petits événements (≤ 10), utiliser seulement la section A
        if ($maxParticipants <= 10) {
            for ($i = 1; $i <= $maxParticipants; $i++) {
                $seats[] = 'A' . $i;
            }
        } else {
            // Pour les grands événements, utiliser plusieurs sections
            $sections = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            $seatsPerSection = ceil($maxParticipants / count($sections));

            foreach ($sections as $section) {
                for ($i = 1; $i <= $seatsPerSection; $i++) {
                    $seats[] = $section . $i;
                }
            }
        }

        return $seats;
    }

    /**
     * Suggérer une place centrale pour les nouveaux utilisateurs
     */
    private static function suggestCentralSeat(Event $event): array
    {
        $reservedSeats = self::where('event_id', $event->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('seat_number')
            ->toArray();

        // 🔧 CORRECTION : Ne considérer que les places visibles (A1, A2, A3)
        $visibleSeats = ['A1', 'A2', 'A3'];
        $availableSeats = array_diff($visibleSeats, $reservedSeats);

        if (empty($availableSeats)) {
            $suggestedSeat = 'A1'; // Place par défaut
        } else {
            // Préférer A2 (place centrale parmi les 3 disponibles)
            $suggestedSeat = in_array('A2', $availableSeats) ? 'A2' : reset($availableSeats);
        }

        return [
            'seat_number' => $suggestedSeat,
            'reason' => 'Place centrale recommandée pour une meilleure expérience',
            'confidence' => 70,
            'preferences' => ['type' => 'central']
        ];
    }

    /**
     * Générer la raison de la recommandation
     */
    private static function generateRecommendationReason(array $preferences): string
    {
        $reasons = [];

        if (!empty($preferences['preferred_sections'])) {
            $topSection = array_key_first($preferences['preferred_sections']);
            $reasons[] = "Vous préférez généralement la section {$topSection}";
        }

        if (!empty($preferences['preferred_rows'])) {
            $topRow = array_key_first($preferences['preferred_rows']);
            $reasons[] = "Vous choisissez souvent le rang {$topRow}";
        }

        if (empty($reasons)) {
            return "Basé sur vos réservations précédentes";
        }

        return implode(' et ', $reasons);
    }

    /**
     * Calculer le niveau de confiance de la recommandation
     */
    private static function calculateConfidence(array $userHistory): int
    {
        $historyCount = count($userHistory);
        
        if ($historyCount >= 5) return 95;
        if ($historyCount >= 3) return 85;
        if ($historyCount >= 1) return 75;
        
        return 60; // Nouvel utilisateur
    }

}
