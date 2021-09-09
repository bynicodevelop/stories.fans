<?php

namespace App\Http\Livewire\Post;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PostCommentsItem extends Component
{
    protected function getListeners()
    {
        return [
            "echo-private:refresh-comments-{$this->post['id']},RefreshCommentsEvent" => 'commentDeleted',
            '$refresh',
        ];
    }

    /**
     * @var Post
     */
    public $post;

    /**
     * @var integer
     */
    public $limitComments = 3;

    /**
     * @var boolean
     */
    public $isUniquePost = false;

    public function commentDeleted($postId)
    {
        if ($this->post['id'] == $postId) {
            $this->emitSelf('$refresh');
        }
    }

    public function render()
    {
        $nComments = $this->post->comments()->count();

        $commentsQuery = $this->post->comments()->with('user')->orderBy('created_at', 'asc');

        if (!$this->isUniquePost) {
            $commentsQuery->take($this->limitComments);
        }

        $comments = $commentsQuery->get();

        return view('livewire.post.post-comments-item', compact('comments', 'nComments'));
    }
}
