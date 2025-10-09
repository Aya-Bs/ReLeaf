<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

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

        // Événement 1 : Conférence sur le climat
        Event::create([
            'title' => 'Conférence : Changement Climatique et Actions Locales',
            'description' => 'Une conférence interactive sur les impacts du changement climatique en Tunisie et les actions que chacun peut entreprendre au niveau local. Avec la participation d\'experts nationaux et internationaux. Durée : 3 heures.',
            'date' => now()->addDays(15)->setTime(14, 0), // Dans 15 jours à 14h
            'location' => 'Centre de Conférences EcoTech - Tunis',
            'max_participants' => 50,
            'user_id' => $admin->id,
            'status' => 'published',
            'image' => 'conference-climat.svg'
        ]);

        // Événement 2 : Atelier pratique
        Event::create([
            'title' => 'Atelier : Création d\'un Jardin Urbain Écologique',
            'description' => 'Apprenez à créer votre propre jardin urbain avec des techniques écologiques. Cet atelier pratique vous donnera toutes les clés pour développer un espace vert chez vous, même dans un petit appartement. Durée : 4 heures.',
            'date' => now()->addDays(22)->setTime(10, 0), // Dans 22 jours à 10h
            'location' => 'Jardin Botanique de Tunis',
            'max_participants' => 25,
            'user_id' => $admin->id,
            'status' => 'published',
            'image' => 'jardin-urbain.svg'
        ]);
        
        echo "✅ Événements de test créés avec succès !\n";
    }
}
