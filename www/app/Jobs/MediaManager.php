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

    /**
     *
     * @var User
     */
    private $user;

    private $mediaType;

    private $name;

    private $ext;

    private $content;

    private $isPremium;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $content, $isPremium, $mediaType, $name, $ext)
    {
        $this->user = $user;
        $this->content = $content;
        $this->isPremium = $isPremium;
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
            $storagePath = storage_path("app/{$path}"); // Storage::disk('local')->get($path);

            Log::info("Run traitement image", [
                "path" => $storagePath
            ]);

            extract($this->imageStorage($storagePath, $name));
        } else if ($this->mediaType == Media::VIDEO) {
            Log::info("Run traitement video", [
                "path" => $path
            ]);

            extract($this->videoStorage($path, $name));
        }

        Log::debug("Create new post");

        $post = $this->user->posts()->create([
            "content" => empty($this->content) ? null : $this->content,
            "is_premium" => $this->isPremium,
        ]);

        Log::debug("Post created", [
            "post_id" => $post["id"]
        ]);

        if ($this->mediaType != Media::POST) {
            Log::debug("Create new media");

            Log::debug("Orientation file: ", [
                "orientation" => $orientation
            ]);

            $post->media()->create([
                "type" => $this->mediaType,
                "name_preview" => $preview,
                "name" => $name,
                "ext" => $ext,
                "orientation" => $orientation,
            ]);
        }

        Log::debug("Delete temporary file", [
            "path" => $path
        ]);

        if (Storage::disk('local')->exists("tmp/{$this->name}")) {
            Storage::disk('local')->delete("tmp/{$this->name}");
        }

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

        $format = new X264();

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

        // Create stream to send image from file
        $fullImage = $image->stream()->detach();

        $image = Image::make($storagePath)->orientate();

        $image->resize(1080, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Create stream to send image from file
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
