<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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


    protected $listeners = [
        'loadMore' => 'loadMore',
        '$refresh' => 'refreshFeed'
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function refreshFeed()
    {
        $this->dispatchBrowserEvent('newPostsLoaded');
    }

    public function loadMore(): void
    {
        $this->perPage += 5;

        $this->refreshFeed();
    }

    public function render(): View
    {
        $posts = Post::whereIn('user_id', $this->user->getFollowers->map(function ($u) {
            return $u['follow_id'];
        }))->with('media')->orderBy("created_at", "desc")->paginate($this->perPage);

        return view('livewire.post.feed', [
            'posts' => $posts->items()
        ]);
    }
}
