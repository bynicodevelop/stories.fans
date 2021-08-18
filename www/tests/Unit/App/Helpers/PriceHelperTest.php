<?php

namespace Tests\Unit\App\Helpers;

use App\Helpers\PriceHelper;
use App\Models\Plan;
use PHPUnit\Framework\TestCase;

class PriceHelperTest extends TestCase
{
    public function test_format_price()
    {
        $results = [
            [
                'result' => PriceHelper::price(1200, Plan::PRICE_MONTHLY),
                'expected' => 12.00,
            ],
            [
                'result' => PriceHelper::price(12000, Plan::PRICE_ANNUALLY),
                'expected' => 10.00,
            ],
            [
                'result' => PriceHelper::price(3000, Plan::PRICE_QUARTERLY),
                'expected' => 10.00,
            ]
        ];

        foreach ($results as $result) {
            $this->assertEquals($result['result'], $result['expected']);
        }
    }

    public function test_calculate_net_price()
    {
        $results = [
            [
                'result' => PriceHelper::netPrice(1000, 30),
                'expected' => 700,
            ],
            [
                'result' => PriceHelper::netPrice(100, 30),
                'expected' => 70,
            ],
            [
                'result' => PriceHelper::netPrice(1800, 30),
                'expected' => 1260,
            ]
        ];

        foreach ($results as $result) {
            $this->assertEquals($result['result'], $result['expected']);
        }
    }
}
