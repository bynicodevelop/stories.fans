<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use ContentRequiresException;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostComment extends Component
{
    /**
     * @var Post
     */
    public $post;

    /**
     * @var string
     */
    public $comment;


    /**
     * @var boolean
     */
    public $isUnique = false;

    /**
     * @var boolean
     */
    public $isDisabled = true;

    public function updatedComment($value)
    {
        $this->isDisabled = true;

        if (!empty($value)) {
            $this->isDisabled = false;
        }
    }

    public function sendComment()
    {
        if (empty($this->comment)) {
            throw new ContentRequiresException();
        }

        $this->post->comments()->create([
            "comment" => $this->comment,
            "user_id" => Auth::id(),
        ]);

        $this->emitTo('post.post-comments-item', '$refresh');

        $this->comment = "";
        $this->isDisabled = true;
    }

    public function render()
    {
        return view('livewire.post.post-comment');
    }
}
