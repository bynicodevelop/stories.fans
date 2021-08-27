<?php

namespace App\Http\Livewire\Post;

use App\Models\Media;
use App\Models\Post;
use Livewire\Component;

class VideoItem extends Component
{
    /**
     * @var Post
     */
    public $post;

    /**
     * @var Media
     */
    public $media;

    /**
     * @var boolean
     */
    public $play = false;

    public function render()
    {
        return view('livewire.post.video-item');
    }
}
