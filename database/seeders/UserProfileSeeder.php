<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        $admin = User::create([
            'name' => 'Admin EcoEvents',
            'email' => 'admin@ecoevents.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'first_name' => 'Admin',
            'last_name' => 'EcoEvents',
            'bio' => 'Administrateur de la plateforme EcoEvents, passionné par l\'écologie et le développement durable.',
            'city' => 'Paris',
            'country' => 'France',
            'interests' => ['Administration', 'Écologie', 'Développement durable'],
            'notification_preferences' => 'email',
            'is_eco_ambassador' => true,
        ]);

        // Créer des utilisateurs de test
        $users = [
            [
                'name' => 'Marie Dupont',
                'email' => 'marie.dupont@example.com',
                'profile' => [
                    'first_name' => 'Marie',
                    'last_name' => 'Dupont',
                    'phone' => '+33 1 23 45 67 89',
                    'bio' => 'Passionnée de jardinage bio et de permaculture. J\'organise régulièrement des ateliers de sensibilisation à l\'écologie.',
                    'city' => 'Lyon',
                    'country' => 'France',
                    'interests' => ['Jardinage bio', 'Permaculture', 'Compostage'],
                    'notification_preferences' => 'both',
                    'is_eco_ambassador' => true,
                ]
            ],
            [
                'name' => 'Pierre Martin',
                'email' => 'pierre.martin@example.com',
                'profile' => [
                    'first_name' => 'Pierre',
                    'last_name' => 'Martin',
                    'phone' => '+33 6 78 90 12 34',
                    'bio' => 'Ingénieur en énergies renouvelables, je milite pour la transition énergétique.',
                    'city' => 'Marseille',
                    'country' => 'France',
                    'interests' => ['Énergies renouvelables', 'Transition énergétique', 'Innovation'],
                    'notification_preferences' => 'email',
                    'is_eco_ambassador' => false,
                ]
            ],
            [
                'name' => 'Sophie Leroy',
                'email' => 'sophie.leroy@example.com',
                'profile' => [
                    'first_name' => 'Sophie',
                    'last_name' => 'Leroy',
                    'bio' => 'Militante écologiste et organisatrice d\'événements de sensibilisation.',
                    'city' => 'Toulouse',
                    'country' => 'France',
                    'interests' => ['Militantisme', 'Sensibilisation', 'Événements écologiques'],
                    'notification_preferences' => 'email',
                    'is_eco_ambassador' => true,
                ]
            ],
            [
                'name' => 'Thomas Dubois',
                'email' => 'thomas.dubois@example.com',
                'profile' => [
                    'first_name' => 'Thomas',
                    'last_name' => 'Dubois',
                    'phone' => '+33 7 89 01 23 45',
                    'bio' => 'Étudiant en développement durable, je participe à de nombreuses initiatives écologiques.',
                    'city' => 'Nantes',
                    'country' => 'France',
                    'interests' => ['Développement durable', 'Recyclage', 'Transport écologique'],
                    'notification_preferences' => 'sms',
                    'is_eco_ambassador' => false,
                ]
            ],
            [
                'name' => 'Emma Rousseau',
                'email' => 'emma.rousseau@example.com',
                'profile' => [
                    'first_name' => 'Emma',
                    'last_name' => 'Rousseau',
                    'bio' => 'Chef cuisinière spécialisée dans la cuisine bio et locale.',
                    'city' => 'Bordeaux',
                    'country' => 'France',
                    'interests' => ['Cuisine bio', 'Alimentation locale', 'Zéro déchet'],
                    'notification_preferences' => 'both',
                    'is_eco_ambassador' => true,
                ]
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            Profile::create(array_merge($userData['profile'], ['user_id' => $user->id]));
        }

        $this->command->info('Utilisateurs et profils créés avec succès !');
        $this->command->info('Connexion admin: admin@ecoevents.com / password');
    }
}
