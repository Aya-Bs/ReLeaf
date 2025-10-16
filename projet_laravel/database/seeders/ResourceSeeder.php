<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les campagnes existantes
        $campaigns = Campaign::all();

        if ($campaigns->count() < 2) {
            echo "⚠️  Pas assez de campagnes pour créer les ressources supplémentaires.\n";

            return;
        }

        $resources = [
            [
                'name' => 'Matériel de sensibilisation',
                'description' => 'Panneaux informatifs, brochures et affiches pour sensibiliser le public',
                'quantity_needed' => 50,
                'quantity_pledged' => 30,
                'unit' => 'kits',
                'provider' => 'Agence de Communication Verte',
                'status' => 'pledged',
                'resource_type' => 'equipment',
                'category' => 'materiel',
                'priority' => 'medium',
                'notes' => 'Contenu en français et arabe',
                'campaign_id' => $campaigns[0]->id,
            ],
            [
                'name' => 'Transport en commun',
                'description' => 'Navettes gratuites pour transporter les participants aux sites d\'événements',
                'quantity_needed' => 4,
                'quantity_pledged' => 2,
                'unit' => 'bus',
                'provider' => 'Transport Public Tunisien',
                'status' => 'needed',
                'resource_type' => 'other',
                'category' => 'technique',
                'priority' => 'high',
                'notes' => 'Pour les événements dans des lieux éloignés',
                'campaign_id' => $campaigns[1]->id,
            ],
        ];

        foreach ($resources as $resourceData) {
            Resource::create($resourceData);
        }

        echo "✅ 2 ressources supplémentaires créées avec succès !\n";
    }
}
