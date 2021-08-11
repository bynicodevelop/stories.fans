<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'stats'
    ];

    public function invitationLink()
    {
        return $this->belongsTo(InvitationLink::class);
    }
}
