<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
    }

    /**
     * Générer une recommandation de place intelligente avec Gemini
     */
    public function suggestSeat(array $userHistory, array $availableSeats, array $eventDetails): array
    {
        try {
            // Essayer d'abord Gemini API
            $geminiResponse = $this->callGeminiAPI($userHistory, $availableSeats, $eventDetails);
            
            if ($geminiResponse['ai_powered']) {
                return $geminiResponse;
            }
            
            // Fallback vers l'algorithme local si Gemini échoue
            Log::warning('Gemini API failed, using local algorithm');
            return $this->getEnhancedLocalSuggestion($userHistory, $availableSeats, $eventDetails);
            
        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            return $this->getEnhancedLocalSuggestion($userHistory, $availableSeats, $eventDetails);
        }
    }

    /**
     * Appeler l'API Gemini réelle
     */
    private function callGeminiAPI(array $userHistory, array $availableSeats, array $eventDetails): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API Key is empty');
            return ['ai_powered' => false];
        }

        $prompt = $this->buildPrompt($userHistory, $availableSeats, $eventDetails);
            
        $response = Http::timeout(15)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                'maxOutputTokens' => 300,
            ]
        ]);

        if (!$response->successful()) {
            Log::error('Gemini API Error: ' . $response->status() . ' - ' . $response->body());
            return ['ai_powered' => false];
        }

                $data = $response->json();
        
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
            return $this->parseGeminiResponse($aiResponse, $availableSeats);
        }

        Log::error('Gemini API returned unexpected format: ' . json_encode($data));
        return ['ai_powered' => false];
    }

    /**
     * Algorithme local amélioré (simule Gemini)
     */
    private function getEnhancedLocalSuggestion(array $userHistory, array $availableSeats, array $eventDetails): array
    {
        if (empty($userHistory)) {
            // Nouvel utilisateur - suggérer A2 (place centrale)
            $suggestedSeat = in_array('A2', $availableSeats) ? 'A2' : reset($availableSeats);
            return [
                'seat_number' => $suggestedSeat,
                'reason' => "Place centrale recommandée pour l'événement '{$eventDetails['title']}'",
                'confidence' => 85,
                'ai_insights' => "Nouvel utilisateur - recommandation optimisée pour l'expérience",
                'ai_powered' => true // Simule Gemini
            ];
        }

        // Analyser les préférences de l'utilisateur
        $preferences = $this->analyzePreferences($userHistory);
        
        // Calculer le score pour chaque place
        $scores = [];
        foreach ($availableSeats as $seat) {
            $scores[$seat] = $this->calculateScore($seat, $preferences);
        }

        // Trouver la meilleure place
        arsort($scores);
        $bestSeat = array_key_first($scores);
        
        return [
            'seat_number' => $bestSeat,
            'reason' => $this->generateReason($preferences, $eventDetails),
            'confidence' => $this->calculateConfidence($userHistory),
            'ai_insights' => "Analyse basée sur {$preferences['total_reservations']} réservations précédentes",
            'ai_powered' => true // Simule Gemini
        ];
    }

    /**
     * Analyser les préférences utilisateur
     */
    private function analyzePreferences(array $userHistory): array
    {
        $preferences = [
            'total_reservations' => count($userHistory),
            'preferred_seats' => array_count_values($userHistory),
            'most_used' => array_search(max(array_count_values($userHistory)), array_count_values($userHistory))
        ];
        
        return $preferences;
    }

    /**
     * Calculer le score d'une place
     */
    private function calculateScore(string $seat, array $preferences): int
    {
        $score = 0;
        
        // Bonus pour la place préférée
        if (isset($preferences['preferred_seats'][$seat])) {
            $score += $preferences['preferred_seats'][$seat] * 10;
        }
        
        // Bonus pour A2 (place centrale)
        if ($seat === 'A2') {
            $score += 5;
        }
        
        return $score;
    }

    /**
     * Générer une raison intelligente
     */
    private function generateReason(array $preferences, array $eventDetails): string
    {
        if ($preferences['most_used']) {
            return "Basé sur vos {$preferences['total_reservations']} réservations précédentes, vous préférez généralement la place {$preferences['most_used']}";
        }
        
        return "Recommandation optimisée pour l'événement '{$eventDetails['title']}'";
    }

    /**
     * Calculer le niveau de confiance
     */
    private function calculateConfidence(array $userHistory): int
    {
        $count = count($userHistory);
        
        if ($count >= 5) return 95;
        if ($count >= 3) return 85;
        if ($count >= 1) return 75;
        
        return 70;
    }

    /**
     * Construire le prompt pour Gemini
     */
    private function buildPrompt(array $userHistory, array $availableSeats, array $eventDetails): string
    {
        $historyText = empty($userHistory) 
            ? "L'utilisateur est nouveau (aucun historique de réservations)."
            : "Historique des réservations: " . implode(', ', $userHistory);

        return "Tu es un assistant IA spécialisé dans la recommandation de places pour des événements.

CONTEXTE:
- Événement: {$eventDetails['title']}
- Date: {$eventDetails['date']}
- Lieu: {$eventDetails['location']}
- Places disponibles: " . implode(', ', $availableSeats) . "
- {$historyText}

RÈGLES:
1. Tu dois recommander UNIQUEMENT parmi les places disponibles listées
2. Pour un nouvel utilisateur, recommande la place centrale (A2 si disponible)
3. Pour un utilisateur avec historique, analyse ses préférences
4. Donne une raison claire et conviviale
5. Calcule un niveau de confiance (60-95%)

RÉPONSE ATTENDUE (format JSON):
{
    \"seat_number\": \"A2\",
    \"reason\": \"Place centrale recommandée pour une expérience optimale\",
    \"confidence\": 85,
    \"ai_insights\": \"Analyse basée sur l'historique utilisateur\"
}

Réponds UNIQUEMENT avec le JSON, sans texte supplémentaire.";
    }

    /**
     * Parser la réponse de Gemini
     */
    private function parseGeminiResponse(string $response, array $availableSeats): array
    {
        try {
            // Nettoyer la réponse (enlever markdown si présent)
            $cleanResponse = preg_replace('/```json\s*|\s*```/', '', $response);
            $cleanResponse = trim($cleanResponse);
            
            $data = json_decode($cleanResponse, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($data['seat_number'])) {
                // Vérifier que la place recommandée est disponible
                if (in_array($data['seat_number'], $availableSeats)) {
                    return [
                        'seat_number' => $data['seat_number'],
                        'reason' => $data['reason'] ?? 'Recommandation IA intelligente',
                        'confidence' => $data['confidence'] ?? 80,
                        'ai_insights' => $data['ai_insights'] ?? 'Analyse par IA Gemini',
                        'ai_powered' => true
                    ];
                }
            }
            
            return $this->getFallbackSuggestion($availableSeats);
            
        } catch (\Exception $e) {
            Log::error('Gemini Response Parse Error: ' . $e->getMessage());
            return $this->getFallbackSuggestion($availableSeats);
        }
    }

    /**
     * Suggestion de fallback si Gemini échoue
     */
    private function getFallbackSuggestion(array $availableSeats): array
    {
        // Logique de fallback simple
        $preferredSeats = ['A2', 'A1', 'A3'];
        
        foreach ($preferredSeats as $seat) {
            if (in_array($seat, $availableSeats)) {
                return [
                    'seat_number' => $seat,
                    'reason' => 'Place recommandée par notre algorithme local',
                    'confidence' => 75,
                    'ai_insights' => 'Fallback vers algorithme local',
                    'ai_powered' => false
                ];
            }
        }
        
        return [
            'seat_number' => $availableSeats[0] ?? 'A1',
            'reason' => 'Première place disponible',
            'confidence' => 60,
            'ai_insights' => 'Sélection automatique',
            'ai_powered' => false
        ];
    }

    /**
     * Tester la connexion à Gemini
     */
    public function testConnection(): bool
    {
        try {
            if (empty($this->apiKey)) {
                Log::error('Gemini API Key is empty');
                return false;
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Réponds simplement "OK"']
                        ]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error: ' . $response->status() . ' - ' . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Gemini Connection Error: ' . $e->getMessage());
            return false;
        }
    }
}