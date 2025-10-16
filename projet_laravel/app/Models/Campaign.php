<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'start_date',
        'end_date',
        'goal',
        'funds_raised',
        'participants_count',
        'environmental_impact',
        'image_url',
        'visibility',
        'tags',
        'status',
        'organizer_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'goal' => 'decimal:2',
        'funds_raised' => 'decimal:2',
        'visibility' => 'boolean',
        'tags' => 'array',
    ];

    // Relation avec l'organisateur (User)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // Relation avec les ressources
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    // Relation avec les événements
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Relation avec les dons
    // public function donations()
    // {
    //     return $this->hasMany(Donation::class);
    // }

    // Accessor pour le pourcentage de progression financière
    public function getFundsProgressPercentageAttribute()
    {
        if ($this->goal == 0 || $this->goal === null) {
            return 0;
        }

        return round(($this->funds_raised / $this->goal) * 100, 2);
    }

    // Accessor pour les jours restants
    public function getDaysRemainingAttribute()
    {
        $now = now();
        $end = $this->end_date;

        if ($end->lt($now)) {
            return 0;
        }

        return $now->diffInDays($end);
    }

    // Scope pour les campagnes actives
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope pour les campagnes visibles
    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }

    // Méthode pour mettre à jour le compteur de participants
    public function updateParticipantsCount()
    {
        $this->participants_count = $this->events->sum('max_participants');
        $this->save();
    }

    /**
     * Vérifier si la campagne peut être modifiée
     * ✅ AJOUTÉ : Même pattern que Event
     */
    public function canBeEdited()
    {
        // Exemple de logique : les campagnes "completed" ou "cancelled" ne peuvent pas être modifiées
        // Ajustez cette logique selon vos besoins
        return ! in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Vérifier si la campagne peut être supprimée
     * ✅ AJOUTÉ : Logique supplémentaire pour la suppression
     */
    public function canBeDeleted()
    {
        // Exemple : les campagnes avec des ressources ou événements ne peuvent pas être supprimées
        // Ajustez cette logique selon vos besoins
        return $this->resources->isEmpty() && $this->events->isEmpty();
    }

    /**
     * Get all assignments for this campaign.
     */
    public function assignments(): MorphMany
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }

    /**
     * Get approved volunteers for this campaign.
     */
    public function volunteers()
    {
        return $this->assignments()
            ->where('status', 'approved')
            ->with('volunteer.user');
    }

    /**
     * Get pending volunteer applications for this campaign.
     */
    public function pendingVolunteers()
    {
        return $this->assignments()
            ->where('status', 'pending')
            ->with('volunteer.user');
    }

    // Ajouter cette relation dans Campaign.php
    public function deletionRequests()
    {
        return $this->hasMany(CampaignDeletionRequest::class);
    }

    public function pendingDeletionRequest()
    {
        return $this->hasOne(CampaignDeletionRequest::class)->where('status', 'pending');
    }
}
