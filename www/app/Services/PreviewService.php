<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PreviewService
{
    private $diskFrom;

    private $diskTo;

    private $file;

    private $baseName;

    private $fileName = "";

    private $extension = "";

    private $visibility;

    private $width;

    private $height;

    private $image;

    private $isBlurred = false;

    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function fromDisk(string $diskName): self
    {
        $this->diskFrom = $diskName;

        return $this;
    }

    public function toDisk(string $diskName): self
    {
        $this->diskTo = $diskName;

        if (is_null($this->visibility) && config("filesystems.disks.{$this->diskTo}.visibility") == 'public') {
            $this->visibility = config("filesystems.disks.{$this->diskTo}.visibility");
        }

        return $this;
    }

    public function baseName(string $baseName): self
    {
        $this->baseName = $baseName;

        return $this;
    }

    public function fileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function open(string $path): self
    {
        $this->file = $path;

        if (Storage::disk($this->diskFrom)->exists($path)) {
            $this->file = Storage::disk($this->diskFrom)->get($path);
        }

        $this->image = Image::make($this->file)->orientate();

        return $this;
    }

    public function resize($width, $height = null)
    {
        $this->width = $width;

        $this->height = $height;

        return $this;
    }

    public function save(string $path): self
    {
        if (!is_null($this->width)) {
            if ($this->image->getWidth() > $this->width) {
                $this->image->resize(
                    $this->width,
                    $this->height,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                );
            }
        }

        if ($this->isBlurred) {
            $this->image->blur(80);
            $this->image->brightness(15);
            $this->image->pixelate(30);
        }

        $this->file = $this->image->stream()->detach();

        $path = str_replace("//", '/', "$path/{$this->baseName}/{$this->baseName}{$this->fileName}.{$this->extension}");

        Storage::disk($this->diskTo)->put($path, $this->file, $this->visibility);

        return $this;
    }

    public function blurred(): self
    {
        $this->isBlurred = true;

        return $this;
    }

    public function getOrientation(): string
    {
        return $this->image->getWidth() >= $this->image->getHeight() ?  Media::LANDSCAPE : Media::PORTRAIT;
    }
}
