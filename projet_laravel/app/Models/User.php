<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Traits\TracksLoginHistory;

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
        if (!$this->profile) {
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

        // Utiliser le nom complet si disponible, sinon le nom d'utilisateur
        $name = $this->getFullNameFromFieldsAttribute();
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=ffffff&background=2d5a27&size=200';
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
     * Get user's full name from first_name and last_name.
     */
    public function getFullNameFromFieldsAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
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
}
