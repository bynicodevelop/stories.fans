<?php

namespace App\Http\Livewire\Invitation;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InvitedLink extends Component
{
    public $link;

    public $listeners = ['saved'];

    public function saved()
    {
        $this->emit('saved');
    }

    public function render()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $linkModel = $user->invitationLinks()->first();

        $this->link = asset("/i/{$linkModel['hash']}");

        return view('invitation.invited-link');
    }
}
