<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use App\Services\MediaService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    private $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(Request $request, $id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        try {
            extract($this->mediaService->blurred($user, $id));

            $storagePath = Storage::disk('spaces')->get("private/{$name}.{$ext}"); // storage_path("app/private/{$name}.{$ext}");

            if ($type == Media::IMAGE) {
                $image = Image::make($storagePath);

                if ($isBlurred) {
                    $image->blur(60);
                }

                return $image->response();
            } else {
                extract($this->mediaService->blurred($user, $id));

                $isPreview = $request->input('preview');

                if (!is_null($isPreview)) {
                    $previewStoragePath = Storage::disk('spaces')->get("private/{$name}-preview.jpg");

                    $image = Image::make($previewStoragePath);

                    if ($isBlurred) {
                        $image->pixelate(40);
                    }

                    return $image->response();
                }
            }

            $stream = Storage::disk('spaces')->readStream("private/{$name}.{$ext}");

            return response()->stream(function () use ($stream) {
                fpassthru($stream);
            }, 200, [
                "Content-Type" => "video/mp4",
                "Cache-Control" => "max-age=2592000, public",
                "Expires" => gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT',
            ]);
        } catch (Exception $e) {
            Log::error("MediaController: ", [
                "message" => $e->getMessage()
            ]);
            abort(404);
        }
    }
}
