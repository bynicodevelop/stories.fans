<?php

namespace App\Http\Livewire\Plan;

use App\Mail\CancelledSubscriptionMail;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Stripe\StripeClient;

class UnsubscribeButton extends Component
{
    /**
     *
     * @var UserSubscription $subscription
     */
    public $subscription;

    public $cancelled;

    public function unsubscribe()
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $stripe->subscriptions->update(
            $this->subscription['stripe_id'],
            [
                "cancel_at_period_end" => true,
            ]
        );

        $this->subscription->update([
            'cancelled' => true,
        ]);

        $this->cancelled = $this->subscription['cancelled'];

        Mail::to($this->subscription['user'])
            ->queue((new CancelledSubscriptionMail($this->subscription['user'], $this->subscription['plan']['user']))
                ->onQueue('email'));

        $this->emitTo('plan.subscription-list', '$refresh');

        $this->dispatchBrowserEvent('banner-message', [
            'style' => 'success',
            'message' => __('plan.cancelled')
        ]);
    }

    public function render()
    {
        $this->cancelled = $this->subscription['cancelled'];

        return view('livewire.plan.unsubscribe-button');
    }
}
