<?php

namespace App\Http\Livewire\Post;

use ContentRequiresException;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Editor extends Component
{
    /**
     *
     * @var String $content
     */
    public $content;

    /**
     *
     * @var boolean $isPremium
     */
    public $isPremium = false;

    /**
     *
     * @var boolean $isDisabled
     */
    public $isDisabled = false;

    /**
     *
     * @var User $user
     */
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function post()
    {
        if (empty($this->content)) {
            throw new ContentRequiresException();
        }

        $this->user->posts()->create([
            "content" => $this->content,
            "is_premium" => $this->isPremium,
        ]);

        $this->content = "";
        $this->isPremium = false;

        $this->emitTo('post.feed', '$refresh');
    }

    public function isDisabled()
    {
        if (empty($this->content)) {
            $this->isDisabled = true;
            return;
        }

        $this->isDisabled = false;
    }

    public function render()
    {
        $this->isDisabled();

        $havePlan = $this->user->plans()->count() > 0;

        return view('livewire.post.editor', compact('havePlan'));
    }
}
