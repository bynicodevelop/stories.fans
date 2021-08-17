<?php

namespace Database\Factories;

use App\Http\Livewire\Plan\SubscribeButton;
use App\Models\Plan;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSubscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => 1,
            "plan_id" => 1,
            "price_period" => Plan::PRICE_MONTHLY,
            "cancelled" => false,
            "stripe_id" => "sub_12345",
            "trial_ends_at" => 0,
            "ends_at" => 0,
        ];
    }
}
