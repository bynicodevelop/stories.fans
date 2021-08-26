<?php

namespace App\Jobs;

use App\Models\Media;
use App\Models\Post;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $media;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($media)
    {
        Log::debug($media);
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug($this->media);

        foreach ($this->media as $media) {
            $listPathes = [
                "private/{$media['name']}.{$media['ext']}",
                "private/{$media['name']}-full.{$media['ext']}",
                "private/{$media['name']}-preview.{$media['ext']}",
            ];

            foreach ($listPathes as $path) {
                if (Storage::disk('spaces')->exists($path)) {
                    Storage::disk('spaces')->delete($path);
                }
            }
        }

        Log::debug("All media deleted");
    }
}
