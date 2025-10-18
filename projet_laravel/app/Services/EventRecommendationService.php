<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class EventRecommendationService
{
    private GeminiService $geminiService;
    private string $apiKey;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
        $this->apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
    }

    /**
     * 🤖 Recommander des événements basés sur la géolocalisation avec IA Gemini
     */
    public function getRecommendedEvents(User $user, Collection $allEvents): array
    {
        try {
            // Obtenir la ville de l'utilisateur
            $userCity = $this->getUserCity($user);
            
            if (!$userCity) {
                return $this->getFallbackRecommendations($allEvents);
            }

            // Analyser les événements avec Gemini IA
            $geminiRecommendations = $this->getGeminiRecommendations($user, $userCity, $allEvents);
            
            if ($geminiRecommendations['ai_powered']) {
                return $geminiRecommendations;
            }

            // Fallback vers algorithme local
            return $this->getLocalRecommendations($userCity, $allEvents);

        } catch (\Exception $e) {
            Log::error('Event Recommendation Error: ' . $e->getMessage());
            return $this->getFallbackRecommendations($allEvents);
        }
    }

    /**
     * 🧠 Utiliser Gemini IA pour des recommandations intelligentes
     */
    private function getGeminiRecommendations(User $user, string $userCity, Collection $allEvents): array
    {
        if (empty($this->apiKey)) {
            return ['ai_powered' => false];
        }

        try {
            // Préparer les données pour Gemini
            $eventsData = $this->prepareEventsDataForAI($allEvents);
            $userProfile = $this->getUserProfileForAI($user);
            
            $prompt = $this->buildRecommendationPrompt($userCity, $eventsData, $userProfile);
            
            $response = Http::timeout(20)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1000,
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error for recommendations: ' . $response->status());
                return ['ai_powered' => false];
            }

            $data = $response->json();
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
                return $this->parseGeminiRecommendations($aiResponse, $allEvents);
            }

            return ['ai_powered' => false];

        } catch (\Exception $e) {
            Log::error('Gemini Recommendations Error: ' . $e->getMessage());
            return ['ai_powered' => false];
        }
    }

    /**
     * 📍 Recommandations locales basées sur la géolocalisation
     */
    private function getLocalRecommendations(string $userCity, Collection $allEvents): array
    {
        // Définir les villes proches pour la Tunisie
        $proximityMap = [
            'Tunis' => ['La Marsa', 'Ariana', 'Ben Arous', 'Manouba', 'Carthage', 'Sidi Bou Said'],
            'Sfax' => ['Mahdia', 'Kairouan', 'Skhira'],
            'Sousse' => ['Monastir', 'Mahdia', 'Kairouan', 'Hammamet'],
            'Bizerte' => ['Tunis', 'Ariana', 'Mateur'],
            'Gabès' => ['Médenine', 'Tataouine', 'Tozeur'],
            'Ariana' => ['Tunis', 'La Marsa', 'Ben Arous'],
            'La Marsa' => ['Tunis', 'Ariana', 'Carthage', 'Sidi Bou Said'],
        ];

        $nearbyCities = $proximityMap[$userCity] ?? [];
        $nearbyEvents = collect();
        $distantEvents = collect();

        foreach ($allEvents as $event) {
            if (!$event->location) continue;
            
            $eventCity = $event->location->city;
            
            if ($eventCity === $userCity || in_array($eventCity, $nearbyCities)) {
                $nearbyEvents->push($event);
            } else {
                $distantEvents->push($event);
            }
        }

        // Trier par proximité et date
        $nearbyEvents = $nearbyEvents->sortBy('date');
        $distantEvents = $distantEvents->sortBy('date');

        return [
            'recommended_events' => $nearbyEvents->take(6)->values()->toArray(),
            'other_events' => $distantEvents->take(4)->values()->toArray(),
            'ai_powered' => false,
            'recommendation_reason' => "Événements proches de {$userCity}",
            'user_city' => $userCity,
            'nearby_cities' => $nearbyCities
        ];
    }

    /**
     * 📊 Préparer les données d'événements pour l'IA
     */
    private function prepareEventsDataForAI(Collection $allEvents): array
    {
        return $allEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => substr($event->description, 0, 200),
                'date' => $event->date->format('Y-m-d H:i'),
                'city' => $event->location->city ?? 'Non défini',
                'location_name' => $event->location->name ?? 'Non défini',
                'max_participants' => $event->max_participants,
                'organizer' => $event->user->name ?? 'Anonyme'
            ];
        })->toArray();
    }

    /**
     * 👤 Préparer le profil utilisateur pour l'IA
     */
    private function getUserProfileForAI(User $user): array
    {
        return [
            'city' => $this->getUserCity($user),
            'interests' => $user->profile->interests ?? [],
            'previous_events' => $user->reservations()
                ->with('event.location')
                ->where('status', 'confirmed')
                ->count(),
            'is_eco_ambassador' => $user->profile->is_eco_ambassador ?? false
        ];
    }

    /**
     * 🏗️ Construire le prompt pour Gemini
     */
    private function buildRecommendationPrompt(string $userCity, array $eventsData, array $userProfile): string
    {
        $eventsJson = json_encode($eventsData, JSON_UNESCAPED_UNICODE);
        $profileJson = json_encode($userProfile, JSON_UNESCAPED_UNICODE);

        return "Tu es un assistant IA spécialisé dans la recommandation d'événements écologiques en Tunisie.

MISSION: Recommander les meilleurs événements pour un utilisateur basé sur sa géolocalisation et ses préférences.

PROFIL UTILISATEUR:
{$profileJson}

VILLE UTILISATEUR: {$userCity}

RÈGLES DE PROXIMITÉ GÉOGRAPHIQUE (Tunisie):
- Tunis: proche de La Marsa, Ariana, Ben Arous, Manouba, Carthage, Sidi Bou Said
- Sfax: proche de Mahdia, Kairouan, Skhira  
- Sousse: proche de Monastir, Mahdia, Kairouan, Hammamet
- Bizerte: proche de Tunis, Ariana, Mateur
- Gabès: proche de Médenine, Tataouine, Tozeur
- Ariana: proche de Tunis, La Marsa, Ben Arous
- La Marsa: proche de Tunis, Ariana, Carthage, Sidi Bou Said

ÉVÉNEMENTS DISPONIBLES:
{$eventsJson}

CRITÈRES DE RECOMMANDATION:
1. PRIORITÉ 1: Proximité géographique (même ville ou villes proches)
2. PRIORITÉ 2: Correspondance avec les intérêts utilisateur
3. PRIORITÉ 3: Date proche et disponibilité
4. PRIORITÉ 4: Diversité des types d'événements

RÉPONSE ATTENDUE (JSON uniquement):
{
    \"recommended_events\": [array of event IDs - maximum 6 événements proches],
    \"other_events\": [array of event IDs - maximum 4 événements distants mais intéressants],
    \"recommendation_reason\": \"Explication claire de la logique de recommandation\",
    \"ai_insights\": \"Analyse intelligente des préférences et de la géolocalisation\",
    \"user_city\": \"{$userCity}\",
    \"proximity_score\": \"score de 1-10 basé sur la proximité géographique\"
}

Réponds UNIQUEMENT avec le JSON, sans texte supplémentaire.";
    }

    /**
     * 🔍 Parser les recommandations de Gemini
     */
    private function parseGeminiRecommendations(string $response, Collection $allEvents): array
    {
        try {
            // Nettoyer la réponse
            $cleanResponse = preg_replace('/```json\s*|\s*```/', '', $response);
            $cleanResponse = trim($cleanResponse);
            
            $data = json_decode($cleanResponse, true);
            
            if (json_last_error() === JSON_ERROR_NONE && isset($data['recommended_events'])) {
                // Récupérer les événements recommandés
                $recommendedEvents = $allEvents->whereIn('id', $data['recommended_events'])->values();
                $otherEvents = $allEvents->whereIn('id', $data['other_events'] ?? [])->values();
                
                return [
                    'recommended_events' => $recommendedEvents->toArray(),
                    'other_events' => $otherEvents->toArray(),
                    'ai_powered' => true,
                    'recommendation_reason' => $data['recommendation_reason'] ?? 'Recommandations IA personnalisées',
                    'ai_insights' => $data['ai_insights'] ?? 'Analyse par Gemini IA',
                    'user_city' => $data['user_city'] ?? '',
                    'proximity_score' => $data['proximity_score'] ?? 'N/A'
                ];
            }
            
            return ['ai_powered' => false];
            
        } catch (\Exception $e) {
            Log::error('Gemini Recommendations Parse Error: ' . $e->getMessage());
            return ['ai_powered' => false];
        }
    }

    /**
     * 🏠 Obtenir la ville de l'utilisateur
     */
    private function getUserCity(User $user): ?string
    {
        // Essayer d'abord le profil, puis le champ user direct
        return $user->profile->city ?? $user->city ?? null;
    }

    /**
     * 🔄 Recommandations de fallback
     */
    private function getFallbackRecommendations(Collection $allEvents): array
    {
        $events = $allEvents->sortBy('date')->take(10);
        
        return [
            'recommended_events' => $events->take(6)->values()->toArray(),
            'other_events' => $events->skip(6)->values()->toArray(),
            'ai_powered' => false,
            'recommendation_reason' => 'Événements triés par date (ville utilisateur non définie)',
            'user_city' => null,
            'nearby_cities' => []
        ];
    }

    /**
     * 🧪 Tester la fonctionnalité de recommandations
     */
    public function testRecommendations(User $user): array
    {
        $events = Event::with(['location', 'user'])
            ->whereIn('status', ['published'])
            ->orderBy('date', 'asc')
            ->take(10)
            ->get();
            
        return $this->getRecommendedEvents($user, $events);
    }
}
