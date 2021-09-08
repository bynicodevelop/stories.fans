<?php

namespace App\Http\Livewire\Commons\ContextualMenu\Menus;

use App\Jobs\DeleteContent;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Delete extends Component
{
    use AuthorizesRequests;

    /**
     * @var Model
     */
    public $model;

    public function delete()
    {
        $this->authorize('delete', $this->model);

        dispatch(new DeleteContent($this->model))->onQueue('media');

        $this->emitUp('closeModal');

        if (get_class($this->model) == Post::class) {
            $this->emitTo('post.item', 'isDeleted', $this->model);
        }
    }

    public function render()
    {
        return view('livewire.commons.contextual-menu.menus.delete');
    }
}
