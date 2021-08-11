<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MenuContextual extends Component
{
    /**
     *
     * @var boolean $confirmingPostDeletion
     */
    public $confirmingPostDeletion = false;

    /**
     *
     * @var Post
     */
    public $post;

    /**
     * @var User
     */
    public $user;

    protected $listeners = ['copied'];

    public function copied()
    {
        $this->dispatchBrowserEvent('banner-message', [
            'style' => 'success',
            'message' => __('contextual-menu.copied')
        ]);
    }

    public function delete()
    {
        $this->post->delete();

        $this->emitTo('post.items', '$refresh');
        $this->emitTo('profiles.count-post', '$refresh');
        $this->emitTo('profiles.count-like', '$refresh');

        $this->confirmingPostDeletion = false;
    }

    public function render()
    {
        $authUser = Auth::user();

        return view('livewire.post.menu-contextual', compact('authUser'));
    }
}
