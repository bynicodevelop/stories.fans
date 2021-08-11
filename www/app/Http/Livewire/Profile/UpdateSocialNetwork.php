<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateSocialNetwork extends Component
{
    /**
     *
     * @var String
     */
    public $instagram;

    /**
     *
     * @var String
     */
    public $facebook;

    /**
     *
     * @var String
     */
    public $youtube;

    /**
     *
     * @var String
     */
    public $tiktok;

    /**
     *
     * @var String
     */
    public $snapchat;

    /**
     *
     * @var String
     */
    public $twitter;

    /**
     *
     * @var User
     */
    public $user;

    public function mount()
    {
        $this->user = Auth::user();

        $this->instagram = $this->user['instagram'];
        $this->facebook = $this->user['facebook'];
        $this->youtube = $this->user['youtube'];
        $this->tiktok = $this->user['tiktok'];
        $this->snapchat = $this->user['snapchat'];
        $this->twitter = $this->user['twitter'];
    }

    public function updateSocialNetwork()
    {
        $this->user->update([
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'youtube' => $this->youtube,
            'tiktok' => $this->tiktok,
            'snapchat' => $this->snapchat,
            'twitter' => $this->twitter,
        ]);

        $this->emit('saved');
    }

    public function render()
    {
        return view('profile.update-social-network');
    }
}
