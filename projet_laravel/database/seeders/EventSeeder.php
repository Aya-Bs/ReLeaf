<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get some users to be event organizers
        $users = User::where('role', 'user')->limit(5)->get();

        if ($users->isEmpty()) {
            // Create a dummy user if no users exist
            $users = User::factory()->count(5)->create();
        }

        $events = [
            [
                'title' => 'Nettoyage de la Plage de la Ville',
                'description' => 'Rejoignez-nous pour une journée de nettoyage de notre belle plage. Ensemble, nous pouvons faire la différence !',
                'date' => now()->addDays(10),
                'location' => 'Plage Centrale',
                'status' => 'published',
            ],
            [
                'title' => 'Atelier de Recyclage Créatif',
                'description' => 'Apprenez à transformer vos déchets en œuvres d\'art. Un atelier amusant et éducatif pour tous les âges.',
                'date' => now()->addDays(20),
                'location' => 'Maison de la Culture',
                'status' => 'published',
            ],
            [
                'title' => 'Randonnée Écologique en Forêt',
                'description' => 'Découvrez la faune et la flore locales lors d\'une randonnée guidée. Apprenez l\'importance de la conservation de nos forêts.',
                'date' => now()->addDays(30),
                'location' => 'Forêt Nationale',
                'status' => 'published',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create(array_merge($eventData, ['user_id' => $users->random()->id]));
        }
    }
}
