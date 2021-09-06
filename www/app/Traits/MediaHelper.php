<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

trait MediaHelper
{
    public function getTypeMedia(string $mimeType): string
    {
        $typeMimeExploded = explode('/', $mimeType);

        return strtolower($typeMimeExploded[0]) == 'video' ? Media::VIDEO : Media::IMAGE;
    }

    public function getMediaProperties($media): array
    {
        $typeMedia = Media::POST;
        $fileName = null;
        $extFile = null;

        if (!empty($media)) {
            $typeMedia = $this->getTypeMedia($media->getMimeType());
            $fileName = $media->getFileName();
            $extFile = $media->getClientOriginalExtension();
        }

        return [
            'typeMedia' => $typeMedia,
            'fileName' => $fileName,
            'extFile' => $extFile,
        ];
    }

    public function getPreview(string $name, bool $isBlurred)
    {
        return Storage::url("private/{$name}-preview.jpg");
    }
}
