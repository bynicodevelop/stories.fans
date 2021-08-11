<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitationStats()
    {
        return $this->hasMany(InvitationStat::class);
    }
}
