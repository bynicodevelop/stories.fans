<?php

namespace App\Jobs;

use App\Events\RefreshCommentsEvent;
use App\Events\RefreshPostsEvent;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Model
     */
    private $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $classModel = get_class($this->model);

        Log::info("Delete content", [
            "data" => ["model" => $this->model],
            "class_model" => $classModel,
            "class" => DeleteContent::class
        ]);

        if ($classModel == Post::class) {
            if ($this->model->media()->count()) {
                dispatch(new DeleteMedia($this->model->media->toArray()))->onQueue('media');
            }
        }

        $this->model->delete();

        if ($classModel == Comment::class) {
            Log::info("Send event", [
                "class" => RefreshCommentsEvent::class
            ]);

            event(new RefreshCommentsEvent($this->model));
        }

        if ($classModel == Post::class) {
            Log::info("Send event", [
                "class" => RefreshPostsEvent::class
            ]);

            event(new RefreshPostsEvent($this->model, RefreshPostsEvent::DELETED));
        }
    }
}
