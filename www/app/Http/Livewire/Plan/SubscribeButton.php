<?php

namespace App\Http\Livewire\Plan;

use App\Exceptions\InvalidStripeIdException;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\StripeService;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Stripe\StripeClient;

class SubscribeButton extends Component
{
    public const MONTHLY = 'month';

    public const QUARTERLY = 'quarterly';

    public const ANNUALLY = 'year';

    /**
     *
     * @var Plan $plan
     */
    public $plan;

    /**
     *
     * @var String
     */
    public $period;

    public function subscribe()
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $stripeService = new StripeService($stripe);

        /**
         * @var User $user
         */
        $user = Auth::user();

        $priceId = $this->plan[Plan::PRICE_MONTHLY_ID];
        $pricePeriod = Plan::PRICE_MONTHLY;

        if ($this->period == self::QUARTERLY) {
            $priceId = $this->plan[Plan::PRICE_QUARTERLY_ID];
            $pricePeriod = Plan::PRICE_QUARTERLY;
        } elseif ($this->period == self::ANNUALLY) {
            $priceId = $this->plan[Plan::PRICE_ANNUALLY_ID];
            $pricePeriod = Plan::PRICE_ANNUALLY;
        }

        $userSubscription = UserSubscription::where('plan_id', $this->plan['id'])
            ->where('user_id', $user['id'])
            ->where('price_period', $pricePeriod)
            ->first();

        $userToFollow = User::find($this->plan['user_id']);

        if (!$user->isFollowed($userToFollow)) {
            $user->follow($userToFollow);
        }

        if ($userSubscription != null) {
            $stripe->subscriptions->update(
                $userSubscription['stripe_id'],
                [
                    "cancel_at_period_end" => false,
                ]
            );

            $userSubscription->update([
                'cancelled' => false,
            ]);

            $this->dispatchBrowserEvent('banner-message', [
                'style' => 'success',
                'message' => __('plan.subscribed-renew')
            ]);
        } else {
            try {
                $subscription = $stripeService->createSubscription(
                    $user->stripe_id,
                    $priceId,
                    !empty($this->plan['day_trial']) ? $this->plan['day_trial'] : null
                );
            } catch (InvalidStripeIdException $e) {
                session()->flash('flash.banner', __('plan.register-card-before-subscribe'));

                return redirect()->route('card');
            }


            // $subscriptionParams = [
            //     'customer' => $user->stripe_id,
            //     'items' => [
            //         ['price' => $priceId],
            //     ],
            // ];

            // if (!empty($this->plan['day_trial'])) {
            //     $subscriptionParams['trial_period_days'] = $this->plan['day_trial'];
            // }

            // $subscription = $stripe->subscriptions->create($subscriptionParams);

            $date = new DateTime();

            $subscriptionData = [
                'user_id' => $user['id'],
                'plan_id' => $this->plan['id'],
                'price_period' => $pricePeriod,
                'stripe_id' => $subscription['id'],
                'ends_at' => $date->setTimestamp($subscription['current_period_end']),
            ];

            if (!empty($subscription['trial_end'])) {
                $subscriptionData['trial_ends_at'] = $date->setTimestamp($subscription['trial_end']);
            }

            UserSubscription::create($subscriptionData);

            $this->dispatchBrowserEvent('banner-message', [
                'style' => 'success',
                'message' => __('plan.subscribed')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.plan.subscribe-button');
    }
}
