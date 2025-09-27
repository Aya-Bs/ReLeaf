<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'date_posted',
        'image_url',
        'tags',
    ];

    // Relation avec l'auteur
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Relation avec les reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'blog_id');
    }
}
