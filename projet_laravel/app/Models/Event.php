<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'images',
        'user_id',
        'duration'
    ];

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
     * Get the user that owns the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sponsors that support this event.
     */
    public function sponsors(): BelongsToMany
    {
        return $this->belongsToMany(Sponsor::class, 'sponsor_events')
            ->withPivot(['amount', 'status', 'notes'])
            ->withTimestamps();
    }

    /**
     * Get the donations for this event.
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the total amount donated for this event.
     */
    public function getTotalDonationsAttribute(): float
    {
        return $this->donations()->where('status', 'confirmed')->sum('amount');
    }

    /**
     * Get the total amount sponsored for this event.
     */
    public function getTotalSponsorshipAttribute(): float
    {
        return $this->sponsors()->wherePivot('status', 'active')->sum('sponsor_events.amount');
    }

    /**
     * Get the total funding for this event (donations + sponsorships).
     */
    public function getTotalFundingAttribute(): float
    {
        return $this->getTotalDonationsAttribute() + $this->getTotalSponsorshipAttribute();
    }

    // /**
    //  * Get the event participations.
    //  */
    // public function participations(): HasMany
    // {
    //     return $this->hasMany(EventParticipation::class);
    // }

    /**
     * Scope for pending events (waiting for admin approval)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for published events (approved by admin)
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
     * Scope for events by organizer
     */
    public function scopeByOrganizer($query, $userId)
    {
        return $query->where('user_id', $userId);
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

    /**
     * Check if event can be edited (only draft or pending events)
     */
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
}