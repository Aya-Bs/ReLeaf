<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sponsor;
use App\Models\User;

class SponsorSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer des utilisateurs avec le rôle sponsor
        $sponsorUsers = User::where('role', 'sponsor')->take(2)->get();

        if ($sponsorUsers->count() < 2) {
            echo "⚠️  Pas assez d'utilisateurs sponsor pour créer les sponsors.\n";
            return;
        }

        $sponsors = [
            [
                'user_id' => $sponsorUsers[0]->id,
                'company_name' => 'EcoTech Solutions',
                'contact_email' => 'contact@ecotechsolutions.tn',
                'contact_phone' => '+216 71 123 456',
                'website' => 'https://www.ecotechsolutions.tn',
                'address' => 'Avenue Habib Bourguiba, Tunis',
                'city' => 'Tunis',
                'country' => 'Tunisie',
                'motivation' => 'Nous croyons fermement en la protection de l\'environnement et souhaitons soutenir les initiatives écologiques locales.',
                'sponsorship_type' => 'argent',
                'additional_info' => 'Entreprise spécialisée dans les technologies vertes et les solutions durables.',
                'status' => 'approved',
            ],
            [
                'user_id' => $sponsorUsers[1]->id,
                'company_name' => 'Green Future Tunisia',
                'contact_email' => 'partnerships@greenfuture.tn',
                'contact_phone' => '+216 98 765 432',
                'website' => 'https://www.greenfuture.tn',
                'address' => 'Zone Industrielle, Sfax',
                'city' => 'Sfax',
                'country' => 'Tunisie',
                'motivation' => 'Notre mission est de promouvoir un avenir plus vert pour la Tunisie à travers le soutien aux projets environnementaux.',
                'sponsorship_type' => 'materiel',
                'additional_info' => 'ONG active dans la sensibilisation environnementale et le développement durable.',
                'status' => 'approved',
            ]
        ];

        foreach ($sponsors as $sponsorData) {
            Sponsor::create($sponsorData);
        }

        echo "✅ 2 sponsors créés avec succès !\n";
    }
}
