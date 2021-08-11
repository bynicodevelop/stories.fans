<?php

namespace App\Http\Livewire\Profiles;

use Livewire\Component;

class CountLike extends Component
{
    /**
     *
     * @var \App\Models\User $user
     */
    public $user;

    protected $listeners = ['$refresh'];

    public function render()
    {
        $nbLikes = $this->user->posts()->withCount('postLiked')->get()->sum('post_liked_count');

        return view('livewire.profiles.count-like', compact('nbLikes'));
    }
}
