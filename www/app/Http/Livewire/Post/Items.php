<?php

namespace App\Http\Livewire\Post;

use App\Models\User;
use Livewire\Component;

class Items extends Component
{
    /**
     *
     * @var integer
     */
    public $perPage = 5;

    /**
     *
     * @var boolean
     */
    public $finished = false;

    /**
     *
     * @var User
     */
    public $user;

    /**
     * @var array
     */
    public $posts;

    protected $listeners = [
        "echo-private:refresh-posts-deleted,RefreshPostsEvent" => 'newPostDeleted',
        'loadMore' => 'loadMore',
        '$refresh'
    ];

    private function postRequest()
    {
        return $this->user->posts()
            ->orderBy("created_at", 'desc')
            ->with('user', 'media')
            ->paginate($this->perPage);
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->posts = [];
    }

    public function newPostDeleted()
    {
        $this->emitTo('alert-component', 'showMessage', [
            "message" => "post.deleted"
        ]);

        $this->refreshFeed();
    }

    public function loadPosts()
    {
        $posts = $this->postRequest();

        $this->posts = $posts->items();

        $this->refreshFeed();
    }

    public function loadMore()
    {
        if ($this->finished) {
            return;
        }

        $this->perPage += 5;

        $posts = $this->postRequest();

        $this->posts = $posts->items();

        if (!$posts->hasPages()) {
            $this->finished = true;
        }

        $this->refreshFeed();
    }

    public function refreshFeed()
    {
        $this->dispatchBrowserEvent('newPostsLoaded');
    }

    public function render()
    {
        return view('livewire.post.items');
    }
}
