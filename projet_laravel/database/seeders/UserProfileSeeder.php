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
            'first_name' => 'Admin',
            'last_name' => 'EcoEvents',
            'email' => 'admin@ecoevents.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'phone' => '+33 1 00 00 00 00',
            'birth_date' => '1980-01-01',
            'city' => 'Paris',
            'country' => 'FR',
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

        // Créer un organisateur
        $organizer = User::create([
            'name' => 'Organisateur EcoEvents',
            'first_name' => 'Organisateur',
            'last_name' => 'EcoEvents',
            'email' => 'organizer@ecoevents.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'organizer',
            'phone' => '+33 1 00 00 00 01',
            'birth_date' => '1985-05-15',
            'city' => 'Lyon',
            'country' => 'FR',
        ]);

        Profile::create([
            'user_id' => $organizer->id,
            'first_name' => 'Organisateur',
            'last_name' => 'EcoEvents',
            'bio' => 'Organisateur d\'événements écologiques sur la plateforme EcoEvents. Je me spécialise dans l\'organisation de nettoyages de plages et de forêts.',
            'city' => 'Lyon',
            'country' => 'France',
            'interests' => ['Organisation d\'événements', 'Écologie', 'Développement durable', 'Nettoyage'],
            'notification_preferences' => 'both',
            'is_eco_ambassador' => true,
        ]);

        // Créer un deuxième organisateur
        $organizer2 = User::create([
            'name' => 'Jean Organisateur',
            'first_name' => 'Jean',
            'last_name' => 'Organisateur',
            'email' => 'jean.organisateur@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'organizer',
            'phone' => '+33 6 12 34 56 78',
            'birth_date' => '1990-08-20',
            'city' => 'Marseille',
            'country' => 'FR',
        ]);

        Profile::create([
            'user_id' => $organizer2->id,
            'first_name' => 'Jean',
            'last_name' => 'Organisateur',
            'bio' => 'Organisateur d\'événements de sensibilisation à l\'écologie dans la région marseillaise.',
            'city' => 'Marseille',
            'country' => 'France',
            'interests' => ['Sensibilisation', 'Événements écologiques', 'Animation'],
            'notification_preferences' => 'email',
            'is_eco_ambassador' => true,
        ]);

        // Créer des utilisateurs réguliers de test
        $users = [
            [
                'name' => 'Marie Dupont',
                'first_name' => 'Marie',
                'last_name' => 'Dupont',
                'email' => 'marie.dupont@example.com',
                'phone' => '+33 1 23 45 67 89',
                'birth_date' => '1992-03-15',
                'city' => 'Lyon',
                'country' => 'FR',
                'role' => 'user',
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
                'first_name' => 'Pierre',
                'last_name' => 'Martin',
                'email' => 'pierre.martin@example.com',
                'phone' => '+33 6 78 90 12 34',
                'birth_date' => '1988-07-22',
                'city' => 'Marseille',
                'country' => 'FR',
                'role' => 'user',
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
                'first_name' => 'Sophie',
                'last_name' => 'Leroy',
                'email' => 'sophie.leroy@example.com',
                'phone' => '+33 6 45 67 89 01',
                'birth_date' => '1995-11-30',
                'city' => 'Toulouse',
                'country' => 'FR',
                'role' => 'user',
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
                'first_name' => 'Thomas',
                'last_name' => 'Dubois',
                'email' => 'thomas.dubois@example.com',
                'phone' => '+33 7 89 01 23 45',
                'birth_date' => '1998-04-10',
                'city' => 'Nantes',
                'country' => 'FR',
                'role' => 'user',
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
                'first_name' => 'Emma',
                'last_name' => 'Rousseau',
                'email' => 'emma.rousseau@example.com',
                'phone' => '+33 6 23 45 67 89',
                'birth_date' => '1991-09-05',
                'city' => 'Bordeaux',
                'country' => 'FR',
                'role' => 'user',
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
            ],
            [
                'name' => 'Ahmed Ben Salah',
                'first_name' => 'Ahmed',
                'last_name' => 'Ben Salah',
                'email' => 'ahmed.bensalah@example.com',
                'phone' => '+216 12 345 678',
                'birth_date' => '1987-12-20',
                'city' => 'Tunis',
                'country' => 'TN',
                'role' => 'organizer',
                'profile' => [
                    'first_name' => 'Ahmed',
                    'last_name' => 'Ben Salah',
                    'phone' => '+216 12 345 678',
                    'bio' => 'Organisateur d\'événements écologiques en Tunisie, spécialisé dans la protection du littoral.',
                    'city' => 'Tunis',
                    'country' => 'Tunisie',
                    'interests' => ['Protection marine', 'Écologie côtière', 'Sensibilisation'],
                    'notification_preferences' => 'both',
                    'is_eco_ambassador' => true,
                ]
            ],
            [
                'name' => 'Lina Chen',
                'first_name' => 'Lina',
                'last_name' => 'Chen',
                'email' => 'lina.chen@example.com',
                'phone' => '+1 514 123 4567',
                'birth_date' => '1993-06-14',
                'city' => 'Montréal',
                'country' => 'CA',
                'role' => 'user',
                'profile' => [
                    'first_name' => 'Lina',
                    'last_name' => 'Chen',
                    'bio' => 'Activiste environnementale basée à Montréal, je participe à des initiatives de reboisement.',
                    'city' => 'Montréal',
                    'country' => 'Canada',
                    'interests' => ['Reboisement', 'Protection des forêts', 'Activisme'],
                    'notification_preferences' => 'email',
                    'is_eco_ambassador' => true,
                ]
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => $userData['role'],
                'phone' => $userData['phone'] ?? null,
                'birth_date' => $userData['birth_date'] ?? null,
                'city' => $userData['city'] ?? null,
                'country' => $userData['country'] ?? null,
            ]);

            Profile::create(array_merge($userData['profile'], ['user_id' => $user->id]));
        }

        $this->command->info('Utilisateurs et profils créés avec succès !');
        $this->command->info('Connexion admin: admin@ecoevents.com / password');
        $this->command->info('Connexion organisateur 1: organizer@ecoevents.com / password');
        $this->command->info('Connexion organisateur 2: jean.organisateur@example.com / password');
        $this->command->info('Connexion organisateur 3: ahmed.bensalah@example.com / password');
    }
}