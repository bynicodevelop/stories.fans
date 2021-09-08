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

            if ($type == Media::IMAGE) {
                return redirect($this->getImage($name, $ext, $isBlurred));
            } else {
                extract($this->mediaService->blurred($user, $id));

                $isPreview = $request->input('preview');

                if (!is_null($isPreview)) {
                    return redirect($this->getPreview($name, $isBlurred));
                }
            }

            return redirect(Storage::url("private/{$name}/{$name}.{$ext}"));
        } catch (Exception $e) {
            Log::error("MediaController: ", [
                "message" => $e->getMessage()
            ]);
            abort(404);
        }
    }
}
