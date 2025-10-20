<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Event;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer un utilisateur admin pour être l'organisateur
        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            $admin = User::create([
                'name' => 'Admin Organisateur',
                'first_name' => 'Admin',
                'last_name' => 'Organisateur',
                'email' => 'admin.event@ecoevents.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Récupérer les locations et campagnes
        $locations = Location::all();
        $campaigns = Campaign::all();

        // Événement 1 : Conférence sur le climat
        Event::create([
            'title' => 'Conférence : Changement Climatique et Actions Locales',
            'description' => 'Une conférence interactive sur les impacts du changement climatique en Tunisie et les actions que chacun peut entreprendre au niveau local. Avec la participation d\'experts nationaux et internationaux. Durée : 3 heures.',
            'date' => now()->addDays(15)->setTime(14, 0),
            'location_id' => $locations->first()->id ?? 1,
            'max_participants' => 50,
            'user_id' => $admin->id,
            'status' => 'published',
            'duration' => '3 heures',
            'campaign_id' => $campaigns->first()->id ?? null,
            'images' => ['conference-climat.svg'],
        ]);

        // Événement 2 : Atelier pratique
        Event::create([
            'title' => 'Atelier : Création d\'un Jardin Urbain Écologique',
            'description' => 'Apprenez à créer votre propre jardin urbain avec des techniques écologiques. Cet atelier pratique vous donnera toutes les clés pour développer un espace vert chez vous, même dans un petit appartement. Durée : 4 heures.',
            'date' => now()->addDays(22)->setTime(10, 0),
            'location_id' => $locations->count() > 1 ? $locations[1]->id : $locations->first()->id,
            'max_participants' => 25,
            'user_id' => $admin->id,
            'status' => 'published',
            'duration' => '4 heures',
            'campaign_id' => $campaigns->count() > 1 ? $campaigns[1]->id : null,
            'images' => ['jardin-urbain.svg'],
        ]);

        // 🌍 Événement 3 : Nettoyage de plage à Sousse
        $sousseLocation = $locations->where('city', 'Sousse')->first();
        if ($sousseLocation) {
            Event::create([
                'title' => 'Nettoyage Collectif de la Plage de Sousse',
                'description' => 'Rejoignez-nous pour une action citoyenne de nettoyage de la plage de Sousse Nord. Ensemble, protégeons notre littoral méditerranéen ! Matériel fourni : gants, sacs, pinces. Collation offerte après l\'activité.',
                'date' => now()->addDays(8)->setTime(8, 30),
                'location_id' => $sousseLocation->id,
                'max_participants' => 60,
                'user_id' => $admin->id,
                'status' => 'published',
                'duration' => '3 heures',
                'campaign_id' => $campaigns->first()->id ?? null,
                'images' => ['nettoyage-plage.jpg'],
            ]);
        }

        // 🌱 Événement 4 : Conférence écologique à Sfax
        $sfaxLocation = $locations->where('city', 'Sfax')->first();
        if ($sfaxLocation) {
            Event::create([
                'title' => 'Conférence : Économie Circulaire et Développement Durable',
                'description' => 'Découvrez les principes de l\'économie circulaire et comment les appliquer dans votre entreprise ou votre quotidien. Conférence animée par des experts tunisiens et européens. Networking et échanges prévus.',
                'date' => now()->addDays(18)->setTime(14, 30),
                'location_id' => $sfaxLocation->id,
                'max_participants' => 40,
                'user_id' => $admin->id,
                'status' => 'published',
                'duration' => '2.5 heures',
                'campaign_id' => $campaigns->count() > 1 ? $campaigns[1]->id : null,
                'images' => ['economie-circulaire.jpg'],
            ]);
        }

        // 🌳 Événement 5 : Plantation d'arbres à Tunis
        $tunisLocation = $locations->where('city', 'Tunis')->skip(1)->first(); // Prendre le 2ème lieu de Tunis
        if ($tunisLocation) {
            Event::create([
                'title' => 'Plantation d\'Arbres au Parc Farhat Hached',
                'description' => 'Participez à une action de reboisement urbain dans le parc Farhat Hached. Nous planterons des oliviers, des cyprès et des arbres fruitiers. Une belle façon de contribuer à l\'amélioration de la qualité de l\'air à Tunis !',
                'date' => now()->addDays(12)->setTime(9, 0),
                'location_id' => $tunisLocation->id,
                'max_participants' => 35,
                'user_id' => $admin->id,
                'status' => 'published',
                'duration' => '4 heures',
                'campaign_id' => $campaigns->first()->id ?? null,
                'images' => ['plantation-arbres.jpg'],
            ]);
        }

        // 🐟 Événement 6 : Sensibilisation marine à Sousse
        $sousseLocation2 = $locations->where('city', 'Sousse')->first();
        if ($sousseLocation2) {
            Event::create([
                'title' => 'Atelier : Protection de la Biodiversité Marine',
                'description' => 'Atelier éducatif sur la protection des écosystèmes marins de la Méditerranée. Découverte des espèces locales, menaces environnementales et gestes de protection. Sortie en mer prévue (selon météo).',
                'date' => now()->addDays(25)->setTime(15, 0),
                'location_id' => $sousseLocation2->id,
                'max_participants' => 30,
                'user_id' => $admin->id,
                'status' => 'published',
                'duration' => '3.5 heures',
                'campaign_id' => $campaigns->count() > 1 ? $campaigns[1]->id : null,
                'images' => ['biodiversite-marine.jpg'],
            ]);
        }

        echo "✅ 6 événements créés avec succès dans différentes villes (Tunis, Sousse, Sfax) !\n";
    }
}
