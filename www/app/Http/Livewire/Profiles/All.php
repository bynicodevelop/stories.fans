<?php

namespace App\Http\Livewire\Profiles;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class All extends Component
{
    /**
     * @var boolean
     */
    public $isLoading = true;

    /**
     * @var integer $perPage
     */
    public $perPage = 10;

    /**
     * @var boolean $finished
     */
    public $finished = false;

    /**
     * @var String $search
     */
    public $search;

    /**
     * @var array
     */
    public $users = [];

    protected $listeners = [
        'loadMore',
    ];

    public function loadMore()
    {
        $this->perPage += 10;

        $this->loadPosts();
    }

    public function loadPosts()
    {
        DB::enableQueryLog();

        if (empty($this->search)) {
            $profiles = User::paginate($this->perPage);
        } else {
            $profiles = User::where('name', 'ilike', "%{$this->search}%")->paginate($this->perPage);
        }

        $this->finished = !$profiles->hasPages();

        $this->users = $profiles->items();

        $this->isLoading = false;
    }

    public function render()
    {
        if (!empty($this->search)) {
            $this->loadPosts();
        }

        return view('livewire.profiles.all');
    }
}
