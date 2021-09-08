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

    protected $listeners = [
        "echo-private:refresh-posts-deleted,RefreshPostsEvent" => 'newPostDeleted',
        'loadMore' => 'loadMore',
        '$refresh'
    ];

    public function newPostDeleted()
    {
        $this->emitTo('alert-component', 'showMessage', [
            "message" => "post.deleted"
        ]);
    }

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function loadMore()
    {
        $this->perPage += 5;

        $this->dispatchBrowserEvent('newPostsLoaded');
    }

    public function render()
    {
        $paginate = $this->user->posts()->orderBy("created_at", 'desc')->with('user', 'media')->paginate($this->perPage);

        $this->finished = !$paginate->hasPages();

        return view('livewire.post.items', ["posts" => $paginate->items()]);
    }
}
