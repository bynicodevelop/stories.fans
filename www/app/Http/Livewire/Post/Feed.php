<?php

namespace App\Http\Livewire\Post;

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
        /**
         *
         * @var User $user
         */
        $user = Auth::user();

        $followers = $user->feed()->with(['posts' => function ($query) {
            $paginate = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

            $this->finished = !$paginate->hasPages();
        }])->get();

        return view('livewire.post.feed', [
            'posts' => $followers->flatMap->posts
        ]);
    }
}
