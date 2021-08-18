<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    public const NAME = 'name';
    public const CONTENT = 'content';

    public const PRICE_MONTHLY = 'price_monthly';
    public const PRICE_MONTHLY_ID = 'price_monthly_id';

    public const PRICE_QUARTERLY = 'price_quarterly';
    public const PRICE_QUARTERLY_ID = 'price_quarterly_id';

    public const PRICE_ANNUALLY = 'price_annually';
    public const PRICE_ANNUALLY_ID = 'price_annually_id';

    public const DAY_TRIAL = 'day_trial';

    public const DELETED = 'deleted';

    protected $fillable = [
        'name',
        'content',
        'price_monthly',
        'price_monthly_id',
        'price_quarterly',
        'price_quarterly_id',
        'price_annually',
        'price_annually_id',
        'day_trial',
        'deleted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }
}
