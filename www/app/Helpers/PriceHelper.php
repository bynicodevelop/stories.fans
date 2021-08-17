<?php

namespace App\Helpers;

use App\Models\Plan;

class PriceHelper
{
    public static function price($price, $period)
    {
        $delta = 1;

        if ($period == Plan::PRICE_QUARTERLY) {
            $delta = 3;
        } else if ($period == Plan::PRICE_ANNUALLY) {
            $delta = 12;
        }

        return number_format((intval($price) / 100) / $delta, 2);
    }

    public static function netPrice(int $price, int $fee)
    {
        return $price - $price * $fee / 100;
    }
}
