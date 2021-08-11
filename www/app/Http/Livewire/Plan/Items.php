<?php

namespace App\Http\Livewire\Plan;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Items extends Component
{
    /**
     *
     * @var boolean $confirmingPlanDeletion
     */
    public $confirmingPlanDeletion = false;

    /**
     *
     * @var int $itemId
     */
    public $itemId;

    protected $listeners = ['$refresh'];

    public function confirmDeletion($id): void
    {
        $this->confirmingPlanDeletion = true;
        $this->itemId = $id;
    }

    public function delete(): void
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->plans()->find($this->itemId)->update([
            'deleted' => true
        ]);

        $this->confirmingPlanDeletion = false;
    }

    public function render()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $plans = $user->plans()
            ->orderBy('deleted', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.plan.items', compact('plans'));
    }
}
