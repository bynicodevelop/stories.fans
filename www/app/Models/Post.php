<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'is_premium',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function countPosts()
    {
        return $this->count();
    }

    public function postLiked()
    {
        return $this->hasMany(Like::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Like::class, 'likes', 'post_id', 'user_id'); //->withTimestamps();
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function countLikes()
    {
        return $this->hasMany(Like::class)->count();
    }
}
