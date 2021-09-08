<?php

namespace App\Http\Livewire\Post;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PostCommentsItem extends Component
{
    protected $listeners = [
        '$refresh',
        'contentDeleted'
    ];

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
    public $isUnique = false;

    public function contentDeleted($data)
    {
        if ($this->post['id'] == $data['post_id'] && Comment::class == $data['class']) {
            // Log::info("deleted", [
            //     "data" => $data,
            //     "class" => Item::class
            // ]);

            // $this->deleted = true;
            $this->emitSelf('$refresh');
        }
    }

    public function render()
    {
        $nComments = $this->post->comments()->count();

        $commentsQuery = $this->post->comments()->with('user')->orderBy('created_at', 'asc');

        if (!$this->isUnique) {
            $commentsQuery->take($this->limitComments);
        }

        $comments = $commentsQuery->get();

        return view('livewire.post.post-comments-item', compact('comments', 'nComments'));
    }
}
