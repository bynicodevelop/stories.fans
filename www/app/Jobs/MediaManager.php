<?php

namespace App\Jobs;

use App\Events\PostCreatedEvent;
use App\Events\RefreshPostsEvent;
use App\Exceptions\VideoStorageException;
use App\Models\Media;
use App\Traits\MediaHelper;
use Exception;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MediaHelper;

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

            extract($this->imageStorage($storagePath, $name, $this->isPremium));
        } else if ($this->mediaType == Media::VIDEO) {
            Log::info("Run traitement video", [
                "path" => $path
            ]);

            extract($this->videoStorage($path, $name, $this->isPremium));
        }

        Log::debug("Create new post");

        $post = $this->user->posts()->create([
            "content" => empty($this->content) ? null : $this->content,
            "is_premium" => $this->isPremium,
        ]);

        Log::debug("Post created", [
            "post_id" => $post["id"]
        ]);

        try {
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

            Log::info("Delete temporary file", [
                "path" => $path
            ]);

            if (Storage::disk('local')->exists("tmp/{$this->name}")) {
                Storage::disk('local')->delete("tmp/{$this->name}");

                Log::info("Deleted temporary file", [
                    "path" => $path
                ]);
            }

            event(new RefreshPostsEvent($post, RefreshPostsEvent::CREATED));
        } catch (VideoStorageException $v) {
            Log::error("VideoStorageException", [
                "message" => $v->getMessage()
            ]);
        } catch (Exception $e) {
            Log::error("Exception", [
                "message" => $e->getMessage()
            ]);
        }
    }

    private function getOrientation(int $width, int $height): string
    {
        return $width > $height ?  self::LANDSCAPE : self::PORTRAIT;
    }

    private function blurredImage($image, $name, $ext = "jpg")
    {
        $image->blur(80);
        $image->brightness(15);
        $image->pixelate(30);

        $file = $image->stream()->detach();

        Storage::put("private/{$name}/{$name}-preview-blurred.{$ext}", $file);
    }

    public function generatePreview($name, $media, $videoDuration, $isPremium)
    {
        Log::info("Create preview from video", [
            "path" => "conversion/{$name}/{$name}-preview.jpg"
        ]);

        $media->getFrameFromSeconds($videoDuration * .33)
            ->export()
            ->save("conversion/{$name}/{$name}-preview.jpg");

        $image = Image::make(Storage::get("conversion/{$name}/{$name}-preview.jpg", 80));

        $image->resize(700, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $file = $image->stream()->detach();

        Storage::disk(config('filesystems.default'))->put("private/{$name}/{$name}-preview.jpg", $file, 'public');

        if ($isPremium) {
            Log::info("Create blur preview from video for premium content", [
                "path" => "conversion/{$name}/{$name}-preview.jpg"
            ]);

            $image = Image::make(Storage::get("conversion/{$name}/{$name}-preview.jpg"));

            $this->blurredImage($image, $name);
        }

        if (collect(Storage::disk('local')->directories("conversion"))->filter(function ($path) use ($name) {
            return $path === "conversion/{$name}";
        })->count() > 0) {
            Storage::disk('local')->deleteDirectory("conversion/{$name}");

            Log::info("Aull previews deleted");
        }
    }

    private function getVideoMeta($media)
    {
        /**
         * @var FFMpeg\FFProbe\DataMapping\Stream
         */
        $videoStream = $media->getVideoStream();

        $rateFrame = $videoStream->get('r_frame_rate');

        $rateFrameExploded = explode("/", $rateFrame);

        return [
            "videoDuration" => $media->getDurationInSeconds(),
            "videoDimensions" => $videoStream->getDimensions(),
            "bitRate" => $videoStream->get('bit_rate'),
            "fps" => $rateFrameExploded[0] / $rateFrameExploded[1],
        ];
    }

    private function videoStorage($storagePath, $name, $isPremium = false): array
    {
        $media = FFMpeg::fromDisk('local')
            ->open($storagePath);

        extract($this->getVideoMeta($media));

        $width = $videoDimensions->getWidth();
        $height = $videoDimensions->getHeight();

        Log::info("Generate preview from video", [
            "name" => $name,
            "media" => $media,
            "videoDuration" => $videoDuration,
            "isPremium" => $isPremium
        ]);
        $this->generatePreview($name, $media, $videoDuration, $isPremium);

        Log::info("Create new folder", [
            "path" => "private/{$name}",
        ]);
        Storage::disk(config('filesystems.default'))->makeDirectory("private/{$name}");
        chmod(storage_path("app/private/{$name}"), 0777);

        $dataFormatting = [];

        $dataFormatting[] = $this->calculateBitrate($width, $height, $fps);

        $formatsCanResize = $this->formatCanResize([1920, 1280, 854], $width);
        Log::info("Calculate resize formats", $formatsCanResize);

        foreach ($formatsCanResize as $format) {
            $newSize = $this->calculateResizeDimensions($width, $height, $format);
            $bitrate = $this->calculateBitrate($newSize["width"], $newSize["height"], $fps);

            $dataFormatting[] = $bitrate;
        }
        Log::info("List formats", $dataFormatting);

        try {
            $media = FFMpeg::fromDisk('local')
                ->open($storagePath)
                ->exportForHLS()
                ->withRotatingEncryptionKey(function ($filename, $contents) use ($name) {
                    Storage::disk(config('filesystems.default'))->put("private/{$name}/{$filename}", $contents, 'public');
                });

            foreach ($dataFormatting as $formatting) {
                $media->addFormat((new X264)->setKiloBitrate($formatting));
            }

            $media->toDisk(config('filesystems.default'))
                ->save("private/{$name}/{$name}.m3u8");
        } catch (Exception $e) {
            Log::error("Convertion error", [
                "message" => $e->getMessage()
            ]);

            $this->deleteFiles($name);

            throw new VideoStorageException($e->getMessage());
        }

        return [
            "orientation" => $this->getOrientation($videoDimensions->getWidth(), $videoDimensions->getHeight()),
            "ext" => "m3u8",
            // TODO: useless property (remove in futur version)
            "preview" => "{$name}-preview.jpg",
        ];
    }

    private function imageStorage($storagePath, $name, $isPremium): array
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

        Storage::disk(config('filesystems.default'))->put("private/{$name}/{$name}-full.{$this->ext}", $fullImage, 'public');
        Storage::disk(config('filesystems.default'))->put("private/{$name}/{$name}.{$this->ext}", $stdImage, 'public');

        if ($isPremium) {
            Log::info("Create blur preview from initial image for premium content", [
                "path" => "conversion/{$name}/{$name}-preview.jpg"
            ]);

            $this->blurredImage($image, $name, $this->ext);
        }

        return [
            "orientation" => $this->getOrientation($image->width(), $image->height()),
            "ext" => $this->ext,
            "preview" => null,
        ];
    }
}
