<?php

namespace App\Http\Livewire\Post;

use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PostCommentMenuContextual extends Component
{
    use AuthorizesRequests;

    /**
     * @var Comment
     */
    public $comment;

    /**
     * @var boolean
     */
    public $openModal = false;

    public function delete()
    {
        $this->authorize('delete', $this->comment);

        $this->comment->delete();

        $this->emitTo('post.post-comments-item', '$refresh');

        $this->openModal = false;
    }

    public function render()
    {
        return view('livewire.post.post-comment-menu-contextual');
    }
}
