<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\TracksLoginHistory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TracksLoginHistory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'role',
        'birth_date',
        'city',
        'country',
        'is_email_verified',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the user's profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the user's volunteer profile.
     */
    public function volunteer(): HasOne
    {
        return $this->hasOne(Volunteer::class);
    }

    /**
     * Check if user is a volunteer.
     */
    public function isVolunteer(): bool
    {
        return $this->volunteer !== null;
    }

    /**
     * Create a profile for the user if it doesn't exist.
     */
    public function createProfileIfNotExists(): void
    {
        if (! $this->profile) {
            $this->profile()->create([]);
        }
    }

    /**
     * Get the user's full name from profile or fallback to name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->profile?->full_name ?? $this->name;
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile && $this->profile->avatar) {
            return Storage::url($this->profile->avatar);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user is auteur.
     */
    public function isAuteur(): bool
    {
        return $this->role === 'auteur';
    }

    public function isCampaignOwner(Campaign $campaign)
    {
        return $this->id === $campaign->organizer_id;
    }

    /**
     * Get user's full name from first_name and last_name.
     */
    public function getFullNameFromFieldsAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name.' '.$this->last_name;
        }

        return $this->name;
    }

    /**
     * Check if user is organizer.
     */
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    /**
     * Get the sponsor record associated with the user.
     */
    public function sponsor(): HasOne
    {
        return $this->hasOne(Sponsor::class);
    }

    /**
     * Get the user's waiting list entries.
     */
    public function waitingLists(): HasMany
    {
        return $this->hasMany(WaitingList::class);
    }

    /**
     * Get the user's reservations.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the user's certifications through reservations.
     */
    public function certifications()
    {
        return $this->hasManyThrough(Certification::class, Reservation::class);
    }

    /**
     * Relation avec les blogs créés par l'utilisateur
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    /**
     * Relation avec les reviews postés par l'utilisateur
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
