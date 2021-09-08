<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Traits\PremiumHelper;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Item extends Component
{
    use PremiumHelper;

    protected $listeners = [
        'contentDeleted',
    ];

    /**
     * @var boolean
     */
    public $deleted = false;

    /**
     * @var Post
     */
    public $post;

    /**
     * @var boolean
     */
    public $isUnique = false;

    public function contentDeleted($data)
    {
        if ($this->post['id'] == $data['id'] && Post::class == $data['class']) {
            Log::info("deleted", [
                "data" => $data,
                "class" => Item::class
            ]);

            $this->deleted = true;
        }
    }

    public function render()
    {
        return view('livewire.post.item');
    }
}
