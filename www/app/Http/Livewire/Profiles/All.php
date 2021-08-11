<?php

namespace App\Http\Livewire\Profiles;

use App\Models\User;
use Livewire\Component;

class All extends Component
{
    /**
     *
     * @var integer $perPage
     */
    public $perPage = 10;

    /**
     *
     * @var boolean $finished
     */
    public $finished = false;


    /**
     *
     * @var String $search
     */
    public $search;

    protected $listeners = [
        'loadMore' => 'loadMore',
    ];

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function render()
    {
        if (empty($this->search)) {
            $paginate = User::paginate($this->perPage);
        } else {
            $paginate = User::where('name', 'ilike', "%{$this->search}%")->paginate($this->perPage);
        }

        $this->finished = !$paginate->hasPages();

        return view('livewire.profiles.all', [
            "users" => $paginate->items(),
        ]);
    }
}
