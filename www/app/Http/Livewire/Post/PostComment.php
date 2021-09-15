<?php

namespace App\Http\Livewire\Post;

use App\Jobs\NewCommentPosted;
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
     * Is used to differenciate post in the feed
     * Or unique post
     * 
     * @var boolean
     */
    public $isUniquePost = false;

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

    public function clear(): void
    {
        $this->comment = null;
        $this->isDisabled = true;
    }

    public function sendComment(): void
    {
        if (empty($this->comment)) {
            $this->emitTo('alert-component', 'showMessage', [
                "message" => "post.required-comment"
            ]);

            return;
        }

        $newCommentPosted = new NewCommentPosted($this->post);

        $newCommentPosted->handle();

        $this->post->comments()->create([
            "comment" => $this->comment,
            "user_id" => Auth::id(),
        ]);

        $this->emitTo('post.post-comments-item', '$refresh');

        $this->clear();
    }

    public function render()
    {
        return view('livewire.post.post-comment');
    }
}
