<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'date_posted',
        'image_url',
        'tags',
    ];
    

    protected $casts = [
        'date_posted' => 'datetime',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class);
}

}
