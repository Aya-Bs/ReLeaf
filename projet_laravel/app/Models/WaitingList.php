<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'user_name',
        'user_email',
        'status',
        'position',
        'joined_at',
        'promoted_at',
        'promoted_by',
        'notes'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'promoted_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function promotedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }

    /**
     * Scopes
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopePromoted($query)
    {
        return $query->where('status', 'promoted');
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Méthodes utiles
     */
    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }

    public function isPromoted(): bool
    {
        return $this->status === 'promoted';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Ajouter un utilisateur à la liste d'attente
     */
    public static function addToWaitingList(int $userId, int $eventId): self
    {
        $user = User::findOrFail($userId);
        
        // Vérifier si l'utilisateur n'est pas déjà dans la liste d'attente
        $existing = self::where('user_id', $userId)
                       ->where('event_id', $eventId)
                       ->where('status', 'waiting')
                       ->first();
        
        if ($existing) {
            throw new \Exception('Vous êtes déjà dans la liste d\'attente pour cet événement.');
        }

        // Calculer la prochaine position
        $nextPosition = self::where('event_id', $eventId)
                           ->where('status', 'waiting')
                           ->max('position') + 1;

        return self::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'status' => 'waiting',
            'position' => $nextPosition,
            'joined_at' => now(),
        ]);
    }

    /**
     * Promouvoir le premier utilisateur de la liste d'attente
     */
    public static function promoteFirst(int $eventId): ?self
    {
        $firstWaiting = self::where('event_id', $eventId)
                           ->where('status', 'waiting')
                           ->orderBy('position', 'asc')
                           ->first();

        if (!$firstWaiting) {
            return null;
        }

        $firstWaiting->update([
            'status' => 'promoted',
            'promoted_at' => now(),
            'promoted_by' => auth()->id(),
        ]);

        // Réajuster les positions des autres utilisateurs
        self::where('event_id', $eventId)
            ->where('status', 'waiting')
            ->where('position', '>', $firstWaiting->position)
            ->decrement('position');

        return $firstWaiting;
    }

    /**
     * Obtenir la liste d'attente pour un événement
     */
    public static function getWaitingListForEvent(int $eventId): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('event_id', $eventId)
                   ->where('status', 'waiting')
                   ->orderBy('position', 'asc')
                   ->with('user')
                   ->get();
    }

    /**
     * Obtenir la position d'un utilisateur dans la liste d'attente
     */
    public static function getUserPosition(int $userId, int $eventId): ?int
    {
        $waitingList = self::where('user_id', $userId)
                           ->where('event_id', $eventId)
                           ->where('status', 'waiting')
                           ->first();

        return $waitingList ? $waitingList->position : null;
    }

    /**
     * Retirer un utilisateur de la liste d'attente
     */
    public static function removeFromWaitingList(int $userId, int $eventId): bool
    {
        $waitingList = self::where('user_id', $userId)
                           ->where('event_id', $eventId)
                           ->where('status', 'waiting')
                           ->first();

        if (!$waitingList) {
            return false;
        }

        $position = $waitingList->position;

        // Marquer comme annulé
        $waitingList->update(['status' => 'cancelled']);

        // Réajuster les positions
        self::where('event_id', $eventId)
            ->where('status', 'waiting')
            ->where('position', '>', $position)
            ->decrement('position');

        return true;
    }
}