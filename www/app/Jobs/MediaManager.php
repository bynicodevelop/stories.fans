<?php

namespace App\Jobs;

use App\Events\PostCreatedEvent;
use App\Models\Media;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

        $storagePath = storage_path("app/tmp/{$this->name}");

        if ($this->mediaType == Media::IMAGE) {
            extract($this->imageStorage($storagePath, $name));
        } else {
            extract($this->videoStorage($storagePath, $name));
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

        Storage::disk('local')->delete("tmp/{$this->name}");

        event(new PostCreatedEvent());
    }

    private function getOrientation(int $width, int $height): string
    {
        return $width > $height ?  self::LANDSCAPE : self::PORTRAIT;
    }

    private function videoStorage($storagePath, $name): array
    {
        $ffprobe = FFProbe::create();

        $videoDimensions = $ffprobe
            ->streams($storagePath)
            ->videos()
            ->first()
            ->getDimensions();

        $videoDuration = $ffprobe
            ->streams($storagePath)
            ->videos()
            ->first()
            ->get('duration');

        $previewTime = $videoDuration * .33;

        $ffmpeg = FFMpeg::create();

        /**
         * @var Video
         */
        $video = $ffmpeg->open($storagePath);

        $frame = $video->frame(TimeCode::fromSeconds($previewTime));

        $pathPreview = storage_path("app/private/{$name}-preview.jpg");

        $frame->save($pathPreview);

        $image = Image::make($pathPreview);
        $image->save($pathPreview, 60);

        $format = new X264();
        $format->setAudioCodec("libmp3lame");
        $video->save($format, storage_path("app/private/{$name}-full.mp4"));

        copy(storage_path("app/private/{$name}-full.mp4"), storage_path("app/private/{$name}.mp4"));

        Storage::disk('spaces')->put("private/{$name}-full.mp4", Storage::disk('local')->get("private/{$name}-full.mp4"));
        Storage::disk('spaces')->put("private/{$name}-preview.jpg", Storage::disk('local')->get("private/{$name}-preview.jpg"));
        Storage::disk('spaces')->put("private/{$name}.mp4", Storage::disk('local')->get("private/{$name}.mp4"));

        Storage::disk('local')->delete("private/{$name}-full.mp4");
        Storage::disk('local')->delete("private/{$name}-preview.jpg");
        Storage::disk('local')->delete("private/{$name}.mp4");

        return [
            "orientation" => $this->getOrientation($videoDimensions->getWidth(), $videoDimensions->getHeight()),
            "ext" => "mp4",
            "preview" => storage_path("app/private/{$name}-preview.jpg"),
        ];
    }

    private function imageStorage($storagePath, $name): array
    {
        $image = Image::make($storagePath)->orientate();

        $image->save(storage_path("app/private/{$name}-full.{$this->ext}"));

        $image = Image::make($storagePath)->orientate();

        $image->resize(1080, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $image->save(storage_path("app/private/{$name}.{$this->ext}"), 80);

        Storage::disk('spaces')->put("private/{$name}-full.{$this->ext}", Storage::disk('local')->get("private/{$name}-full.{$this->ext}"));
        Storage::disk('spaces')->put("private/{$name}.{$this->ext}", Storage::disk('local')->get("private/{$name}.{$this->ext}"));

        Storage::disk('local')->delete("private/{$name}-full.{$this->ext}");
        Storage::disk('local')->delete("private/{$name}.{$this->ext}");

        return [
            "orientation" => $this->getOrientation($image->width(), $image->height()),
            "ext" => $this->ext,
            "preview" => null,
        ];
    }
}
