<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id',
        'assignable_type',
        'assignable_id',
        'role',
        'status',
        'assigned_at',
        'start_date',
        'end_date',
        'hours_committed',
        'hours_worked',
        'notes',
        'rating',
        'feedback',
        'assigned_by',
        'approved_at',
        'completed_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'hours_committed' => 'integer',
        'hours_worked' => 'integer',
        'rating' => 'decimal:1',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relations
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function($subQ) use ($startDate, $endDate) {
                  $subQ->where('start_date', '<=', $startDate)
                       ->where('end_date', '>=', $endDate);
              });
        });
    }

    // Accessors
    public function getEventAttribute()
    {
        return $this->assignable_type === Event::class ? $this->assignable : null;
    }

    public function getCampaignAttribute()
    {
        return $this->assignable_type === Campaign::class ? $this->assignable : null;
    }

    public function getDurationInDaysAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->hours_committed == 0) {
            return 0;
        }
        return round(($this->hours_worked / $this->hours_committed) * 100, 2);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->end_date && $this->end_date->isPast() && $this->status !== 'completed';
    }

    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    // Methods
    public function approve(User $approver): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        return $this->update([
            'status' => 'approved',
            'assigned_by' => $approver->id,
            'approved_at' => now()
        ]);
    }

    public function reject(User $rejector, string $reason = null): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        return $this->update([
            'status' => 'rejected',
            'assigned_by' => $rejector->id,
            'notes' => $reason ? ($this->notes . "\nRejection reason: " . $reason) : $this->notes
        ]);
    }

    public function complete(int $hoursWorked = null, float $rating = null, string $feedback = null): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        $updateData = [
            'status' => 'completed',
            'completed_at' => now()
        ];

        if ($hoursWorked !== null) {
            $updateData['hours_worked'] = $hoursWorked;
        }

        if ($rating !== null) {
            $updateData['rating'] = $rating;
        }

        if ($feedback !== null) {
            $updateData['feedback'] = $feedback;
        }

        return $this->update($updateData);
    }

    public function cancel(string $reason = null): bool
    {
        if (!in_array($this->status, ['pending', 'approved'])) {
            return false;
        }

        return $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? ($this->notes . "\nCancellation reason: " . $reason) : $this->notes
        ]);
    }

    public function updateHoursWorked(int $hours): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        return $this->update(['hours_worked' => $hours]);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'approved';
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            'rejected' => 'secondary',
            default => 'light'
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'approved' => 'Approuvé',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            'rejected' => 'Rejeté',
            default => 'Inconnu'
        };
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'coordinator' => 'Coordinateur',
            'helper' => 'Aide',
            'specialist' => 'Spécialiste',
            'supervisor' => 'Superviseur',
            'organizer' => 'Organisateur',
            default => ucfirst($this->role)
        };
    }
}
