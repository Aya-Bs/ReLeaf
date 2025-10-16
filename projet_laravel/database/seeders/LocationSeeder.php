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
        ];

        foreach ($locations as $locationData) {
            Location::create($locationData);
        }

        echo "✅ 2 locations créées avec succès !\n";
    }
}
