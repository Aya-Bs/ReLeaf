<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CarbonCalculatorService
{
    private $carbonInterfaceApiKey;
    
    public function __construct()
    {
        $this->carbonInterfaceApiKey = config('services.carbon_interface.api_key');
    }
    
    /**
     * Calcule l'empreinte carbone - Version simplifiée et robuste
     */
    public function calculateResourceFootprint($resource, $distance = null)
    {
        Log::info("Calcul d'empreinte carbone pour la ressource: {$resource->id} - {$resource->name}");
        
        // Essayer Carbon Interface d'abord si la clé API est configurée
        if ($this->carbonInterfaceApiKey && $this->isApiReachable()) {
            try {
                $result = $this->calculateWithCarbonInterface($resource, $distance);
                if ($result > 0) {
                    Log::info('✅ Calcul réussi avec Carbon Interface: ' . $result . ' kg CO2e');
                    return $result;
                }
            } catch (\Exception $e) {
                Log::warning('❌ Carbon Interface échoué: ' . $e->getMessage());
            }
        }
        
        // Fallback ADEME (toujours fonctionnel)
        Log::info('🔄 Utilisation du fallback ADEME');
        return $this->calculateWithFallback($resource, $distance);
    }
    
    /**
     * Vérifie si l'API est accessible
     */
    private function isApiReachable()
    {
        try {
            $response = Http::timeout(5)
                ->withOptions(['verify' => false])
                ->get('https://www.carboninterface.com/api/v1/estimates');
                
            return $response->status() !== 0; // 0 signifie généralement DNS/connection failed
        } catch (\Exception $e) {
            Log::warning('Carbon Interface non accessible: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * CALCUL AVEC CARBON INTERFACE - Version robuste
     */
    public function calculateWithCarbonInterface($resource, $distance = null)
    {
        $cacheKey = "carbon_interface_{$resource->id}_{$resource->quantity_needed}";
        
        return Cache::remember($cacheKey, 86400, function() use ($resource, $distance) {
            $estimateConfig = $this->getCarbonInterfaceEstimateConfig($resource->resource_type);
            
            $requestData = [
                'type' => $estimateConfig['type'],
                $estimateConfig['value_field'] => $resource->quantity_needed,
                $estimateConfig['unit_field'] => $estimateConfig['unit'],
                'country' => 'fr'
            ];

            // Ajouter la distance si fournie (pour le transport)
            if ($distance && $resource->resource_type === 'human') {
                $requestData['distance_value'] = $distance;
                $requestData['distance_unit'] = 'km';
            }

            $response = Http::timeout(10)
                ->withOptions([
                    'verify' => false, // Désactive la vérification SSL pour plus de robustesse
                    'debug' => false,
                ])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->carbonInterfaceApiKey,
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'ReleafApp/1.0'
                ])
                ->retry(2, 100) // 2 tentatives
                ->post('https://www.carboninterface.com/api/v1/estimates', $requestData);

            if ($response->successful()) {
                $data = $response->json();
                Log::debug('✅ Carbon Interface API Response', ['data' => $data]);
                
                $carbonKg = $data['data']['attributes']['carbon_kg'] ?? 0;
                
                // Appliquer les bonus écologiques même pour Carbon Interface
                return $this->applyEcologicalBonuses($carbonKg, $resource);
            }
            
            throw new \Exception('Carbon Interface API error: ' . $response->status() . ' - ' . $response->body());
        });
    }
    
    /**
     * Configuration Carbon Interface simplifiée
     */
    private function getCarbonInterfaceEstimateConfig($resourceType)
    {
        $configs = [
            'money' => [
                'type' => 'commercial_financial_estimate',
                'value_field' => 'money_usd',
                'unit_field' => 'money_unit',
                'unit' => 'usd'
            ],
            'food' => [
                'type' => 'food_estimate',
                'value_field' => 'weight_value',
                'unit_field' => 'weight_unit',
                'unit' => 'kg'
            ],
            'clothing' => [
                'type' => 'textiles_estimate',
                'value_field' => 'weight_value',
                'unit_field' => 'weight_unit',
                'unit' => 'kg'
            ],
            'medical' => [
                'type' => 'healthcare_estimate',
                'value_field' => 'weight_value',
                'unit_field' => 'weight_unit',
                'unit' => 'kg'
            ],
            'equipment' => [
                'type' => 'industrial_processes_estimate',
                'value_field' => 'weight_value',
                'unit_field' => 'weight_unit',
                'unit' => 'kg'
            ],
            'human' => [
                'type' => 'vehicle_estimate',
                'value_field' => 'distance_value',
                'unit_field' => 'distance_unit',
                'unit' => 'km'
            ]
        ];
        
        return $configs[$resourceType] ?? [
            'type' => 'electricity',
            'value_field' => 'electricity_value',
            'unit_field' => 'electricity_unit',
            'unit' => 'kwh'
        ];
    }
    
    /**
     * Fallback avec données ADEME - Version améliorée et fiable
     */
    public function calculateWithFallback($resource, $distance = null)
    {
        Log::info("🔧 Calcul ADEME fallback pour: {$resource->resource_type}");

        // Facteurs ADEME basés sur les données françaises
        $ademeFactors = [
            'money' => ['base' => 0.0005, 'unit' => 'euro'], // 0.5g CO2e par euro
            'food' => ['base' => 2.3, 'unit' => 'kg'], // 2.3kg CO2e par kg de nourriture
            'clothing' => ['base' => 7.5, 'unit' => 'piece'], // 7.5kg CO2e par vêtement
            'medical' => ['base' => 2.8, 'unit' => 'unit'], // 2.8kg CO2e par unité médicale
            'equipment' => ['base' => 12.0, 'unit' => 'unit'], // 12kg CO2e par équipement
            'human' => ['base' => 0.15, 'unit' => 'hour'], // 150g CO2e par heure de travail
            'other' => ['base' => 1.2, 'unit' => 'unit'] // 1.2kg CO2e par unité
        ];
        
        $factor = $ademeFactors[$resource->resource_type] ?? $ademeFactors['other'];
        $baseEmission = $factor['base'] * $resource->quantity_needed;
        
        // Ajouter l'impact du transport si distance fournie
        if ($distance) {
            $transportEmission = $distance * 0.21; // 210g CO2e/km (moyenne véhicule utilitaire)
            $baseEmission += $transportEmission;
            Log::debug("Transport ajouté: {$distance}km = {$transportEmission}kg CO2e");
        }
        
        // Appliquer les bonus écologiques
        $finalEmission = $this->applyEcologicalBonuses($baseEmission, $resource);
        
        Log::info("📊 Calcul ADEME: {$baseEmission}kg × bonus = {$finalEmission}kg CO2e");
        
        return round(max(0.01, $finalEmission), 2); // Minimum 0.01 kg
    }
    
    /**
     * Applique les bonus écologiques
     */
    private function applyEcologicalBonuses($baseEmission, $resource)
    {
        $reduction = 1.0;
        $bonusApplied = [];
        
        // Bonus pour fournisseur local
        if ($this->isLocalProvider($resource->provider)) {
            $reduction *= 0.7; // -30%
            $bonusApplied[] = 'local (-30%)';
        }
        
        // Bonus pour matériel réutilisé
        if ($this->isReusedMaterial($resource)) {
            $reduction *= 0.5; // -50%
            $bonusApplied[] = 'réutilisation (-50%)';
        }
        
        // Bonus pour produit écologique
        if ($this->isEcoFriendly($resource)) {
            $reduction *= 0.8; // -20%
            $bonusApplied[] = 'écologique (-20%)';
        }
        
        $finalEmission = $baseEmission * $reduction;
        
        if (!empty($bonusApplied)) {
            Log::info("🎯 Bonus appliqués: " . implode(', ', $bonusApplied) . " | Réduction: " . round((1-$reduction)*100) . "%");
        }
        
        return $finalEmission;
    }
    
    /**
     * Vérifie si le fournisseur est local
     */
    public function isLocalProvider($provider)
    {
        if (!$provider) return false;
        
        $localKeywords = ['local', 'localement', 'régional', 'région', 'proximité', 'artisan', 'producteur local'];
        $providerLower = strtolower($provider);
        
        foreach ($localKeywords as $keyword) {
            if (str_contains($providerLower, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Vérifie si le matériel est réutilisé
     */
    public function isReusedMaterial($resource)
    {
        $reuseKeywords = ['occasion', 'reconditionné', 'réutilisé', 'seconde main', 'recyclé', 'récupération'];
        $text = strtolower(($resource->description ?? '') . ' ' . ($resource->name ?? ''));
        
        foreach ($reuseKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Vérifie si la ressource est écologique
     */
    public function isEcoFriendly($resource)
    {
        $ecoKeywords = ['bio', 'biologique', 'écologique', 'durable', 'renouvelable', 'naturel', 'écoresponsable'];
        $text = strtolower(($resource->description ?? '') . ' ' . ($resource->name ?? ''));
        
        foreach ($ecoKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Test des connexions API - Version simplifiée
     */
    public function testApiConnections()
    {
        $results = [];
        
        // Test Carbon Interface uniquement
        try {
            if (!$this->carbonInterfaceApiKey) {
                $results['carbon_interface'] = 'CLÉ API MANQUANTE';
            } else {
                $response = Http::timeout(8)
                    ->withOptions(['verify' => false])
                    ->withHeaders(['Authorization' => 'Bearer ' . $this->carbonInterfaceApiKey])
                    ->get('https://www.carboninterface.com/api/v1/estimates');
                    
                $results['carbon_interface'] = $response->successful() ? '✅ CONNECTÉ' : '❌ HTTP ' . $response->status();
            }
        } catch (\Exception $e) {
            $results['carbon_interface'] = '❌ ERREUR: ' . $e->getMessage();
        }
        
        // Statut ADEME (toujours disponible)
        $results['ademe_fallback'] = '✅ TOUJOURS DISPONIBLE';
        
        return $results;
    }
    
    /**
     * Obtient les statistiques d'utilisation
     */
    public function getUsageStatistics()
    {
        return [
            'primary_method' => 'carbon_interface',
            'fallback_method' => 'ademe',
            'carbon_interface_configured' => !empty($this->carbonInterfaceApiKey),
            'ademe_ready' => true,
            'api_reachable' => $this->isApiReachable()
        ];
    }
}