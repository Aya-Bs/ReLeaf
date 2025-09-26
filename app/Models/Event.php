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
}
