<?php

namespace App\Http\Livewire\Profiles;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class FollowBtn extends Component
{
    /**
     *
     * @var boolean $showAddPaymentMethod
     */
    public $showAddPaymentMethod = false;

    /**
     * @var String $label
     */
    public $label = "Follow";

    /**
     * @var \App\Models\User $user
     */
    public $user;

    /**
     * @var \App\Models\User $user
     */
    public $authUser;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->authUser = Auth::user();
    }

    public function addPaymentMethod(): void
    {
        $this->showAddPaymentMethod = true;
    }

    public function toggleFollow(): void
    {
        $this->authUser->follow($this->user);
    }

    public function render(): View
    {
        $this->label = $this->authUser->isFollowed($this->user) ? "profile.unfollow" : "profile.follow";

        return view('livewire.profiles.follow-btn');
    }
}
