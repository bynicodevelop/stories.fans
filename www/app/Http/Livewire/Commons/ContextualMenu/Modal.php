<?php

namespace App\Http\Livewire\Commons\ContextualMenu;

use Livewire\Component;

class Modal extends Component
{
    protected $listeners = [
        "closeModal"
    ];

    /**
     * @var boolean
     */
    public $openModal = false;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var array
     */
    public $menus = [];

    /**
     * @var boolean
     */
    public $isUniquePost = false;

    public function closeModal()
    {
        $this->openModal = false;
    }

    public function render()
    {
        return view('livewire.commons.contextual-menu.modal');
    }
}
