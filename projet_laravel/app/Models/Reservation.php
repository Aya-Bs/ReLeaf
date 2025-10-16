<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_id',
        'event_id',
        'seat_number',
        'status',
        'reserved_at',
        'expires_at',
        'confirmed_at',
        'confirmed_by',
        'num_guests',
        'comments',
        'seat_details',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'seat_details' => 'array',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function certification(): HasOne
    {
        return $this->hasOne(Certification::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Méthodes utiles
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function canBeConfirmed(): bool
    {
        return $this->status === 'pending';
    }

    public function confirm(User $admin): bool
    {
        if ($this->canBeConfirmed()) {
            $this->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => $admin->id,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Créer une nouvelle réservation
     */
    public static function createWithTimeout(array $data): self
    {
        $data['reserved_at'] = now();
        $data['expires_at'] = null; // Pas d'expiration automatique
        $data['status'] = 'pending';

        return self::create($data);
    }
}
