<?php

/**
 * Script pour tester les recommandations IA avec de nouveaux événements
 * Usage: php seed_events_test.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;

// Créer l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🚀 Démarrage du script de test des recommandations IA...\n\n";

try {
    // 1. Exécuter les seeders
    echo "📍 Ajout de nouvelles locations...\n";
    Artisan::call('db:seed', ['--class' => 'LocationSeeder']);
    echo Artisan::output();

    echo "🎉 Ajout de nouveaux événements...\n";
    Artisan::call('db:seed', ['--class' => 'EventSeeder']);
    echo Artisan::output();

    // 2. Afficher un résumé
    echo "\n📊 RÉSUMÉ DES DONNÉES :\n";
    echo "═══════════════════════════════\n";
    
    $locations = \App\Models\Location::all();
    echo "📍 Locations créées (" . $locations->count() . ") :\n";
    foreach ($locations as $location) {
        echo "   • {$location->name} ({$location->city})\n";
    }
    
    echo "\n🎉 Événements créés (" . \App\Models\Event::count() . ") :\n";
    $events = \App\Models\Event::with('location')->get();
    foreach ($events as $event) {
        $city = $event->location->city ?? 'Ville inconnue';
        $date = $event->date->format('d/m/Y');
        echo "   • {$event->title} - {$city} ({$date})\n";
    }

    // 3. Test des recommandations IA
    echo "\n🤖 TEST DES RECOMMANDATIONS IA :\n";
    echo "═══════════════════════════════════\n";
    
    // Créer un utilisateur test de Tunis
    $testUser = \App\Models\User::firstOrCreate(
        ['email' => 'test.tunis@example.com'],
        [
            'name' => 'Utilisateur Test Tunis',
            'city' => 'Tunis',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]
    );
    
    // Créer son profil
    $testUser->profile()->firstOrCreate([
        'city' => 'Tunis',
        'interests' => ['environnement', 'écologie', 'nature']
    ]);
    
    echo "👤 Utilisateur test créé : {$testUser->name} (Ville: {$testUser->city})\n\n";
    
    // Tester les recommandations
    $recommendationService = app(\App\Services\EventRecommendationService::class);
    $allEvents = \App\Models\Event::with(['location', 'user'])->where('status', 'published')->get();
    
    echo "🧠 Test des recommandations pour un utilisateur de Tunis...\n";
    $recommendations = $recommendationService->getRecommendedEvents($testUser, $allEvents);
    
    echo "📋 RÉSULTATS :\n";
    echo "   • IA activée : " . ($recommendations['ai_powered'] ? '✅ OUI (Gemini)' : '❌ NON (Local)') . "\n";
    echo "   • Ville utilisateur : " . ($recommendations['user_city'] ?? 'Non définie') . "\n";
    echo "   • Événements recommandés : " . count($recommendations['recommended_events'] ?? []) . "\n";
    echo "   • Autres événements : " . count($recommendations['other_events'] ?? []) . "\n";
    
    if (!empty($recommendations['recommendation_reason'])) {
        echo "   • Raison : " . $recommendations['recommendation_reason'] . "\n";
    }
    
    if (!empty($recommendations['ai_insights'])) {
        echo "   • Analyse IA : " . $recommendations['ai_insights'] . "\n";
    }

    echo "\n🎯 ÉVÉNEMENTS RECOMMANDÉS (proches de Tunis) :\n";
    foreach ($recommendations['recommended_events'] ?? [] as $event) {
        $city = $event['location']['city'] ?? 'Ville inconnue';
        echo "   ⭐ {$event['title']} - {$city}\n";
    }

    echo "\n🌍 AUTRES ÉVÉNEMENTS (distants) :\n";
    foreach ($recommendations['other_events'] ?? [] as $event) {
        $city = $event['location']['city'] ?? 'Ville inconnue';
        echo "   📍 {$event['title']} - {$city}\n";
    }

    echo "\n✅ SCRIPT TERMINÉ AVEC SUCCÈS !\n";
    echo "🌐 Vous pouvez maintenant tester sur : http://localhost:8000/events\n";
    echo "🧪 Test API direct : http://localhost:8000/test-ai-recommendations\n";

} catch (\Exception $e) {
    echo "❌ ERREUR : " . $e->getMessage() . "\n";
    echo "📍 Fichier : " . $e->getFile() . " (ligne " . $e->getLine() . ")\n";
    exit(1);
}
