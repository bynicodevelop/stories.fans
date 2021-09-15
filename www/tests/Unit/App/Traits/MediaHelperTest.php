<?php

namespace Tests\Unit\App\Traits;

use App\Models\Media;
use App\Traits\MediaHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;
use Tests\TestCase;

class MediaHelperTest extends TestCase
{
    use MediaHelper;

    public function test_getTypeMedia_video()
    {
        $result = $this->getTypeMedia("video/media");

        $this->assertEquals($result, Media::VIDEO);
    }

    public function test_getTypeMedia_image()
    {
        $result = $this->getTypeMedia("image/media");

        $this->assertEquals($result, Media::IMAGE);
    }

    public function test_getMediaProperties_post_type()
    {
        $result = $this->getMediaProperties(null);

        $this->assertEquals($result['typeMedia'], Media::POST);
        $this->assertEquals($result['fileName'], null);
        $this->assertEquals($result['extFile'], null);
    }

    public function test_getMediaProperties_image_type()
    {
        $fileName = 'avatar.jpg';
        $media = TemporaryUploadedFile::fake()->image($fileName);

        $result = $this->getMediaProperties($media);

        $this->assertEquals($result['typeMedia'], Media::IMAGE);
        $this->assertEquals($result['fileName'], $media->getFilename());
        $this->assertEquals($result['extFile'], $media->getClientOriginalExtension());
    }

    public function test_getMediaProperties_video_type()
    {
        $fileName = 'avatar.mov';
        $media = TemporaryUploadedFile::fake()->image($fileName);

        $result = $this->getMediaProperties($media);

        $this->assertEquals($result['typeMedia'], Media::VIDEO);
        $this->assertEquals($result['fileName'], $media->getFilename());
        $this->assertEquals($result['extFile'], $media->getClientOriginalExtension());
    }

    public function test_getPreview_without_blurred()
    {
        Storage::fake('private');

        $url = $this->getPreview('my-image', false);

        $this->assertEquals($url, Storage::url("private/my-image/my-image-preview.jpg"));
    }

    public function test_getPreview_with_blurred()
    {
        Storage::fake('private');

        $url = $this->getPreview('my-image', true);

        $this->assertEquals($url, Storage::url("private/my-image/my-image-preview-blurred.jpg"));
    }

    public function test_getImage_without_blurred_jpg()
    {
        Storage::fake('private');

        $url = $this->getImage("my-image", "jpg", false);

        $this->assertEquals($url, Storage::url("private/my-image/my-image.jpg"));
    }

    public function test_getImage_without_blurred_png()
    {
        Storage::fake('private');

        $url = $this->getImage("my-image", "png", false);

        $this->assertEquals($url, Storage::url("private/my-image/my-image.png"));
    }

    public function test_getImage_with_blurred()
    {
        Storage::fake('private');

        $url = $this->getImage("my-image", "jpg", true);

        $this->assertEquals($url, Storage::url("private/my-image/my-image-preview-blurred.jpg"));
    }

    public function test_calculateBitrate()
    {
        $data = [
            [
                "width" => 1080,
                "height" => 1080,
                "fps" => 30,
                "result" => 3500,
            ],
            [
                "width" => 1280,
                "height" => 720,
                "fps" => 30,
                "result" => 2800,
            ],
            [
                "width" => 1920,
                "height" => 1080,
                "fps" => 30,
                "result" => 6300,
            ],
        ];

        foreach ($data as $d) {
            extract($d);

            $biteRate = $this->calculateBitrate($width, $height, $fps);

            $this->assertEquals($biteRate, $result);
        }
    }

    public function test_calculateResizeDimensions()
    {
        $data = [
            [
                "width" => 1080,
                "height" => 1080,
                "widthToResize" => 720,
                "resultHeigth" => 720,
            ],
            [
                "width" => 1920,
                "height" => 1080,
                "widthToResize" => 1280,
                "resultHeigth" => 720,
            ],
            [
                "width" => 1920,
                "height" => 1080,
                "widthToResize" => 720,
                "resultHeigth" => 405,
            ],
            [
                "width" => 1728,
                "height" => 972,
                "widthToResize" => 1080,
                "resultHeigth" => 608,
            ],
        ];

        foreach ($data as $d) {
            extract($d);

            $dataSize = $this->calculateResizeDimensions($width, $height, $widthToResize);

            $this->assertEquals($dataSize["height"], $resultHeigth);
        }
    }

    public function test_formatCanResize()
    {
        $data = [
            [
                "listWidth" => [
                    1920,
                    1280,
                    1080,
                    720,
                ],
                "width" => 1280,
                "result" => [1080, 720]
            ],
            [
                "listWidth" => [
                    1920,
                    1280,
                    1080,
                    720,
                ],
                "width" => 2300,
                "result" => [1920, 1280, 1080, 720]
            ],
            [
                "listWidth" => [
                    1920,
                    1280,
                    1080,
                    720,
                ],
                "width" => 720,
                "result" => []
            ]
        ];

        foreach ($data as $d) {
            extract($d);

            $listFormatSize = $this->formatCanResize($listWidth, $width);

            $this->assertEquals($listFormatSize, $result);
        }
    }

    public function test_deletePrivateFiles()
    {
        Storage::fake(config('filesystems.default'));

        Storage::disk(config('filesystems.default'))->put('private/avatar1/avatar1.jpg', UploadedFile::fake()->image('avatar1.jpg'));
        Storage::disk(config('filesystems.default'))->put('private/avatar2/avatar2.jpg', UploadedFile::fake()->image('avatar2.jpg'));

        Storage::disk(config('filesystems.default'))->assertExists('private/avatar1/avatar1.jpg');

        $this->deletePrivateFiles("avatar1");

        Storage::disk(config('filesystems.default'))->assertExists('private/avatar2/avatar2.jpg');
        Storage::disk(config('filesystems.default'))->assertMissing('private/avatar1/avatar1.jpg');
    }

    public function test_deleteConversionFile()
    {
        Storage::fake("conversion");

        Storage::disk(config('filesystems.default'))->put('conversion/avatar1/avatar1.jpg', UploadedFile::fake()->image('avatar1.jpg'));
        Storage::disk(config('filesystems.default'))->put('conversion/avatar2/avatar2.jpg', UploadedFile::fake()->image('avatar2.jpg'));

        Storage::disk(config('filesystems.default'))->assertExists('conversion/avatar1/avatar1.jpg');

        $this->deleteConversionFiles("avatar1");

        Storage::disk(config('filesystems.default'))->assertExists('conversion/avatar2/avatar2.jpg');
        Storage::disk(config('filesystems.default'))->assertMissing('conversion/avatar1/avatar1.jpg');
    }
}
