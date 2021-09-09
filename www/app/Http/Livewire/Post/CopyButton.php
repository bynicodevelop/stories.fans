<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use Livewire\Component;

class CopyButton extends Component
{
    /**
     * @var Post
     */
    public $post;

    /**
     * @var boolean
     */
    public $isUniquePost = false;

    public function render()
    {
        $hasCopy = false;

        return view('livewire.post.copy-button', compact('hasCopy'));
    }
}
