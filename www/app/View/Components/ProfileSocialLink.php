<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;

class ProfileSocialLink extends Component
{
    /**
     *
     * @var User
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.profile.social-link');
    }
}
