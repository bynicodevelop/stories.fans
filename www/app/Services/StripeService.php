<?php

namespace App\Services;

use App\Exceptions\InvalidPriceId;
use App\Exceptions\InvalidPriceIdException;
use App\Exceptions\InvalidStripeIdException;
use App\Exceptions\InvalidStripeServiceException;


class StripeService
{
    private $stripe;

    public function __construct($stripe)
    {
        if (empty($stripe)) {
            throw new InvalidStripeServiceException();
        }

        $this->stripe = $stripe;
    }

    public function createSubscription($stripeId, $priceId, $trialPeriod = null): array
    {
        if (empty($stripeId)) {
            throw new InvalidStripeIdException();
        }

        if (empty($priceId)) {
            throw new InvalidPriceIdException();
        }

        $subscriptionParams = [
            'customer' => $stripeId,
            'items' => [
                ['price' => $priceId],
            ],
        ];

        if (!empty($trialPeriod)) {
            $subscriptionParams['trial_period_days'] = $trialPeriod;
        }

        $subscription = $this->stripe->subscriptions->create($subscriptionParams);

        return [
            "id" => $subscription["id"],
            "current_period_end" => $subscription["current_period_end"],
        ];
    }
}
