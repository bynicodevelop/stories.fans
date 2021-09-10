<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class PreviewService
{
    private $diskFrom;

    private $diskTo;

    private $file;

    private $filename;

    private $visibility;

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

    public function fileName(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function open(string $path): self
    {
        $this->file = Storage::disk($this->diskFrom)->get($path);

        return $this;
    }

    public function save(string $path): self
    {
        Storage::disk($this->diskTo)->put("$path/{$this->fileName}/{$this->fileName}-preview.jpg", $this->file, $this->visibility);

        return $this;
    }
}
