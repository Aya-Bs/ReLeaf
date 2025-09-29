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
        'certificate_code'
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
        return self::create([
            'reservation_id' => $reservation->id,
            'type' => 'participation',
            'points_earned' => self::calculatePoints($reservation->event),
            'date_awarded' => now(),
            'issued_by' => $admin->id,
            'certificate_code' => self::generateUniqueCode()
        ]);
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
}
