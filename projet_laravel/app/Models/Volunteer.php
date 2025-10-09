<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skills',
        'availability',
        'experience_level',
        'preferred_regions',
        'max_hours_per_week',
        'emergency_contact',
        'medical_conditions',
        'status',
        'bio',
        'motivation',
        'previous_volunteer_experience'
    ];

    protected $casts = [
        'skills' => 'array',
        'availability' => 'array',
        'preferred_regions' => 'array',
        'max_hours_per_week' => 'integer',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function eventAssignments(): HasMany
    {
        return $this->assignments()->where('assignable_type', Event::class);
    }

    public function campaignAssignments(): HasMany
    {
        return $this->assignments()->where('assignable_type', Campaign::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRegion($query, $region)
    {
        return $query->whereJsonContains('preferred_regions', $region);
    }

    public function scopeBySkill($query, $skill)
    {
        return $query->whereJsonContains('skills', $skill);
    }

    public function scopeAvailable($query, $startDate, $endDate)
    {
        return $query->where('status', 'active')
                    ->where(function($q) use ($startDate, $endDate) {
                        $q->whereJsonContains('availability', [
                            'start_date' => $startDate,
                            'end_date' => $endDate
                        ]);
                    });
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->user->full_name;
    }

    public function getEmailAttribute(): string
    {
        return $this->user->email;
    }

    public function getPhoneAttribute(): string
    {
        return $this->user->phone ?? '';
    }

    public function getTotalHoursWorkedAttribute(): int
    {
        return $this->assignments()->where('status', 'completed')->sum('hours_worked');
    }

    public function getActiveAssignmentsCountAttribute(): int
    {
        return $this->assignments()->whereIn('status', ['pending', 'approved'])->count();
    }

    // Methods
    public function isAvailableFor($startDate, $endDate): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        // Vérifier les conflits avec les assignments existants
        $conflictingAssignments = $this->assignments()
            ->whereIn('status', ['approved', 'pending'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        return !$conflictingAssignments;
    }

    public function canTakeAssignment($hoursRequired): bool
    {
        $currentWeeklyHours = $this->getCurrentWeeklyHours();
        return ($currentWeeklyHours + $hoursRequired) <= $this->max_hours_per_week;
    }

    public function getCurrentWeeklyHours(): int
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        return $this->assignments()
            ->whereIn('status', ['approved', 'completed'])
            ->whereBetween('start_date', [$startOfWeek, $endOfWeek])
            ->sum('hours_committed');
    }

    public function getRatingAttribute(): float
    {
        $completedAssignments = $this->assignments()
            ->where('status', 'completed')
            ->whereNotNull('rating')
            ->get();

        if ($completedAssignments->isEmpty()) {
            return 0.0;
        }

        return $completedAssignments->avg('rating');
    }

    public function getSkillsListAttribute(): array
    {
        return $this->skills ?? [];
    }

    public function hasSkill($skill): bool
    {
        return in_array($skill, $this->skills ?? []);
    }

    public function addSkill($skill): void
    {
        $skills = $this->skills ?? [];
        if (!in_array($skill, $skills)) {
            $skills[] = $skill;
            $this->update(['skills' => $skills]);
        }
    }

    public function removeSkill($skill): void
    {
        $skills = $this->skills ?? [];
        $skills = array_filter($skills, fn($s) => $s !== $skill);
        $this->update(['skills' => array_values($skills)]);
    }
}
