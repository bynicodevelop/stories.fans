<?php

namespace App\Http\Livewire\Post;

use App\Models\Post;
use App\Traits\PremiumHelper;
use Livewire\Component;

class Item extends Component
{
    use PremiumHelper;

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
    public $isUniquePost = false;

    protected function getListeners()
    {
        return [
            'isDeleted',
        ];
    }

    public function isDeleted($data)
    {
        if ($data['id'] == $this->post['id']) {
            $this->deleted = true;
        }
    }

    public function render()
    {
        return view('livewire.post.item');
    }
}
