<?php

namespace App\Services;

use App\Models\Media;

class AbstractMediaService
{
    protected $media;

    protected $diskFrom;

    protected $diskTo;

    protected $visibility;

    protected $baseName;

    protected $fileName = "";

    protected $extension = "";

    protected $width;

    protected $height;

    protected $isBlurred = false;

    protected function resizeMedia($width)
    {
        if (!is_null($this->width)) {
            if ($width > $this->width) {
                $this->media->resize(
                    $this->width,
                    $this->height,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                );
            }
        }
    }

    protected function blurMedia()
    {
        if ($this->isBlurred) {
            $this->media->blur(80);
            $this->media->brightness(15);
            $this->media->pixelate(30);
        }
    }

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

    public function resize($width, $height = null)
    {
        $this->width = $width;

        $this->height = $height;

        return $this;
    }

    public function blurred(): self
    {
        $this->isBlurred = true;

        return $this;
    }

    public function getOrientation(): string
    {
        return $this->media->getWidth() >= $this->media->getHeight() ?  Media::LANDSCAPE : Media::PORTRAIT;
    }
}
