<?php

namespace App\Http\Controllers;

use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificateVerificationController extends Controller
{
    /**
     * Vérifier un certificat via QR code
     */
    public function verify(string $token): View
    {
        $certificate = Certification::findByVerificationToken($token);

        if (!$certificate) {
            return view('certificates.verification-error', [
                'message' => 'Certificat non trouvé ou invalide',
                'error_code' => 'NOT_FOUND'
            ]);
        }

        // Charger les relations nécessaires
        $certificate->load(['reservation.event.location', 'reservation.user', 'issuedBy']);

        return view('certificates.verification-success', [
            'certificate' => $certificate
        ]);
    }

    /**
     * API pour vérification programmatique
     */
    public function apiVerify(Request $request)
    {
        $token = $request->input('token');
        
        if (!$token) {
            return response()->json([
                'valid' => false,
                'error' => 'Token manquant'
            ], 400);
        }

        $certificate = Certification::findByVerificationToken($token);

        if (!$certificate) {
            return response()->json([
                'valid' => false,
                'error' => 'Certificat non trouvé'
            ], 404);
        }

        $certificate->load(['reservation.event', 'reservation.user']);

        return response()->json([
            'valid' => true,
            'certificate' => [
                'code' => $certificate->certificate_code,
                'type' => $certificate->type,
                'points' => $certificate->points_earned,
                'date_awarded' => $certificate->date_awarded->format('d/m/Y'),
                'event' => [
                    'title' => $certificate->reservation->event->title,
                    'date' => $certificate->reservation->event->date->format('d/m/Y'),
                    'location' => $certificate->reservation->event->location->name ?? 'Non défini'
                ],
                'participant' => [
                    'name' => $certificate->reservation->user->name,
                    'email' => $certificate->reservation->user->email
                ]
            ]
        ]);
    }
}