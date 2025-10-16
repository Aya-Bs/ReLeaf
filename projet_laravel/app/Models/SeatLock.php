<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'seat_number',
        'user_id',
        'locked_at',
        'expires_at',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Méthodes utilitaires
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    public function getRemainingSeconds(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return $this->expires_at->diffInSeconds(now());
    }

    /**
     * Méthodes statiques
     */
    public static function lockSeat(int $eventId, string $seatNumber, int $userId): self
    {
        // Supprimer les verrous expirés
        self::cleanExpiredLocks();

        // Supprimer le verrou existant de l'utilisateur s'il existe
        self::where('user_id', $userId)->delete();

        // Créer le nouveau verrou
        return self::create([
            'event_id' => $eventId,
            'seat_number' => $seatNumber,
            'user_id' => $userId,
            'locked_at' => now(),
            'expires_at' => now()->addMinutes(5),
        ]);
    }

    public static function isLocked(int $eventId, string $seatNumber): bool
    {
        // Nettoyer les verrous expirés
        self::cleanExpiredLocks();

        return self::where('event_id', $eventId)
            ->where('seat_number', $seatNumber)
            ->where('expires_at', '>', now())
            ->exists();
    }

    public static function getLockedSeats(int $eventId): array
    {
        // Nettoyer les verrous expirés
        self::cleanExpiredLocks();

        return self::where('event_id', $eventId)
            ->where('expires_at', '>', now())
            ->pluck('seat_number')
            ->toArray();
    }

    public static function releaseSeat(int $eventId, string $seatNumber): void
    {
        self::where('event_id', $eventId)
            ->where('seat_number', $seatNumber)
            ->delete();
    }

    public static function cleanExpiredLocks(): void
    {
        self::where('expires_at', '<', now())->delete();
    }

    public static function getUserLock(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->first();
    }
}
