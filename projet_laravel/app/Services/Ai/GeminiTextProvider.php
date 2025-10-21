<?php

namespace App\Services\Ai;

use GuzzleHttp\Client;
use Carbon\Carbon;

class GeminiTextProvider
{
    protected string $apiKey;
    protected string $model;
    protected string $endpoint;

    public function __construct()
    {
        $cfg = config('flyer.text.gemini');
        $this->apiKey = (string) ($cfg['api_key'] ?? '');
        $this->model = (string) ($cfg['model'] ?? 'gemini-2.5-flash');
        $this->endpoint = rtrim((string) ($cfg['endpoint'] ?? ''), '/');
    }

    public function enhance(array $event): array
    {
        if (empty($this->apiKey)) {
            return [
                'tagline' => self::fallbackTagline($event),
                'colors' => ['#15A053', '#0E7A3A', '#73C58E'],
                'date_text' => self::fallbackDateText($event),
                'quote' => self::fallbackQuote($event),
            ];
        }

        $title = $event['title'] ?? '';
        $theme = $event['theme'] ?? '';
        $city = $event['city'] ?? '';
        $date = $event['date'] ?? '';
        $description = $event['description'] ?? '';

        $prompt = "Tu es un assistant marketing francophone. Retourne uniquement un JSON compact avec: \n" .
            "- tagline: une phrase d'accroche de 6 à 8 mots (français)\n" .
            "- colors: un tableau de 3 couleurs hex (primaire, secondaire, accent)\n" .
            "- date_text: une version lisible et élégante de la date (ex: 'Samedi 25 octobre 2025 • 10:00')\n" .
            "- quote: une courte citation inspirante (<= 12 mots), basée sur le titre et la description, en français, sans guillemets décoratifs.\n" .
            "Contexte: titre: {$title}; thème: {$theme}; ville: {$city}; date: {$date}; description: {$description}.\n" .
            "Réponds UNIQUEMENT avec un objet JSON de forme {\"tagline\":\"...\",\"colors\":[\"#...\",\"#...\",\"#...\"],\"date_text\":\"...\",\"quote\":\"...\"}.";

        $client = new Client(['timeout' => 20]);
        $url = $this->endpoint . "/{$this->model}:generateContent?key=" . urlencode($this->apiKey);

        $resp = $client->post($url, [
            'json' => [
                'contents' => [[
                    'parts' => [['text' => $prompt]],
                ]],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 256,
                ],
            ],
        ]);

        $data = json_decode((string) $resp->getBody(), true);
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        // Try parse JSON from the model output
        $parsed = self::extractJson($text);

        return [
            'tagline' => $parsed['tagline'] ?? self::fallbackTagline($event),
            'colors' => $parsed['colors'] ?? ['#15A053', '#0E7A3A', '#73C58E'],
            'date_text' => $parsed['date_text'] ?? self::fallbackDateText($event),
            'quote' => $parsed['quote'] ?? self::fallbackQuote($event),
        ];
    }

    protected static function extractJson(string $text): array
    {
        $text = trim($text);
        $start = strpos($text, '{');
        $end = strrpos($text, '}');
        if ($start === false || $end === false || $end <= $start) return [];
        $json = substr($text, $start, $end - $start + 1);
        $arr = json_decode($json, true);
        return is_array($arr) ? $arr : [];
    }

    protected static function fallbackTagline(array $event): string
    {
        $title = $event['title'] ?? 'Notre événement';
        return "Rejoignez-nous pour $title";
    }

    protected static function fallbackDateText(array $event): string
    {
        $date = $event['date'] ?? '';
        try {
            if (!empty($date)) {
                $c = Carbon::parse($date);
                // French-like readable format
                return $c->translatedFormat('l d F Y • H:i');
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return '';
    }

    protected static function fallbackQuote(array $event): string
    {
        $title = trim((string) ($event['title'] ?? 'notre planète'));
        $theme = trim((string) ($event['theme'] ?? 'l\'environnement'));
        if (!empty($title)) {
            return "Agir ensemble pour $title";
        }
        return "Agir ensemble pour $theme";
    }
}
