<?php

namespace App\Console\Commands;

use App\Models\Certification;
use App\Models\Event;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Console\Command;

class TestQRCodeCommand extends Command
{
    protected $signature = 'qr:test';
    protected $description = 'Tester le système QR code pour les certificats';

    public function handle()
    {
        $this->info('🧪 Test du système QR Code pour les certificats...');

        // Créer ou récupérer les données de test
        $user = User::first();
        $admin = User::where('role', 'admin')->first();
        $event = Event::first();

        if (!$user || !$admin || !$event) {
            $this->error('❌ Données manquantes pour le test');
            $this->warn('Assurez-vous d\'avoir des utilisateurs et des événements dans la base de données');
            return;
        }

        // Créer une réservation de test
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'seat_number' => 'A1',
            'status' => 'confirmed',
            'user_name' => $user->name,
            'reserved_at' => now()
        ]);

        $this->info('✅ Réservation créée: ' . $reservation->seat_number);

        // Générer le certificat avec QR code
        $certificate = Certification::generateForReservation($reservation, $admin);

        $this->info('✅ Certificat généré avec QR code:');
        
        $this->table(
            ['Propriété', 'Valeur'],
            [
                ['Code Certificat', $certificate->certificate_code],
                ['Token de Vérification', $certificate->verification_token],
                ['Chemin QR Code', $certificate->qr_code_path],
                ['URL QR Code', $certificate->qr_code_url],
                ['URL de Vérification', $certificate->verification_url],
                ['Points', $certificate->points_earned],
                ['Date d\'Émission', $certificate->date_awarded->format('d/m/Y H:i')]
            ]
        );

        $this->info('🔗 Testez la vérification:');
        $this->line('URL: ' . $certificate->verification_url);
        
        $this->info('📱 Testez l\'API:');
        $this->line('POST /api/certificates/verify');
        $this->line('Body: {"token": "' . $certificate->verification_token . '"}');

        $this->info('✅ Système QR Code fonctionnel !');
    }
}