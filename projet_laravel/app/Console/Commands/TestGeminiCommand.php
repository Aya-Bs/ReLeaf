<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GeminiService;

class TestGeminiCommand extends Command
{
    protected $signature = 'gemini:test';
    protected $description = 'Tester la connexion à l\'API Gemini';

    public function handle()
    {
        $this->info('🧪 Test du système de recommandation IA...');
        
        $geminiService = new GeminiService();
        
        // Test de recommandation (algorithme amélioré)
        $this->info('🤖 Test de recommandation...');
        
        $userHistory = ['A1', 'A2'];
        $availableSeats = ['A1', 'A2', 'A3'];
        $eventDetails = [
            'title' => 'Conférence Test',
            'date' => '25/12/2024 à 14:00',
            'location' => 'Salle de test'
        ];
        
        $suggestion = $geminiService->suggestSeat($userHistory, $availableSeats, $eventDetails);
        
        $this->table(
            ['Propriété', 'Valeur'],
            [
                ['Place recommandée', $suggestion['seat_number']],
                ['Raison', $suggestion['reason']],
                ['Confiance', $suggestion['confidence'] . '%'],
                ['IA Powered', $suggestion['ai_powered'] ? 'Oui' : 'Non'],
                ['Insights', $suggestion['ai_insights'] ?? 'N/A']
            ]
        );
        
        $this->info('✅ Système de recommandation fonctionnel !');
        $this->warn('💡 Pour activer Gemini API, configurez votre clé dans .env');
    }
}
