<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AlertComponent extends Component
{
    const SUCCESS = "success";
    const ERROR = "error";
    const WARN = "warn";

    /**
     *
     * @var string
     */
    public $message;

    /**
     *
     * @var boolean
     */
    public $show = false;

    protected $listeners = [
        "showMessage",
        "toggleShow",
    ];

    public function toggleShow()
    {
        $this->show = !$this->show;
    }

    public function showMessage($params)
    {
        $this->show = true;
        $this->message = $params['message'];

        $this->dispatchBrowserEvent('show-alert');
    }

    public function render()
    {
        return view('livewire.alert-component');
    }
}
