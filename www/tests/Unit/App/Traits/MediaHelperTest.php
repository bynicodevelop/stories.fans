<?php

namespace Tests\Unit\App\Traits;

use App\Models\Media;
use App\Traits\MediaHelper;
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

        $this->assertEquals($url, "http://stories.localhost/private/my-image-preview.jpg");
    }

    public function test_getPreview_with_blurred()
    {
        Storage::fake('private');

        $url = $this->getPreview('my-image', true);

        $this->assertEquals($url, "http://stories.localhost/private/my-image-preview-blurred.jpg");
    }

    public function test_getImage_without_blurred_jpg()
    {
        Storage::fake('private');

        $url = $this->getImage("my-image", "jpg", false);

        $this->assertEquals($url, "http://stories.localhost/private/my-image.jpg");
    }

    public function test_getImage_without_blurred_png()
    {
        Storage::fake('private');

        $url = $this->getImage("my-image", "png", false);

        $this->assertEquals($url, "http://stories.localhost/private/my-image.png");
    }

    public function test_getImage_with_blurred()
    {
        Storage::fake('private');

        $url = $this->getImage("my-image", "jpg", true);

        $this->assertEquals($url, "http://stories.localhost/private/my-image-blurred.jpg");
    }
}
