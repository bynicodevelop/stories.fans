<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use App\Services\MediaService;
use App\Traits\MediaHelper;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    use MediaHelper;

    private $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(Request $request, $id)
    {
        $isBlurred = false;

        /**
         * @var User $user
         */
        $user = Auth::user();

        try {
            extract($this->mediaService->blurred($user, $id));

            try {
                $this->authorize('seePost', $post);
            } catch (Exception $e) {
                $isBlurred = true;
            }

            $storagePath = Storage::get("private/{$name}.{$ext}"); // storage_path("app/private/{$name}.{$ext}");

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
                    return redirect($this->getPreview($name, $isBlurred));

                    $previewStoragePath = Storage::get("private/{$name}-preview.jpg");

                    $image = Image::make($previewStoragePath);

                    if ($isBlurred) {
                        $image->pixelate(40);
                    }

                    return $image->response();
                }
            }

            return redirect(Storage::url("private/{$name}.{$ext}"));

            // $fs = Storage::getDriver();

            // $metaData = $fs->getMetadata($storagePath);
            // $stream = $fs->readStream($storagePath);

            // if (ob_get_level()) ob_end_clean();

            // return response()->stream(
            //     function () use ($stream) {
            //         fpassthru($stream);
            //     },
            //     200,
            //     [
            //         'Content-Type' => $metaData['type'],
            //         'Content-disposition' => 'attachment; filename="' . $metaData['path'] . '"',
            //     ]
            // );
            // $stream = Storage::readStream("private/{$name}.{$ext}");

            // return response()->stream(function () use ($stream) {
            //     fpassthru($stream);
            // }, 200, [
            //     "Content-Type" => "video/mp4",
            //     "Cache-Control" => "max-age=2592000, public",
            //     "Expires" => gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT',
            // ]);
        } catch (Exception $e) {
            Log::error("MediaController: ", [
                "message" => $e->getMessage()
            ]);
            abort(404);
        }
    }
}
