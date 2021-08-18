<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee'
    ];

    public static function latestFee()
    {
        return static::orderBy('created_at', 'desc')->first();
    }
}
