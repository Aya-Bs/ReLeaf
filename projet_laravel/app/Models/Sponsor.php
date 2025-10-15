<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponsor extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'contact_email',
        'contact_phone',
        'website',
        'address',
        'city',
        'country',
        'motivation',
        'additional_info',
        'sponsorship_type',
        'status',
        'validated_at',
        'validated_by',
        'deletion_reason',
        'deleted_at',
        'deleted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'validated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the events that the sponsor supports.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'sponsor_events')
            ->withPivot(['amount', 'status', 'created_at', 'updated_at'])
            ->withTimestamps();
    }

    /**
     * Get the donations made by this sponsor.
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the admin who validated this sponsor.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Get the admin who deleted this sponsor.
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Owning user (account created after validation).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for pending sponsors (waiting for admin approval).
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for validated sponsors.
     */
    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }

    /**
     * Scope for rejected sponsors.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for active sponsors (not deleted).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Check if sponsor is pending validation.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if sponsor is validated.
     */
    public function isValidated(): bool
    {
        return $this->status === 'validated';
    }

    /**
     * Check if sponsor is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if sponsor requested account deletion.
     */
    public function isDeletionRequested(): bool
    {
        return $this->status === 'deletion_requested';
    }

    /**
     * Check if sponsor is active (not deleted).
     */
    public function isActive(): bool
    {
        return is_null($this->deleted_at);
    }

    /**
     * Scope for deletion requested sponsors.
     */
    public function scopeDeletionRequested($query)
    {
        return $query->where('status', 'deletion_requested');
    }

    /**
     * Get the total amount donated by this sponsor.
     */
    public function getTotalDonatedAttribute(): float
    {
        return $this->donations()->sum('amount');
    }

    /**
     * Get the total amount sponsored for events.
     */
    public function getTotalSponsoredAttribute(): float
    {
        return $this->events()->sum('sponsor_events.amount');
    }

    /**
     * Check if sponsor offers financial sponsorship.
     */
    public function isFinancialSponsor(): bool
    {
        return $this->sponsorship_type === 'argent';
    }

    /**
     * Check if sponsor offers material sponsorship.
     */
    public function isMaterialSponsor(): bool
    {
        return $this->sponsorship_type === 'materiel';
    }

    /**
     * Check if sponsor offers service sponsorship.
     */
    public function isServiceSponsor(): bool
    {
        return $this->sponsorship_type === 'service';
    }

    /**
     * Get formatted sponsorship type.
     */
    public function getFormattedSponsorshipTypeAttribute(): string
    {
        return match ($this->sponsorship_type) {
            'argent' => 'Sponsoring financier',
            'materiel' => 'Sponsoring matériel',
            'service' => 'Sponsoring service',
            default => 'Non spécifié'
        };
    }

    /**
     * Scope for financial sponsors.
     */
    public function scopeFinancial($query)
    {
        return $query->where('sponsorship_type', 'argent');
    }

    /**
     * Scope for material sponsors.
     */
    public function scopeMaterial($query)
    {
        return $query->where('sponsorship_type', 'materiel');
    }

    /**
     * Scope for service sponsors.
     */
    public function scopeService($query)
    {
        return $query->where('sponsorship_type', 'service');
    }
}
