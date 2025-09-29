<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Resource;
use Carbon\Carbon;

class CampaignSeeder extends Seeder
{
    public function run()
    {
        // Campagne 1: Reforestation
        $campaign1 = Campaign::create([
            'name' => 'Opération Reboisement Urbain',
            'description' => 'Campagne de plantation d\'arbres dans les zones urbaines pour lutter contre la pollution',
            'category' => 'reforestation',
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(60),
            'goal' => 25000,
            'funds_raised' => 18750,
            'participants_count' => 150,
            'environmental_impact' => '500 arbres plantés, réduction estimée de 50 tonnes de CO2/an',
            'image_url' => null,
            'visibility' => true,
            'tags' => ['arbres', 'ville', 'co2', 'verdure'],
            'status' => 'active',
            'organizer_id' => 1
        ]);

        Resource::create([
            'name' => 'Plants d\'arbres',
            'description' => 'Jeunes plants d\'essences locales',
            'quantity_needed' => 500,
            'quantity_pledged' => 350,
            'unit' => 'plants',
            'provider' => 'Pépinière Municipale',
            'status' => 'pledged',
            'resource_type' => 'equipment',
            'category' => 'materiel',
            'priority' => 'high',
            'notes' => 'Essences adaptées au milieu urbain',
            'campaign_id' => $campaign1->id
        ]);

        // Campagne 2: Nettoyage
        $campaign2 = Campaign::create([
            'name' => 'Nettoyage des Plages Méditerranéennes',
            'description' => 'Opération de nettoyage des plages et sensibilisation à la pollution marine',
            'category' => 'nettoyage',
            'start_date' => Carbon::now()->subDays(15),
            'end_date' => Carbon::now()->addDays(45),
            'goal' => 15000,
            'funds_raised' => 8200,
            'participants_count' => 80,
            'environmental_impact' => '2 tonnes de déchets collectés, 5 km de littoral nettoyés',
            'image_url' => null,
            'visibility' => true,
            'tags' => ['plage', 'déchets', 'méditerranée', 'sensation'],
            'status' => 'active',
            'organizer_id' => 1
        ]);

        Resource::create([
            'name' => 'Sacs poubelles biodégradables',
            'description' => 'Sacs de 100L pour la collecte des déchets',
            'quantity_needed' => 200,
            'quantity_pledged' => 200,
            'unit' => 'sacs',
            'provider' => 'Éco-Entreprise SARL',
            'status' => 'received',
            'resource_type' => 'equipment',
            'category' => 'materiel',
            'priority' => 'medium',
            'campaign_id' => $campaign2->id
        ]);

        Resource::create([
            'name' => 'Gants de protection',
            'description' => 'Gants en coton réutilisable',
            'quantity_needed' => 100,
            'quantity_pledged' => 75,
            'unit' => 'paires',
            'provider' => null,
            'status' => 'needed',
            'resource_type' => 'equipment',
            'category' => 'materiel',
            'priority' => 'urgent',
            'campaign_id' => $campaign2->id
        ]);
    }
}