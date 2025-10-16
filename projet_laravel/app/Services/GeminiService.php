<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private ?string $apiKey;

    private string $apiUrl;

    public function __construct()
    {
        // Clé API Gemini directement dans le service
        $this->apiKey = config('services.gemini.api_key') ?: 'AIzaSyD8nS-AYpwFJ4SVC0rJxwwrP_auESnW9Cg';
        $this->apiUrl = config('services.gemini.api_url') ?: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
    }

    /**
     * Générer une réponse IA avec Gemini
     */
    public function generateResponse(string $userMessage, string $language = 'fr', array $context = []): string
    {
        if (empty($this->apiKey)) {
            return $this->getFallbackResponse($userMessage, $language);
        }

        try {
            $prompt = $this->buildPrompt($userMessage, $language, $context);

            $response = Http::timeout(30)->post($this->apiUrl.'?key='.$this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
                    ],
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            Log::warning('Gemini API response error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $this->getFallbackResponse($userMessage, $language);

        } catch (\Exception $e) {
            Log::error('Gemini API error: '.$e->getMessage());

            return $this->getFallbackResponse($userMessage, $language);
        }
    }

    /**
     * Construire le prompt pour Gemini
     */
    private function buildPrompt(string $userMessage, string $language, array $context): string
    {
        $languageInstructions = [
            'fr' => 'Réponds en français de manière professionnelle et amicale.',
            'en' => 'Respond in English in a professional and friendly manner.',
            'ar' => 'أجب باللغة العربية بطريقة مهنية وودودة.',
        ];

        $contextInfo = '';
        if (! empty($context)) {
            $contextInfo = "\n\nContexte de la conversation:\n";
            foreach ($context as $key => $value) {
                $contextInfo .= "- {$key}: {$value}\n";
            }
        }

        $languageInstruction = isset($languageInstructions[$language])
            ? $languageInstructions[$language]
            : $languageInstructions['fr'];

        return "Tu es l'assistant IA d'EcoEvents, une plateforme d'événements écologiques. 

{$languageInstruction}

Tu peux aider les utilisateurs avec :
- Informations sur les événements écologiques
- Aide pour les réservations
- Informations sur les certificats
- Support général de la plateforme
- Questions sur l'environnement et l'écologie

Réponds de manière concise, utile et engageante. Utilise des emojis appropriés.
Si tu ne connais pas la réponse, dirige l'utilisateur vers le support humain.

Message de l'utilisateur: {$userMessage}{$contextInfo}

Réponse:";
    }

    /**
     * Réponse de fallback si Gemini n'est pas disponible
     */
    private function getFallbackResponse(string $userMessage, string $language): string
    {
        // Retourner une réponse simple pour forcer le fallback vers le système de règles
        return 'FALLBACK_TO_RULES';
    }

    /**
     * Vérifier si l'API Gemini est configurée
     */
    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Obtenir des suggestions intelligentes basées sur le contexte
     */
    public function getSmartSuggestions(string $language = 'fr', array $context = []): array
    {
        if (! $this->isConfigured()) {
            return $this->getDefaultSuggestions($language);
        }

        try {
            $prompt = "Génère 5 suggestions de questions que pourrait poser un utilisateur d'EcoEvents (plateforme d'événements écologiques) en {$language}. 
            Réponds uniquement avec les suggestions, une par ligne, sans numérotation.";

            $response = Http::timeout(15)->post($this->apiUrl.'?key='.$this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 200,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $suggestions = explode("\n", trim($data['candidates'][0]['content']['parts'][0]['text']));

                    return array_filter(array_map('trim', $suggestions));
                }
            }

        } catch (\Exception $e) {
            Log::error('Gemini suggestions error: '.$e->getMessage());
        }

        return $this->getDefaultSuggestions($language);
    }

    /**
     * Suggestions par défaut
     */
    private function getDefaultSuggestions(string $language): array
    {
        $suggestions = [
            'fr' => [
                'Voir les événements disponibles',
                'Comment réserver un événement ?',
                'Informations sur les certificats',
                'Changer la langue en anglais',
                'Mon profil utilisateur',
            ],
            'en' => [
                'View available events',
                'How to book an event?',
                'Certificate information',
                'Change language to French',
                'My user profile',
            ],
            'ar' => [
                'عرض الأحداث المتاحة',
                'كيفية حجز حدث؟',
                'معلومات الشهادات',
                'تغيير اللغة إلى الفرنسية',
                'ملفي الشخصي',
            ],
        ];

        return isset($suggestions[$language])
            ? $suggestions[$language]
            : $suggestions['fr'];
    }
}
