<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PreviewService extends AbstractMediaService
{
    private $file;

    public function open(string $path): self
    {
        $this->file = $path;

        if (Storage::disk($this->diskFrom)->exists($path)) {
            $this->file = Storage::disk($this->diskFrom)->get($path);
        }

        $this->media = Image::make($this->file)->orientate();

        return $this;
    }

    public function save(string $path): self
    {
        $this->resizeMedia($this->media->getWidth());

        $this->blurMedia();

        $this->file = $this->media->stream()->detach();

        $path = str_replace("//", '/', "$path/{$this->baseName}/{$this->baseName}{$this->fileName}.{$this->extension}");

        Storage::disk($this->diskTo)->put($path, $this->file, $this->visibility);

        return $this;
    }

    public function getOrientation(): string
    {
        return $this->media->getWidth() >= $this->media->getHeight() ?  Media::LANDSCAPE : Media::PORTRAIT;
    }
}
