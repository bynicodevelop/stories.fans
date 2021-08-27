<?php

namespace Tests\Unit\App\Services;

use App\Exceptions\InvalidPriceIdException;
use App\Exceptions\InvalidStripeIdException;
use App\Exceptions\InvalidStripeServiceException;
use App\Services\StripeService;
use Mockery;
use PHPUnit\Framework\TestCase;
use Stripe\Service\SubscriptionService;
use Stripe\StripeClient;

class StripeServiceTest extends TestCase
{
    public function test_stripe_instanciate()
    {
        $this->expectException(InvalidStripeServiceException::class);

        new StripeService(null);
    }

    public function test_check_stripe_id_exists()
    {
        $this->expectException(InvalidStripeIdException::class);

        $mock = $this->getMockClass(StripeClient::class);
        $stripeService = new StripeService($mock);

        $stripeService->createSubscription(0, "");
    }

    public function test_check_price_id_exists()
    {
        $this->expectException(InvalidPriceIdException::class);

        $mock = $this->getMockClass(StripeClient::class);
        $stripeService = new StripeService($mock);

        $stripeService->createSubscription(1, "");
    }

    public function test_create_subscription()
    {
        $subscriptionParams = [
            'customer' => 1,
            'items' => [
                ['price' => "price_test"],
            ],
        ];

        /**
         * @var Mock $subscriptionsMock
         */
        $subscriptionsMock = Mockery::mock(SubscriptionService::class);

        $subscriptionsMock->shouldReceive('create')->once()->with($subscriptionParams)->andReturn([
            "id" => "sub_K0VFxSzJBZOeEA",
            "current_period_end" => 1629104026,
        ]);

        /**
         * @var Mock $mock
         */
        $mock = $this->createMock(StripeClient::class);

        $mock->method('__get')
            ->with($this->equalTo('subscriptions'))
            ->will($this->returnValue($subscriptionsMock));

        $stripeService = new StripeService($mock);

        $subscriptions = $stripeService->createSubscription($subscriptionParams['customer'], $subscriptionParams['items'][0]['price']);

        $this->assertEquals($subscriptions['id'], "sub_K0VFxSzJBZOeEA");
        $this->assertEquals($subscriptions['current_period_end'], 1629104026);

        Mockery::close();
    }

    public function test_create_subscription_with_trial_period()
    {
        $subscriptionParams = [
            'customer' => 1,
            'items' => [
                ['price' => "price_test"],
            ],
            'trial_period_days' => 7,
        ];

        /**
         * @var Mock $subscriptionsMock
         */
        $subscriptionsMock = Mockery::mock(SubscriptionService::class);

        $subscriptionsMock->shouldReceive('create')->once()->with($subscriptionParams)->andReturn([
            "id" => "sub_K0VFxSzJBZOeEA",
            "current_period_end" => 1629104026,
        ]);

        /**
         * @var Mock $mock
         */
        $mock = $this->createMock(StripeClient::class);

        $mock->method('__get')
            ->with($this->equalTo('subscriptions'))
            ->will($this->returnValue($subscriptionsMock));

        $stripeService = new StripeService($mock);

        $subscriptions = $stripeService->createSubscription($subscriptionParams['customer'], $subscriptionParams['items'][0]['price'], $subscriptionParams['trial_period_days']);

        $this->assertEquals($subscriptions['id'], "sub_K0VFxSzJBZOeEA");
        $this->assertEquals($subscriptions['current_period_end'], 1629104026);

        Mockery::close();
    }
}
