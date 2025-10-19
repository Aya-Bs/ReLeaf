<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CarbonCalculatorService;

class TestCarbonApis extends Command
{
    protected $signature = 'carbon:test {--detailed}';
    protected $description = 'Test les connexions aux APIs carbone';

    public function handle()
    {
        $calculator = new CarbonCalculatorService();
        
        $this->info('🔍 Test des APIs Carbone...');
        $this->line('');
        
        // Vérifier la configuration
        $this->info('📋 Configuration:');
        $this->line(' - Carbon Interface API Key: ' . (config('services.carbon_interface.api_key') ? '✅ Définie' : '❌ Manquante'));
        $this->line(' - Méthode principale: Carbon Interface');
        $this->line(' - Méthode fallback: ADEME');
        $this->line('');
        
        // Test des connexions
        $this->info('🌐 Test de connectivité:');
        $results = $calculator->testApiConnections();
        
        foreach ($results as $api => $status) {
            $this->line(" {$api}: {$status}");
        }
        
        $this->line('');
        
        // Test de calcul détaillé
        $this->info('🧪 Test de calcul:');
        
        $mockResource = (object) [
            'id' => 1,
            'resource_type' => 'food',
            'quantity_needed' => 10,
            'unit' => 'kg',
            'provider' => 'local',
            'description' => 'Produits locaux bio',
            'name' => 'Légumes bio'
        ];
        
        try {
            $result = $calculator->calculateResourceFootprint($mockResource);
            $method = config('services.carbon_interface.api_key') ? 'Carbon Interface' : 'ADEME';
            $this->line(" ✅ Calcul réussi: {$result} kg CO2e");
            $this->line(" 📝 Méthode: {$method}");
            
            if ($this->option('detailed')) {
                $this->line('');
                $this->info('📊 Détails du calcul:');
                $this->line(" - Type: {$mockResource->resource_type}");
                $this->line(" - Quantité: {$mockResource->quantity_needed} {$mockResource->unit}");
                $this->line(" - Fournisseur: {$mockResource->provider}");
                $this->line(" - Local: " . ($calculator->isLocalProvider($mockResource->provider) ? '✅ Oui' : '❌ Non'));
                $this->line(" - Écologique: " . ($calculator->isEcoFriendly($mockResource) ? '✅ Oui' : '❌ Non'));
            }
            
        } catch (\Exception $e) {
            $this->error(" 💥 Erreur de calcul: " . $e->getMessage());
        }
        
        $this->line('');
        
        // Statistiques
        $stats = $calculator->getUsageStatistics();
        $this->info('📈 Statistiques:');
        $this->line(" - Méthode principale: {$stats['primary_method']}");
        $this->line(" - Méthode fallback: {$stats['fallback_method']}");
        $this->line(" - Carbon Interface configuré: " . ($stats['carbon_interface_configured'] ? '✅' : '❌'));
        $this->line(" - ADEME disponible: " . ($stats['ademe_ready'] ? '✅' : '❌'));
        $this->line(" - API accessible: " . ($stats['api_reachable'] ? '✅' : '❌'));
        
        $this->line('');
        $this->info('💡 Recommandations:');
        
        if (!$stats['carbon_interface_configured']) {
            $this->line(' 🔸 Carbon Interface non configuré - Utilisation exclusive ADEME');
            $this->line(' 🔸 Les calculs utilisent les données ADEME (gratuit et fiable)');
        } elseif (!$stats['api_reachable']) {
            $this->line(' 🔸 Carbon Interface hors ligne - Fallback ADEME activé');
            $this->line(' 🔸 Vérifiez votre connexion Internet');
        } else {
            $this->line(' 🔸 Carbon Interface fonctionnel');
            $this->line(' 🔸 Fallback ADEME prêt en cas de besoin');
        }
        
        $this->line('');
        $this->info('🎯 Système carbone optimisé et fiable!');
    }
}