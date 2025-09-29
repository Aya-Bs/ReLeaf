<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'max_participants',
        'status',
        'image',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the user that owns the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event participations.
     */
    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipation::class);
    }

    /**
     * Get the event reservations.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the event waiting list.
     */
    public function waitingList(): HasMany
    {
        return $this->hasMany(WaitingList::class);
    }

    /**
     * Scope pour les événements publiés.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope pour les événements à venir.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now());
    }

    /**
     * Scope pour les événements passés.
     */
    public function scopePast($query)
    {
        return $query->where('date', '<', now());
    }

    /**
     * Check if event is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if event is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->date >= now();
    }

    /**
     * Check if event is full.
     */
    public function isFull(): bool
    {
        $confirmedReservations = $this->reservations()
                                    ->where('status', 'confirmed')
                                    ->count();
        return $confirmedReservations >= $this->max_participants;
    }

    /**
     * Get available spots count.
     */
    public function getAvailableSpots(): int
    {
        $confirmedReservations = $this->reservations()
                                    ->where('status', 'confirmed')
                                    ->count();
        return max(0, $this->max_participants - $confirmedReservations);
    }

    /**
     * Get waiting list count.
     */
    public function getWaitingListCount(): int
    {
        return $this->waitingList()->where('status', 'waiting')->count();
    }
}
