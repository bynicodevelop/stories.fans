<?php

namespace App\View\Components;

use App\Models\Post;
use App\Models\User;
use Illuminate\View\Component;

class CardHeaderComponent extends Component
{
    /**
     *
     * @var Post
     */
    public $post;

    /**
     *
     * @var User
     */
    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.card-header-component');
    }
}
