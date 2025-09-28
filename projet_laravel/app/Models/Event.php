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
        'images',
        'user_id',
        'duration',
        'campaign_id'
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

    public function isRejected(): bool
{
    return $this->status === 'rejected';
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