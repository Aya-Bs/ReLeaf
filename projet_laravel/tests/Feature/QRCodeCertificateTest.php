<?php

namespace Tests\Feature;

use App\Models\Certification;
use App\Models\Event;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\User;
use App\Services\QRCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QRCodeCertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_certificate_generation_with_qr_code(): void
    {
        // Créer les données de test
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $location = Location::factory()->create();
        $event = Event::factory()->create(['location_id' => $location->id]);
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'confirmed'
        ]);

        // Générer le certificat
        $certificate = Certification::generateForReservation($reservation, $admin);

        // Vérifier que le certificat a été créé avec les nouveaux champs
        $this->assertDatabaseHas('certifications', [
            'id' => $certificate->id,
            'reservation_id' => $reservation->id,
            'certificate_code' => $certificate->certificate_code,
        ]);

        // Vérifier que le token de vérification existe
        $this->assertNotNull($certificate->verification_token);
        $this->assertEquals(32, strlen($certificate->verification_token));

        // Vérifier que le QR code a été généré
        $this->assertNotNull($certificate->qr_code_path);
        $this->assertStringContainsString('certificates/qr-codes/', $certificate->qr_code_path);
    }

    public function test_qr_code_service_generation(): void
    {
        Storage::fake('public');

        $qrService = new QRCodeService();
        $verificationUrl = 'https://example.com/verify/test123';
        $certificateId = 'test123';

        $qrPath = $qrService->generateCertificateQR($certificateId, $verificationUrl);

        // Vérifier que le fichier QR code a été créé
        Storage::disk('public')->assertExists($qrPath);

        // Vérifier le nom du fichier
        $this->assertStringContainsString('cert_' . $certificateId . '_qr.svg', $qrPath);
    }

    public function test_certificate_verification_page(): void
    {
        // Créer un certificat avec token
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $location = Location::factory()->create();
        $event = Event::factory()->create(['location_id' => $location->id]);
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'confirmed'
        ]);

        $certificate = Certification::generateForReservation($reservation, $admin);

        // Tester la page de vérification
        $response = $this->get("/certificates/verify/{$certificate->verification_token}");

        $response->assertStatus(200);
        $response->assertViewIs('certificates.verification-success');
        $response->assertViewHas('certificate', $certificate);
    }

    public function test_certificate_verification_invalid_token(): void
    {
        $invalidToken = 'invalid_token_123456789012345678901234567890';

        $response = $this->get("/certificates/verify/{$invalidToken}");

        $response->assertStatus(200);
        $response->assertViewIs('certificates.verification-error');
        $response->assertViewHas('error_code', 'NOT_FOUND');
    }

    public function test_certificate_api_verification(): void
    {
        // Créer un certificat
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $location = Location::factory()->create();
        $event = Event::factory()->create(['location_id' => $location->id]);
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'confirmed'
        ]);

        $certificate = Certification::generateForReservation($reservation, $admin);

        // Tester l'API de vérification
        $response = $this->postJson('/api/certificates/verify', [
            'token' => $certificate->verification_token
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'valid' => true,
            'certificate' => [
                'code' => $certificate->certificate_code,
                'type' => $certificate->type,
                'points' => $certificate->points_earned,
            ]
        ]);
    }

    public function test_certificate_api_verification_invalid_token(): void
    {
        $response = $this->postJson('/api/certificates/verify', [
            'token' => 'invalid_token'
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'valid' => false,
            'error' => 'Certificat non trouvé'
        ]);
    }
}