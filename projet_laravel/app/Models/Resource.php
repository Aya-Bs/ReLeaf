<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity_needed',
        'quantity_pledged',
        'unit',
        'provider',
        'status',
        'resource_type',
        'category',
        'priority',
        'notes',
        'image_url',
        'campaign_id'
    ];

    protected $casts = [
        'quantity_needed' => 'integer',
        'quantity_pledged' => 'integer',
    ];

    // Relation avec la campagne
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Calcul du pourcentage de progression
    public function getProgressPercentageAttribute()
    {
        if ($this->quantity_needed == 0) return 0;
        return round(($this->quantity_pledged / $this->quantity_needed) * 100, 2);
    }

    // Accessor pour la quantité manquante
    public function getMissingQuantityAttribute()
    {
        return max(0, $this->quantity_needed - $this->quantity_pledged);
    }

    // Scope pour les ressources nécessaires
    public function scopeNeeded($query)
    {
        return $query->where('status', 'needed');
    }

    // Scope pour les ressources par priorité
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    // Méthode pour mettre à jour le statut automatiquement
    public function updateStatus()
    {
        if ($this->quantity_pledged >= $this->quantity_needed && $this->quantity_needed > 0) {
            $this->status = 'received';
        } elseif ($this->quantity_pledged > 0) {
            $this->status = 'pledged';
        } else {
            $this->status = 'needed';
        }
        $this->save();
    }

    // Méthode pour ajouter une promesse de ressource
    public function pledgeQuantity($quantity, $provider = null)
    {
        $this->quantity_pledged += $quantity;
        
        if ($provider) {
            $this->provider = $provider;
        }
        
        $this->updateStatus();
    }
}