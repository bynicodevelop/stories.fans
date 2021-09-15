<?php

namespace App\Services;

use App\Exceptions\ExtractPreviewAtRequiresException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoService extends AbstractMediaService
{
    private $extractPreviewAt;

    private $widthDimension;

    public function open($path)
    {
        $file = FFMpeg::fromDisk($this->diskFrom)
            ->open($path);

        if (is_null($this->extractPreviewAt)) {
            throw new ExtractPreviewAtRequiresException();
        }

        $stream = $file->getVideoStream();
        $dimensions = $stream->getDimensions();

        $this->widthDimension = $dimensions->getWidth();

        $duration = $file->getDurationInSeconds();

        $this->media = $file->getFrameFromSeconds($duration * $this->extractPreviewAt)
            ->export()
            ->getFrameContents();

        $this->media = Image::make($this->media)->orientate();

        return $this;
    }

    public function extractPreviewAt($value)
    {
        $this->extractPreviewAt = $value;

        return $this;
    }

    public function save($path)
    {
        $this->resizeMedia($this->widthDimension);

        $this->blurMedia();

        $path = str_replace("//", "/", "{$path}/{$this->baseName}/{$this->baseName}{$this->fileName}.{$this->extension}");

        $resource = $this->media->stream()->detach();

        Storage::disk($this->diskTo)->put($path, $resource, $this->visibility);
    }
}
