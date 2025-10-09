<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Donation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'sponsor_id',
        'donor_name',
        'donor_email',
        'amount',
        'currency',
        'type',
        'status',
        'payment_method',
        'notes',
        'donated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'donated_at' => 'datetime',
    ];

    /**
     * Get the event that the donation is for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the sponsor who made this donation (if applicable).
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }

    /**
     * Get the admin who processed this donation.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope for individual donations (from citizens).
     */
    public function scopeIndividual($query)
    {
        return $query->where('type', 'individual');
    }

    /**
     * Scope for sponsor donations.
     */
    public function scopeSponsor($query)
    {
        return $query->where('type', 'sponsor');
    }

    /**
     * Scope for pending donations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed donations.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for failed donations.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if donation is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if donation is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if donation is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if donation is from a sponsor.
     */
    public function isFromSponsor(): bool
    {
        return $this->type === 'sponsor';
    }

    /**
     * Check if donation is from an individual.
     */
    public function isFromIndividual(): bool
    {
        return $this->type === 'individual';
    }

    /**
     * Get formatted amount attribute.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' €';
    }

    /**
     * Determine if the donation is still within the 24h editable window.
     */
    public function isWithinEditableWindow(): bool
    {
        $reference = $this->donated_at ?? $this->created_at;
        if (!$reference) return false;
        return now()->diffInHours($reference) < 24;
    }

    /**
     * Determine if user can modify this donation.
     */
    public function canBeModifiedBy(User $user): bool
    {
        if (!$this->isPending()) return false; // only pending donations editable
        if (!$this->isWithinEditableWindow()) return false; // 24h window
        // Owner by user_id
        if ($this->user_id && $this->user_id === $user->id) return true;
        // Sponsor owning the sponsor record
        if ($user->role === 'sponsor' && $user->sponsor && $this->sponsor_id === $user->sponsor->id) return true;
        return false;
    }

    /**
     * Determine if user can delete this donation (same rules as modify for now).
     */
    public function canBeDeletedBy(User $user): bool
    {
        return $this->canBeModifiedBy($user);
    }

    /**
     * Remaining editable hours (0 if locked)
     */
    public function editableRemainingHours(): int
    {
        $reference = $this->donated_at ?? $this->created_at;
        if (!$reference) return 0;
        $elapsed = now()->diffInMinutes($reference); // finer precision
        $total = 24 * 60;
        $remaining = $total - $elapsed;
        return $remaining > 0 ? (int) ceil($remaining / 60) : 0;
    }
}
