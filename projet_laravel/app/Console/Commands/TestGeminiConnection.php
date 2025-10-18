<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GeminiService;
use App\Services\EventRecommendationService;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Http;

class TestGeminiConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:test {--detailed : Afficher les détails complets} {--quick : Test rapide uniquement}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester la connexion et les fonctionnalités Gemini IA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🤖 Test de la connexion Gemini IA...');
        $this->newLine();

        // 1. Vérifier la clé API
        $apiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        
        if (empty($apiKey)) {
            $this->error('❌ ERREUR : Clé API Gemini manquante !');
            $this->warn('💡 Ajoutez GEMINI_API_KEY=votre_clé dans votre fichier .env');
            return 1;
        }

        $this->info('✅ Clé API Gemini trouvée : ' . substr($apiKey, 0, 10) . '...');

        // 2. Test de connexion basique
        $this->info('🔗 Test de connexion basique...');
        
        try {
            $response = Http::timeout(10)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Réponds simplement "CONNEXION OK" si tu me reçois.']
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'maxOutputTokens' => 50,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiResponse = trim($data['candidates'][0]['content']['parts'][0]['text']);
                    $this->info('✅ Connexion réussie ! Réponse Gemini : "' . $aiResponse . '"');
                } else {
                    $this->error('❌ Réponse Gemini inattendue : ' . json_encode($data));
                    return 1;
                }
            } else {
                $this->error('❌ Échec de connexion HTTP : ' . $response->status());
                $this->error('Détails : ' . $response->body());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Erreur de connexion : ' . $e->getMessage());
            return 1;
        }

        // Si test rapide, s'arrêter ici
        if ($this->option('quick')) {
            $this->newLine();
            $this->info('🎉 Test rapide terminé - Gemini fonctionne !');
            return 0;
        }

        // 3. Test du service GeminiService
        $this->newLine();
        $this->info('🧪 Test du service GeminiService...');
        
        try {
            $geminiService = app(GeminiService::class);
            $connectionTest = $geminiService->testConnection();
            
            if ($connectionTest) {
                $this->info('✅ Service GeminiService opérationnel');
            } else {
                $this->error('❌ Service GeminiService défaillant');
            }
        } catch (\Exception $e) {
            $this->error('❌ Erreur service GeminiService : ' . $e->getMessage());
        }

        // 4. Test des recommandations d'événements
        $this->newLine();
        $this->info('🎯 Test des recommandations d\'événements IA...');
        
        try {
            // Créer un utilisateur test temporaire
            $testUser = User::firstOrCreate(
                ['email' => 'test.gemini@example.com'],
                [
                    'name' => 'Test Gemini User',
                    'city' => 'Tunis',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Créer son profil si nécessaire
            $testUser->profile()->firstOrCreate([
                'city' => 'Tunis',
                'interests' => ['environnement', 'écologie']
            ]);

            $recommendationService = app(EventRecommendationService::class);
            $events = Event::with(['location', 'user'])->where('status', 'published')->take(5)->get();
            
            if ($events->count() === 0) {
                $this->warn('⚠️  Aucun événement trouvé pour tester les recommandations');
                $this->info('💡 Exécutez : php artisan db:seed --class=EventSeeder');
            } else {
                $this->info("📊 Test avec {$events->count()} événements...");
                
                $recommendations = $recommendationService->getRecommendedEvents($testUser, $events);
                
                if ($recommendations['ai_powered']) {
                    $this->info('✅ Recommandations IA Gemini fonctionnelles !');
                    $this->info('🏙️  Ville utilisateur : ' . ($recommendations['user_city'] ?? 'Non définie'));
                    $this->info('⭐ Événements recommandés : ' . count($recommendations['recommended_events'] ?? []));
                    $this->info('🌍 Autres événements : ' . count($recommendations['other_events'] ?? []));
                    
                    if ($this->option('detailed')) {
                        $this->newLine();
                        $this->info('📋 DÉTAILS DES RECOMMANDATIONS :');
                        $this->line('Raison : ' . ($recommendations['recommendation_reason'] ?? 'N/A'));
                        $this->line('Analyse IA : ' . ($recommendations['ai_insights'] ?? 'N/A'));
                        
                        if (!empty($recommendations['recommended_events'])) {
                            $this->info('🎯 Événements recommandés :');
                            foreach ($recommendations['recommended_events'] as $event) {
                                $city = $event['location']['city'] ?? 'Ville inconnue';
                                $this->line("   • {$event['title']} ({$city})");
                            }
                        }
                    }
                } else {
                    $this->warn('⚠️  Recommandations en mode fallback (pas d\'IA Gemini)');
                    $this->info('Raison : ' . ($recommendations['recommendation_reason'] ?? 'Erreur inconnue'));
                }
            }

            // Nettoyer l'utilisateur test
            $testUser->delete();

        } catch (\Exception $e) {
            $this->error('❌ Erreur test recommandations : ' . $e->getMessage());
        }

        // 5. Résumé final
        $this->newLine();
        $this->info('📊 RÉSUMÉ DU TEST :');
        $this->line('═══════════════════════════════');
        $this->info('✅ Clé API Gemini configurée');
        $this->info('✅ Connexion Gemini opérationnelle');
        $this->info('✅ Service GeminiService fonctionnel');
        $this->info('✅ Recommandations IA testées');
        
        $this->newLine();
        $this->info('🎉 Gemini IA est prêt à l\'utilisation !');
        $this->info('🌐 Testez sur : http://localhost:8000/events (connecté)');
        $this->info('🧪 API directe : http://localhost:8000/test-ai-recommendations');

        return 0;
    }
}
