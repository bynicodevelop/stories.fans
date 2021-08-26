<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    const VIDEO = "video";

    const IMAGE = "image";

    protected $fillable = [
        'name_preview',
        'name',
        'ext',
        'type',
        'orientation',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
