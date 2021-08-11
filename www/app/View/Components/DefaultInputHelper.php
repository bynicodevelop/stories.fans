<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DefaultInputHelper extends Component
{
    public $message;

    public function __construct($message = null)
    {
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.default-input-helper');
    }
}
