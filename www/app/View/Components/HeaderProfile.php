<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HeaderProfile extends Component
{
    /**
     *
     * @var User
     */
    public $user;

    /**
     *
     * @var boolean
     */
    public $showBio;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, $showBio = true)
    {
        $this->user = $user;
        $this->showBio = $showBio;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header-profile');
    }
}
