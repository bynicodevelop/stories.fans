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

        $data = [
            "id" => $this->model["id"],
            "class" => Post::class
        ];

        switch (get_class($this->model)) {
            case Comment::class:
                $emitTo = 'post.post-comments-item';
                $data["post_id"] = $this->model["post_id"];
                $data["class"] = Comment::class;
                break;
            default:
                // for Post::class
                $emitTo = 'post.item';
                break;
        }

        dispatch(new DeleteContent($this->model));

        $this->emitUp('closeModal');

        $this->emitTo($emitTo, 'contentDeleted', $data);
    }

    public function render()
    {
        return view('livewire.commons.contextual-menu.menus.delete');
    }
}
