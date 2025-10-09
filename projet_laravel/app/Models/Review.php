<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_name',
        'user_id',
        'rating',
        'comment',
        'date_posted',
        'blog_id',
        'event_id',
        'media_url',
    ];
}
