<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;


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
        'location_id',
        'max_participants',
        'status',
        'images',
        'user_id',
        'duration',
        'campaign_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
        'images' => 'array',
    ];


        /**
     * Get the campaign that owns the event.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
    
    /**
     * Get the user that owns the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for pending events (waiting for admin approval)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
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
     * Scope for draft events
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
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
     * Check if event is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if event is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if event is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if event is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }


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
        $activeReservations = $this->reservations()
                                    ->whereIn('status', ['pending', 'confirmed'])
                                    ->count();
        return max(0, $this->max_participants - $activeReservations);
    }


     /**
     * Scope for events by organizer
     */
    public function scopeByOrganizer($query, $userId)
    {
        return $query->where('user_id', $userId);
    }


    /**
     * Get waiting list count.
     */
    public function getWaitingListCount(): int
    {
        return $this->waitingList()->where('status', 'waiting')->count();
    }

    /**
     * Scope to get events that have available seats.
     * Usage: Event::withAvailableSeats()->get()
     */
    public function scopeWithAvailableSeats($query)
    {
        // Select events where max_participants > number of active reservations (pending or confirmed)
        return $query->whereRaw(
            "max_participants > (select count(*) from reservations where reservations.event_id = events.id and reservations.status in ('pending','confirmed'))"
        );
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    /**
     * Check if event can be deleted (only draft, pending, cancelled or past events)
     */
    public function canBeDeleted(): bool
    {
        return in_array($this->status, ['draft', 'pending', 'cancelled']) || $this->date < now();
    }

    /**
     * Submit event for admin approval
     */
    public function submitForApproval(): bool
    {
        return $this->update(['status' => 'pending']);
    }


    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }
        
        return collect($this->images)->map(function ($image) {
            return Storage::url($image);
        })->toArray();
    }


}

