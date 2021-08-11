<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ConfirmModal extends Component
{
    /**
     *
     * @var int
     */
    public $id;

    /**
     *
     * @var String
     */
    public $maxWidth;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id = null, $maxWidth = null)
    {
        $this->id = $id;
        $this->maxWidth = $maxWidth;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.confirm-modal');
    }
}
