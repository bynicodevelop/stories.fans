<?php

namespace App\Jobs;

use App\Events\RefreshPostsEvent;
use App\Exceptions\VideoStorageException;
use App\Models\Media;
use App\Services\PreviewService;
use App\Services\VideoConversionService;
use App\Services\VideoService;
use App\Traits\MediaHelper;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MediaManager implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MediaHelper;

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

        if (!Storage::disk('local')->exists("tmp/{$this->name}")) {
            Log::info("Temporary file not exists", [
                "path_name" => "tmp/{$this->name}",
                "class" => MediaManager::class
            ]);
            return;
        }

        Log::info("Start of treatment:", [
            "path" => $path,
            "mediaType" => $this->mediaType,
            "class" => MediaManager::class
        ]);

        if ($this->mediaType == Media::IMAGE) {
            $storagePath = storage_path("app/{$path}");

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

        Log::info("Create new post");

        $post = $this->user->posts()->create([
            "content" => empty($this->content) ? null : $this->content,
            "is_premium" => $this->isPremium,
        ]);

        Log::info("Post created", [
            "post_id" => $post["id"]
        ]);

        try {
            if ($this->mediaType != Media::POST) {
                Log::info("Create new media");

                Log::info("Orientation file: ", [
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

    private function videoStorage($storagePath, $name, $isPremium = false): array
    {
        Log::info("Generate preview from video", [
            "name" => $name,
            "isPremium" => $isPremium,
            "class" => MediaManager::class
        ]);
        $videoServicePreview = new VideoService();

        $videoServicePreview
            ->extractPreviewAt(.33)
            ->fromDisk('local')
            ->open($storagePath)
            ->toDisk(config('filesystems.default'))
            ->setVisibility('public')
            ->baseName($name)
            ->resize(720)
            ->fileName('-preview')
            ->setExtension('jpg')
            ->save("private/");

        if ($isPremium) {
            Log::info("Generate premium preview from video", [
                "name" => $name,
                "isPremium" => $isPremium,
                "class" => MediaManager::class
            ]);
            $videoServicePremium = new VideoService();

            $videoServicePremium
                ->extractPreviewAt(.33)
                ->fromDisk('local')
                ->open($storagePath)
                ->toDisk(config('filesystems.default'))
                ->setVisibility('public')
                ->baseName($name)
                ->resize(720)
                ->fileName("-preview-blurred")
                ->setExtension('jpg')
                ->blurred()
                ->save("private/");
        }

        try {
            $videoConversionService = new VideoConversionService();

            $videoConversionService
                ->fromDisk('local')
                ->open($storagePath)
                ->setVisibility('public')
                ->convertInFormats([1920, 1280, 854])
                ->baseName($name)
                ->setExtension("m3u8")
                ->toDisk(config('filesystems.default'))
                ->save("private/");
        } catch (Exception $e) {
            Log::error("Convertion error", [
                "message" => $e->getMessage()
            ]);

            $this->deletePrivateFiles($name);

            throw new VideoStorageException($e->getMessage());
        }

        $this->deleteConversionFiles($name);

        return [
            "orientation" => $videoServicePreview->getOrientation(),
            "ext" => "m3u8",
            "preview" => null,
        ];
    }

    private function imageStorage($storagePath, $name, $isPremium): array
    {
        $previewServiceFull = new PreviewService();

        $previewServiceFull->open($storagePath)
            ->toDisk(config('filesystems.default'))
            ->setVisibility('public')
            ->baseName($name)
            ->setExtension($this->ext)
            ->fileName("-full")
            ->save("/private");

        $previewServiceResize = new PreviewService();

        $previewServiceResize->open($storagePath)
            ->resize(720)
            ->toDisk(config('filesystems.default'))
            ->setVisibility('public')
            ->baseName($name)
            ->setExtension($this->ext)
            ->save("/private");

        if ($isPremium) {
            Log::info("Create blur preview from initial image for premium content", [
                "path" => "conversion/{$name}/{$name}-preview.jpg"
            ]);

            $previewServiceResizeBlurred = new PreviewService();

            $previewServiceResizeBlurred->open($storagePath)
                ->resize(720)
                ->toDisk(config('filesystems.default'))
                ->setVisibility('public')
                ->baseName($name)
                ->fileName("-preview-blurred")
                ->setExtension($this->ext)
                ->blurred()
                ->save("/private");
        }

        return [
            "orientation" => $previewServiceFull->getOrientation(),
            "ext" => $this->ext,
            "preview" => null,
        ];
    }
}
