<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'type',
        'points_earned',
        'date_awarded',
        'issued_by',
        'certificate_code',
        'qr_code_path',
        'verification_token'
    ];

    protected $casts = [
        'date_awarded' => 'datetime'
    ];

    /**
     * Relations
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Générer un certificat automatiquement
     */
    public static function generateForReservation(Reservation $reservation, User $admin): self
    {
        $certificate = self::create([
            'reservation_id' => $reservation->id,
            'type' => 'participation',
            'points_earned' => self::calculatePoints($reservation->event),
            'date_awarded' => now(),
            'issued_by' => $admin->id,
            'certificate_code' => self::generateUniqueCode(),
            'verification_token' => self::generateVerificationToken()
        ]);

        // Générer le QR code
        $certificate->generateQRCode();

        return $certificate;
    }

    /**
     * Calculer les points basés sur l'événement
     */
    private static function calculatePoints(Event $event): int
    {
        // Logique simple : points fixes pour tous les événements
        return 10; // Points standard pour participation
    }

    /**
     * Générer un code unique
     */
    private static function generateUniqueCode(): string
    {
        do {
            $code = 'ECO-' . strtoupper(Str::random(8));
        } while (self::where('certificate_code', $code)->exists());
        
        return $code;
    }

    /**
     * Obtenir l'URL de téléchargement du certificat
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('user.certificates.download', ['code' => $this->certificate_code]);
    }

    /**
     * Générer un token de vérification unique
     */
    private static function generateVerificationToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('verification_token', $token)->exists());
        
        return $token;
    }

    /**
     * Générer le QR code pour ce certificat
     */
    public function generateQRCode(): void
    {
        $qrService = app(\App\Services\QRCodeService::class);
        
        // Utiliser l'URL de l'application au lieu de localhost
        $baseUrl = 'http://192.168.1.7:8000'; // Votre IP Wi-Fi locale
        $verificationUrl = $baseUrl . '/certificates/verify/' . $this->verification_token;
        
        $qrPath = $qrService->generateCertificateQR($this->id, $verificationUrl);
        
        $this->update(['qr_code_path' => $qrPath]);
    }

    /**
     * Obtenir l'URL du QR code
     */
    public function getQRCodeUrlAttribute(): ?string
    {
        if (!$this->qr_code_path) {
            return null;
        }
        
        $qrService = app(\App\Services\QRCodeService::class);
        return $qrService->getQRCodeUrl($this->qr_code_path);
    }

    /**
     * Obtenir l'URL de vérification
     */
    public function getVerificationUrlAttribute(): string
    {
        return route('certificates.verify', ['token' => $this->verification_token]);
    }

    /**
     * Trouver un certificat par token de vérification
     */
    public static function findByVerificationToken(string $token): ?self
    {
        return self::where('verification_token', $token)->first();
    }

    /**
     * 🔗 Générer l'URL de partage LinkedIn
     */
    public function getLinkedInShareUrl(): string
    {
        $title = "Certificat de participation - " . $this->reservation->event->title;
        $summary = "J'ai participé à l'événement écologique '{$this->reservation->event->title}' et obtenu {$this->points_earned} points éco ! 🌱";
        $url = route('certificates.verify', ['token' => $this->verification_token]);
        
        $params = http_build_query([
            'mini' => 'true',
            'url' => $url,
            'title' => $title,
            'summary' => $summary,
            'source' => config('app.name', 'ReLeaf')
        ]);
        
        return "https://www.linkedin.com/sharing/share-offsite/?" . $params;
    }

    /**
     * 🔗 Générer l'URL de partage Twitter/X
     */
    public function getTwitterShareUrl(): string
    {
        $text = "🌱 J'ai participé à '{$this->reservation->event->title}' et obtenu mon certificat éco ! #{$this->certificate_code} #EcoEvents #Durabilité";
        $url = route('certificates.verify', ['token' => $this->verification_token]);
        
        $params = http_build_query([
            'text' => $text,
            'url' => $url,
            'hashtags' => 'EcoEvents,Durabilité,Environnement'
        ]);
        
        return "https://twitter.com/intent/tweet?" . $params;
    }

    /**
     * 🔗 Générer l'URL de partage Facebook
     */
    public function getFacebookShareUrl(): string
    {
        $url = route('certificates.verify', ['token' => $this->verification_token]);
        
        $params = http_build_query([
            'u' => $url,
            'quote' => "J'ai participé à l'événement écologique '{$this->reservation->event->title}' ! 🌱"
        ]);
        
        return "https://www.facebook.com/sharer/sharer.php?" . $params;
    }

    /**
     * 🔗 Obtenir tous les liens de partage
     */
    public function getSocialShareUrls(): array
    {
        return [
            'linkedin' => $this->getLinkedInShareUrl(),
            'twitter' => $this->getTwitterShareUrl(),
            'facebook' => $this->getFacebookShareUrl()
        ];
    }
}
