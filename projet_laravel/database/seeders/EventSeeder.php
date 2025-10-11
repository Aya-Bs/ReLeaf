<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Location;
use App\Models\Campaign;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer un utilisateur admin pour être l'organisateur
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
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
            'location' => $locations->first()->name ?? 'Parc de Belvédère, Tunis',
            'max_participants' => 50,
            'user_id' => $admin->id,
            'status' => 'published',
            'duration' => '3 heures',
            'campaign_id' => $campaigns->first()->id ?? null,
            'images' => ['conference-climat.svg']
        ]);

        // Événement 2 : Atelier pratique
        Event::create([
            'title' => 'Atelier : Création d\'un Jardin Urbain Écologique',
            'description' => 'Apprenez à créer votre propre jardin urbain avec des techniques écologiques. Cet atelier pratique vous donnera toutes les clés pour développer un espace vert chez vous, même dans un petit appartement. Durée : 4 heures.',
            'date' => now()->addDays(22)->setTime(10, 0),
            'location' => $locations->count() > 1 ? $locations[1]->name : ($locations->first()->name ?? 'Plage de Gammarth, La Marsa'),
            'max_participants' => 25,
            'user_id' => $admin->id,
            'status' => 'published',
            'duration' => '4 heures',
            'campaign_id' => $campaigns->count() > 1 ? $campaigns[1]->id : null,
            'images' => ['jardin-urbain.svg']
        ]);
        
        echo "✅ 2 événements créés avec succès !\n";
    }
}
