<?php

namespace App\Jobs;

use App\Models\Media;
use App\Models\Post;
use App\Traits\MediaHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MediaHelper;

    private $media;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("List of media", [
            "data" => [
                "files" => $this->media
            ],
            "class" => DeleteMedia::class
        ]);

        foreach ($this->media as $media) {
            $this->deletePrivateFiles($media['name']);
        }

        Log::info("All media deleted");
    }
}
