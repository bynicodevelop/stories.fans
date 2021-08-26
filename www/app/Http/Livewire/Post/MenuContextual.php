<?php

namespace App\Http\Livewire\Post;

use App\Jobs\DeleteMedia;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        Log::debug("Delete post", [
            "post" => $this->post
        ]);

        $media = $this->post->media()->get()->toArray();

        dispatch(new DeleteMedia($media))->onQueue('media');

        $this->post->delete();

        $this->emitTo('profiles.count-post', '$refresh');
        $this->emitTo('profiles.count-like', '$refresh');
        $this->emitTo('post.items', '$refresh');
        $this->emitTo('post.feed', '$refresh');

        $this->confirmingPostDeletion = false;
    }

    public function render()
    {
        $authUser = Auth::user();

        return view('livewire.post.menu-contextual', compact('authUser'));
    }
}
