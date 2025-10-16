<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des utilisateurs avec différents rôles
        $users = [
            [
                'name' => 'Ahmed Ben Ali',
                'first_name' => 'Ahmed',
                'last_name' => 'Ben Ali',
                'email' => 'ahmed.benali@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'phone' => '+216 98 123 456',
                'city' => 'Tunis',
                'country' => 'Tunisie',
            ],
            [
                'name' => 'Fatma Khelil',
                'first_name' => 'Fatma',
                'last_name' => 'Khelil',
                'email' => 'fatma.khelil@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'email_verified_at' => now(),
                'phone' => '+216 92 654 321',
                'city' => 'Sfax',
                'country' => 'Tunisie',
            ],
            [
                'name' => 'EcoTech Solutions',
                'first_name' => 'EcoTech',
                'last_name' => 'Solutions',
                'email' => 'contact@ecotechsolutions.tn',
                'password' => bcrypt('password'),
                'role' => 'sponsor',
                'email_verified_at' => now(),
                'phone' => '+216 71 123 456',
                'city' => 'Tunis',
                'country' => 'Tunisie',
            ],
            [
                'name' => 'Green Future Tunisia',
                'first_name' => 'Green Future',
                'last_name' => 'Tunisia',
                'email' => 'partnerships@greenfuture.tn',
                'password' => bcrypt('password'),
                'role' => 'sponsor',
                'email_verified_at' => now(),
                'phone' => '+216 98 765 432',
                'city' => 'Sfax',
                'country' => 'Tunisie',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        echo "✅ 4 utilisateurs supplémentaires créés avec succès !\n";
    }
}
