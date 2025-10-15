<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordResetToken extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'email';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Generate a secure random token.
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Create a new password reset token.
     */
    public static function createToken(string $email): static
    {
        // Delete any existing tokens for this email
        static::where('email', $email)->delete();

        return static::create([
            'email' => $email,
            'token' => static::generateToken(),
        ]);
    }

    /**
     * Check if token is valid (not expired).
     */
    public function isValid(): bool
    {
        return $this->created_at && $this->created_at->addMinutes(60)->isFuture();
    }

    /**
     * Find a valid token by email and token.
     */
    public static function findValidToken(string $email, string $token): ?static
    {
        return static::where('email', $email)
            ->where('token', $token)
            ->first();
    }
}
