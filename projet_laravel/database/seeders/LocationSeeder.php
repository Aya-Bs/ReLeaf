<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Parc de Belvédère',
                'address' => 'Avenue Habib Bourguiba, Tunis, Tunisie',
                'city' => 'Tunis',
                'latitude' => 36.8065,
                'longitude' => 10.1815,
                'capacity' => 200,
                'description' => 'Magnifique parc urbain au cœur de Tunis, parfait pour les événements écologiques et les activités de sensibilisation. Équipements : Parking, Toilettes, Électricité, Eau potable.',
                'images' => ['parc-belvedere.jpg'],
            ],
            [
                'name' => 'Plage de Gammarth',
                'address' => 'Route de Gammarth, La Marsa, Tunisie',
                'city' => 'La Marsa',
                'latitude' => 36.8472,
                'longitude' => 10.3245,
                'capacity' => 150,
                'description' => 'Plage publique idéale pour les campagnes de nettoyage et les activités de sensibilisation marine. Équipements : Parking, Douches, Restaurants à proximité.',
                'images' => ['plage-gammarth.jpg'],
            ],
            [
                'name' => 'Centre Culturel de Sousse',
                'address' => 'Avenue Bourguiba, Sousse, Tunisie',
                'city' => 'Sousse',
                'latitude' => 35.8256,
                'longitude' => 10.6369,
                'capacity' => 120,
                'description' => 'Centre culturel moderne au cœur de Sousse, équipé pour les conférences et ateliers environnementaux. Équipements : Climatisation, Projecteur, Sonorisation, Parking.',
                'images' => ['centre-culturel-sousse.jpg'],
            ],
            [
                'name' => 'Port de Sfax - Espace Écologique',
                'address' => 'Zone Portuaire, Sfax, Tunisie',
                'city' => 'Sfax',
                'latitude' => 34.7406,
                'longitude' => 10.7603,
                'capacity' => 80,
                'description' => 'Espace dédié aux activités écologiques près du port de Sfax, idéal pour les campagnes de sensibilisation marine et les nettoyages côtiers. Équipements : Accès mer, Parking, Toilettes.',
                'images' => ['port-sfax-eco.jpg'],
            ],
            [
                'name' => 'Parc Farhat Hached - Tunis',
                'address' => 'Avenue de la Liberté, Tunis, Tunisie',
                'city' => 'Tunis',
                'latitude' => 36.8019,
                'longitude' => 10.1858,
                'capacity' => 100,
                'description' => 'Parc central de Tunis parfait pour les événements communautaires et les ateliers de jardinage urbain. Équipements : Espaces verts, Bancs, Éclairage, Sécurité.',
                'images' => ['parc-farhat-hached.jpg'],
            ],
            [
                'name' => 'Plage de Sousse Nord',
                'address' => 'Corniche Nord, Sousse, Tunisie',
                'city' => 'Sousse',
                'latitude' => 35.8367,
                'longitude' => 10.6411,
                'capacity' => 200,
                'description' => 'Grande plage de Sousse idéale pour les événements de sensibilisation marine et les nettoyages de plage. Équipements : Accès facile, Parking, Restaurants.',
                'images' => ['plage-sousse-nord.jpg'],
            ],
        ];

        foreach ($locations as $locationData) {
            Location::create($locationData);
        }

        echo "✅ " . count($locations) . " locations créées avec succès !\n";
    }
}
