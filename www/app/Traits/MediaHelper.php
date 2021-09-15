<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Support\Facades\Log;
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

    public function getPreview(string $name, bool $isBlurred = false)
    {
        if ($isBlurred) {
            return Storage::url("private/{$name}/{$name}-preview-blurred.jpg");
        }

        return Storage::url("private/{$name}/{$name}-preview.jpg");
    }

    public function getImage(string $name, string $ext, bool $isBlurred = false)
    {
        if ($isBlurred) {
            return Storage::url("private/{$name}/{$name}-preview-blurred.{$ext}");
        }

        return Storage::url("private/{$name}/{$name}.{$ext}");
    }

    public function calculateBitrate($width, $height, $fps)
    {
        return ceil((($width * $height * $fps * .1) / 1000) / 100) * 100;
    }

    public function calculateResizeDimensions($width, $height, $widthToResize): array
    {
        if ($width - $widthToResize > 0) {
            $resizeRatio = $width / $widthToResize;

            $height = $height / $resizeRatio;
            $width = $widthToResize;
        }

        return [
            "width" => round($width),
            "height" => round($height),
        ];
    }

    public function formatCanResize($listWidth, $width): array
    {
        $listFormatSize = [];

        foreach ($listWidth as $l) {
            if ($l < $width) {
                $listFormatSize[] = $l;
            }
        }

        return $listFormatSize;
    }

    public function deletePrivateFiles($name)
    {
        $files = Storage::disk(config('filesystems.default'))->files("private/{$name}");

        foreach ($files as $file) {
            if (Storage::exists($file)) {
                Storage::delete($file);

                Log::info("File deleted: ", [
                    "file" => $file
                ]);
            }
        }

        Storage::disk(config('filesystems.default'))->deleteDirectory("private/{$name}");
    }

    public function deleteConversionFiles($name)
    {
        $files = Storage::disk(config('filesystems.default'))->files("conversion/{$name}");

        foreach ($files as $file) {
            if (Storage::exists($file)) {
                Storage::delete($file);

                Log::info("File deleted: ", [
                    "file" => $file
                ]);
            }
        }

        Storage::disk(config('filesystems.default'))->deleteDirectory("conversion/{$name}");
    }

    public function getOrientation(int $width, int $height): string
    {
        return $width > $height ?  Media::LANDSCAPE : Media::PORTRAIT;
    }
}
