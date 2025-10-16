<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'city',
        'capacity',
        'description',
        'latitude',
        'longitude',
        'images',
        'reserved',
        'in_repair',
        'price', 
    ];

    protected $casts = [
        'images' => 'array',
        'reserved' => 'boolean',
        'in_repair' => 'boolean',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
