<?php

namespace App\Http\Livewire\Commons\ContextualMenu\Menus;

use Livewire\Component;

class Share extends Component
{
    protected $listeners = ['copied'];

    /**
     * @var Model
     */
    public $model;

    /**
     * @var boolean
     */
    public $isUniquePost = false;

    public function copied()
    {
        $this->emitTo('alert-component', 'showMessage', [
            "message" => "contextual-menu.copied"
        ]);

        $this->emitUp("closeModal");
    }

    public function render()
    {
        return view('livewire.commons.contextual-menu.menus.share');
    }
}
