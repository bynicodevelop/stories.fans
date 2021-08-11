<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LikeButton extends Component
{
    /**
     *
     * @var Post
     */
    public $post;

    /**
     *
     * @var integer
     */
    public $nbLikes = 0;

    /**
     *
     * @var User
     */
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function toggleLike()
    {
        $this->post->likes()->toggle([$this->user['id']]);

        $this->emitTo('profiles.count-like', '$refresh');
    }

    public function render()
    {
        $this->nbLikes = $this->post->countLikes();
        $hasLike = $this->user->hasLikedPost($this->post);

        return view('livewire.post.like-button', compact('hasLike'));
    }
}
