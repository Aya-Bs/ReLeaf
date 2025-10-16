<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "🌱 Début du seeding de la base de données...\n\n";

        // 1. Créer les utilisateurs de base
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
        ]);

        // 2. Créer les locations
        $this->call([
            LocationSeeder::class,
        ]);

        // 3. Créer les campagnes (qui créent aussi des ressources)
        $this->call([
            CampaignSeeder::class,
        ]);

        // 4. Créer les ressources supplémentaires
        $this->call([
            ResourceSeeder::class,
        ]);

        // 5. Créer les événements (qui utilisent locations et campagnes)
        $this->call([
            EventSeeder::class,
        ]);

        // 6. Créer les sponsors
        $this->call([
            SponsorSeeder::class,
        ]);

        // 7. Créer les donations
        $this->call([
            DonationSeeder::class,
        ]);

        echo "\n🎉 Seeding terminé avec succès !\n";
        echo "📊 Résumé :\n";
        echo "   - 2 locations créées\n";
        echo "   - 2 campagnes créées\n";
        echo "   - 2 événements créés\n";
        echo "   - 4 ressources créées (2 dans CampaignSeeder + 2 dans ResourceSeeder)\n";
        echo "   - 2 donations créées\n";
        echo "   - 2 sponsors créés\n";
        echo "   - Plusieurs utilisateurs créés avec différents rôles\n";
    }
}
