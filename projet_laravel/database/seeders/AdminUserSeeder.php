<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin s'il n'existe pas déjà
        $admin = User::firstOrCreate(
            ['email' => 'admin@ecoevents.com'],
            [
                'name' => 'Admin EcoEvents',
                'first_name' => 'Admin',
                'last_name' => 'EcoEvents',
                'phone' => '+33 1 23 45 67 89',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer le profil admin s'il n'existe pas
        if (! $admin->profile) {
            Profile::create([
                'user_id' => $admin->id,
                'first_name' => 'Admin',
                'last_name' => 'EcoEvents',
                'phone' => '+33 1 23 45 67 89',
                'bio' => 'Administrateur de la plateforme EcoEvents, passionné par l\'écologie et le développement durable.',
                'city' => 'Paris',
                'country' => 'France',
                'interests' => ['Administration', 'Écologie', 'Développement durable'],
                'notification_preferences' => 'email',
                'is_eco_ambassador' => true,
            ]);
        }

        $this->command->info('Utilisateur admin créé avec succès !');
        $this->command->info('Email: admin@ecoevents.com');
        $this->command->info('Mot de passe: admin123');
    }
}
