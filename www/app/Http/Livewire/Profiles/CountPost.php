<?php

namespace App\Http\Livewire\Profiles;

use Livewire\Component;

class CountPost extends Component
{
    /**
     *
     * @var \App\Models\User $user
     */
    public $user;

    protected $listeners = ['$refresh'];

    public function render()
    {
        $nbPosts = $this->user->posts()->count();

        return view('livewire.profiles.count-post', compact('nbPosts'));
    }
}
