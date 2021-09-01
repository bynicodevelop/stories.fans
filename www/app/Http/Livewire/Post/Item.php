<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Traits\PremiumHelper;
use Livewire\Component;

class Item extends Component
{
    use PremiumHelper;

    /**
     * @var Post
     */
    public $post;

    /**
     * @var boolean
     */
    public $isUnique = false;

    public function render()
    {
        return view('livewire.post.item');
    }
}
