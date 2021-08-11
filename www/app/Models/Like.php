<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    const USER_ID = 'user_id';
    const POST_ID = 'post_id';

    protected $fillable = [
        self::USER_ID,
        self::POST_ID,
    ];
}
