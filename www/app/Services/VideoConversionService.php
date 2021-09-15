<?php

namespace App\Services;

use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoConversionService extends AbstractMediaService
{
    private $path;

    private $widthDimension;

    private $heightDimension;

    private $fps;

    private $conversionFormats = [];

    public function open($path): self
    {
        $this->path = $path;

        $this->media = FFMpeg::fromDisk($this->diskFrom)
            ->open($this->path);

        $stream = $this->media->getVideoStream();
        $dimensions = $stream->getDimensions();
        $rate = $stream->get('r_frame_rate');

        $explodedRate = explode("/", $rate);
        $this->fps = $explodedRate[0] / $explodedRate[1];

        $this->widthDimension = $dimensions->getWidth();
        $this->heightDimension = $dimensions->getHeight();

        return $this;
    }

    private function calculateBitrate($width, $height, $fps)
    {
        return ceil((($width * $height * $fps * .1) / 1000) / 100) * 100;
    }

    private function calculateResizeDimensions($width, $height, $widthToResize): array
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

    public function convertInFormats(array $formats): self
    {
        $this->conversionFormats[] = $this->calculateBitrate($this->widthDimension, $this->heightDimension, $this->fps);

        foreach ($formats as $l) {
            if ($l < $this->widthDimension) {
                $newSize = $this->calculateResizeDimensions($this->widthDimension, $this->heightDimension, $l);
                $bitrate = $this->calculateBitrate($newSize["width"], $newSize["height"], $this->fps);

                $this->conversionFormats[] = $bitrate;
            }
        }

        return $this;
    }

    public function save($path)
    {
        $path = str_replace("//", "/", "{$path}/{$this->baseName}/{$this->baseName}.{$this->extension}");

        $media = FFMpeg::fromDisk($this->diskFrom)
            ->open($this->path)
            ->exportForHLS()
            ->withRotatingEncryptionKey(function ($filename, $contents) {
                Storage::disk($this->diskTo)->put("private/{$this->baseName}/{$filename}", $contents, 'public');
            });

        foreach ($this->conversionFormats as $formatting) {
            $media = $media->addFormat((new X264)->setKiloBitrate($formatting));
        }

        $media->toDisk($this->diskTo)
            ->save($path);
    }
}
