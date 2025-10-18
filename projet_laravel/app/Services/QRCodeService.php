<?php

namespace App\Services;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Générer un QR code pour un certificat
     */
    public function generateCertificateQR(string $certificateId, string $verificationUrl): string
    {
        // Créer le QR code avec Bacon QR Code
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        $qrCodeString = $writer->writeString($verificationUrl);

        // Sauvegarder le QR code
        $filename = 'certificates/qr-codes/cert_' . $certificateId . '_qr.svg';
        Storage::disk('public')->put($filename, $qrCodeString);

        return $filename;
    }

    /**
     * Générer un QR code avec logo (optionnel)
     */
    public function generateCertificateQRWithLogo(string $certificateId, string $verificationUrl, ?string $logoPath = null): string
    {
        // Pour l'instant, utiliser la même méthode que sans logo
        // TODO: Implémenter le logo avec Bacon QR Code
        return $this->generateCertificateQR($certificateId, $verificationUrl);
    }

    /**
     * Obtenir l'URL publique du QR code
     */
    public function getQRCodeUrl(string $filename): string
    {
        return Storage::disk('public')->url($filename);
    }

    /**
     * Supprimer un QR code
     */
    public function deleteQRCode(string $filename): bool
    {
        return Storage::disk('public')->delete($filename);
    }
}
