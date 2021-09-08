<?php

namespace App\Events;

use App\Http\Livewire\Commons\ContextualMenu\Menus\Delete;
use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefreshPostsEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    const CREATED = "created";

    const DELETED = "deleted";

    public $post;

    public $type;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post, string $type)
    {
        $this->post = $post;

        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $event = "refresh-posts-{$this->type}";

        // if ($this->type == self::DELETED) {
        //     $event = "refresh-posts-{$this->type}-{$this->post['id']}";
        // }

        return new PrivateChannel($event);
    }
}
