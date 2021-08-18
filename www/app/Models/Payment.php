<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'fee_id',
        'net_price',
        'hosted_invoice_url',
    ];

    public function userSubscription()
    {
        return $this->belongsTo(UserSubscription::class);
    }
}
