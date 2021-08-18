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

        $users = $user->subscriptions()->with(['plan' => function ($query) {
            $query->with('user');
        }], 'user')->get();

        // dd(DB::getQueryLog());

        return view('livewire.plan.subscription-list', compact('users'));
    }
}
