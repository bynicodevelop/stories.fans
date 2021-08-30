<?php

namespace App\Jobs;

use App\Events\PostCreatedEvent;
use App\Models\Media;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class MediaManager implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const LANDSCAPE = "landscape";

    const PORTRAIT = "portrait";

    private $post;

    private $mediaType;

    private $name;

    private $ext;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($post, $mediaType, $name, $ext)
    {
        $this->post = $post;
        $this->mediaType = $mediaType;
        $this->name = $name;
        $this->ext = $ext;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $name = md5($this->name . microtime());

        $path = "tmp/{$this->name}";

        Log::debug("Media uploaded info:", [
            "path" => $path,
            "mediaType" => $this->mediaType,
        ]);

        if ($this->mediaType == Media::IMAGE) {
            $storagePath = Storage::disk('local')->get($path);

            extract($this->imageStorage($storagePath, $name));
        } else {
            extract($this->videoStorage($path, $name));
        }

        Log::debug("Orientation file: ", [
            "orientation" => $orientation
        ]);

        $this->post->media()->create([
            "type" => $this->mediaType,
            "name_preview" => $preview,
            "name" => $name,
            "ext" => $ext,
            "orientation" => $orientation,
        ]);

        Log::debug("Delete temporary file", [
            "path" => $path
        ]);

        Storage::disk('local')->delete("tmp/{$this->name}");

        event(new PostCreatedEvent());
    }

    private function getOrientation(int $width, int $height): string
    {
        return $width > $height ?  self::LANDSCAPE : self::PORTRAIT;
    }

    private function videoStorage($storagePath, $name): array
    {
        $media = FFMpeg::fromDisk('local')
            ->open($storagePath);

        $videoDuration = $media->getDurationInSeconds();
        $videoDimensions = $media->getVideoStream()
            ->getDimensions();

        $media->getFrameFromSeconds($videoDuration * .33)
            ->export()
            ->toDisk(config('filesystems.default'))
            ->save("private/{$name}-preview.jpg");

        $format = new X264('libmp3lame', 'libx264');

        $media = FFMpeg::fromDisk('local')
            ->open($storagePath);

        $media
            ->export()
            ->toDisk(config('filesystems.default'))
            ->inFormat($format)
            ->save("private/{$name}-full.mp4");

        $media
            ->export()
            ->toDisk(config('filesystems.default'))
            ->inFormat($format)
            ->save("private/{$name}.mp4");

        return [
            "orientation" => $this->getOrientation($videoDimensions->getWidth(), $videoDimensions->getHeight()),
            "ext" => "mp4",
            "preview" => "{$name}-preview.jpg",
        ];
    }

    private function imageStorage($storagePath, $name): array
    {
        $image = Image::make($storagePath)->orientate();

        $fullImage = $image->stream()->detach();

        $image = Image::make($storagePath)->orientate();

        $image->resize(1080, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $stdImage = $image->stream()->detach();

        Storage::put("private/{$name}-full.{$this->ext}", $fullImage);
        Storage::put("private/{$name}.{$this->ext}", $stdImage);

        return [
            "orientation" => $this->getOrientation($image->width(), $image->height()),
            "ext" => $this->ext,
            "preview" => null,
        ];
    }
}
