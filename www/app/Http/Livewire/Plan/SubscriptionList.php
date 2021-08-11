<?php

namespace App\Http\Livewire\Plan;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscriptionList extends Component
{
    public $listeners = ['$refresh'];

    public function render()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        // DB::enableQueryLog();

        // $users = $user->subscriptions()->with('plan')->where('cancelled', false)->get()->map(function ($userSubscription) {
        $users = $user->subscriptions()->with('plan')->get()->map(function ($userSubscription) {
            return [
                'userSubscription' => $userSubscription,
                'plan' => $userSubscription->plan,
                'user' => $userSubscription->plan()->with('user')->first()->user
            ];
        });

        // dd(DB::getQueryLog());

        return view('livewire.plan.subscription-list', compact('users'));
    }
}
