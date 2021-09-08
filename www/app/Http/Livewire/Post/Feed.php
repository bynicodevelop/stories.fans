<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Feed extends Component
{
    /**
     *
     * @var User $user
     */
    public $user;

    /**
     *
     * @var boolean $finished
     */
    public $finished = false;

    /**
     *
     * @var integer $perPage
     */
    public $perPage = 5;

    /**
     * @var array
     */
    public $posts;

    protected $listeners = [
        '$refresh' => 'refreshFeed',
        'loadMore',
    ];

    private function postRequest()
    {
        return Post::whereIn('user_id', $this->user->getFollowers->map(function ($u) {
            return $u['follow_id'];
        }))->with('media')->orderBy("created_at", "desc")->paginate($this->perPage);
    }

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->posts = [];
    }

    public function loadPosts()
    {
        $posts = $this->postRequest();

        $this->posts = $posts->items();
    }

    public function refreshFeed()
    {
        $this->dispatchBrowserEvent('newPostsLoaded');
    }

    public function loadMore(): void
    {
        $this->perPage += 5;

        $posts = $this->postRequest();

        if ($posts->hasPages()) {
            $this->posts = $posts->items();

            $this->refreshFeed();
        }
    }

    public function render(): View
    {
        return view('livewire.post.feed');
    }
}
