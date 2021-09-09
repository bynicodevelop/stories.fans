<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Feed extends Component
{
    public $isLoading = true;

    /**
     * @var boolean $finished
     */
    public $finished = false;

    /**
     * @var integer $perPage
     */
    public $perPage = 5;

    /**
     * @var User $user
     */
    public $user;

    /**
     * @var array $posts
     */
    public $posts;

    protected $listeners = [
        "echo-private:refresh-posts-created,RefreshPostsEvent" => 'newPostCreated',
        "echo-private:refresh-posts-deleted,RefreshPostsEvent" => 'newPostDeleted',
        '$refresh' => 'refreshFeed',
        'loadMore',
    ];

    private function postRequest()
    {
        return Post::whereIn('user_id', $this->user->getFollowers->map(function ($u) {
            return $u['follow_id'];
        }))
            ->with('media')
            ->orderBy("created_at", "desc")
            ->paginate($this->perPage);
    }

    public function mount(User $user): void
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

    public function newPostCreated($data)
    {
        $post = Post::where("id", $data["post"]["id"])->with("media")->first();

        array_unshift($this->posts, $post);

        $this->refreshFeed();
    }

    public function loadPosts()
    {
        $posts = $this->postRequest();

        $this->posts = $posts->items();

        $this->isLoading = false;

        $this->refreshFeed();
    }

    public function loadMore(): void
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

    public function render(): View
    {
        return view('livewire.post.feed');
    }
}
