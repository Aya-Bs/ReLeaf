<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer des utilisateurs et campagnes existants
        $users = User::where('role', 'user')->take(2)->get();
        $campaigns = Campaign::take(2)->get();

        if ($users->count() < 2 || $campaigns->count() < 2) {
            echo "⚠️  Pas assez d'utilisateurs ou de campagnes pour créer les donations.\n";

            return;
        }

        $donations = [
            [
                'user_id' => $users[0]->id,
                'campaign_id' => $campaigns[0]->id,
                'amount' => 150.00,
                'currency' => 'TND',
                'donation_type' => 'argent',
                'message' => 'Merci pour cette belle initiative ! J\'espère que cela aidera à protéger notre environnement.',
                'status' => 'completed',
                'payment_method' => 'carte_bancaire',
                'anonymous' => false,
            ],
            [
                'user_id' => $users[1]->id,
                'campaign_id' => $campaigns[1]->id,
                'amount' => 75.50,
                'currency' => 'TND',
                'donation_type' => 'argent',
                'message' => 'Félicitations pour ce projet ! C\'est important de prendre soin de notre planète.',
                'status' => 'completed',
                'payment_method' => 'especes',
                'anonymous' => true,
            ],
        ];

        foreach ($donations as $donationData) {
            Donation::create($donationData);
        }

        echo "✅ 2 donations créées avec succès !\n";
    }
}
