<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;
use phpDocumentor\Reflection\Types\Boolean;

class CardFooterComponent extends Component
{
    /**
     *
     * @var Post
     */
    public $post;

    public $isUnique = false;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Post $post, bool $isUnique)
    {
        $this->post = $post;
        $this->isUnique = $isUnique;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card.card-footer-component');
    }
}
