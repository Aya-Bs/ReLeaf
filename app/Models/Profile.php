<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'bio',
        'avatar',
        'birth_date',
        'city',
        'country',
        'interests',
        'notification_preferences',
        'is_eco_ambassador',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'interests' => 'array',
        'is_eco_ambassador' => 'boolean',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the avatar URL attribute.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/avatars/' . $this->avatar)
            : asset('images/default-avatar.png');
    }
}
