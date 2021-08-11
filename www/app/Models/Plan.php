<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    const NAME = 'name';
    const CONTENT = 'content';

    const PRICE_MONTHLY = 'price_monthly';
    const PRICE_MONTHLY_ID = 'price_monthly_id';

    const PRICE_QUARTERLY = 'price_quarterly';
    const PRICE_QUARTERLY_ID = 'price_quarterly_id';

    const PRICE_ANNUALLY = 'price_annually';
    const PRICE_ANNUALLY_ID = 'price_annually_id';

    const DAY_TRIAL = 'day_trial';

    const DELETED = 'deleted';

    use HasFactory;

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
}
