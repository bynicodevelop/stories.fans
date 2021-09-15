<?php

namespace Tests\Feature\App\Services;

use App\Models\Media;
use App\Services\PreviewService;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\StreamInterface;
use Tests\TestCase;

class PreviewServiceTest extends TestCase
{
    /**
     * Doit vérifier que le fichier est bien déplacé
     * d'un dossier à un autre avec une visibilité public
     */
    public function test_create_public_preview()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";
        $fileNameEnd = "123456.jpg";

        $file = UploadedFile::fake()->image($fileNameStart);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(10);

        $stream = $mockImage->shouldReceive('stream')->once()->andReturn($this->createMock(StreamInterface::class, []));

        $stream->shouldReceive('detach')->andReturn(fopen('php://memory', 'r+'));

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}")
            ->fromDisk('local')
            ->toDisk('local')
            ->save("private/{$fileNameEnd}");

        Storage::disk('local')->assertExists("private/{$fileNameEnd}");

        $this->assertEquals(Storage::disk('local')->getVisibility("private/{$fileNameEnd}"), 'public');
    }

    /**
     * Doit vérifier que le fichier est bien déplacé
     * d'un dossier à un autre avec une visibilité privé
     */
    public function test_create_private_preview_jpg()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";
        $fileNameEnd = "123456";

        $file = UploadedFile::fake()->image($fileNameStart);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(10);

        $stream = $mockImage->shouldReceive('stream')->once()->andReturn($this->createMock(StreamInterface::class, []));

        $stream->shouldReceive('detach')->andReturn(fopen('php://memory', 'r+'));

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}")
            ->setVisibility('private')
            ->fromDisk('local')
            ->toDisk('local')
            ->setExtension('jpg')
            ->baseName($fileNameEnd)
            ->fileName('-full')
            ->save("private/");

        Storage::disk('local')->assertExists("private/{$fileNameEnd}/{$fileNameEnd}-full.jpg");

        $this->assertEquals(Storage::disk('local')->getVisibility("private/{$fileNameEnd}/{$fileNameEnd}-full.jpg"), 'private');
    }

    public function test_create_private_preview_png()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";
        $fileNameEnd = "123456";

        $file = UploadedFile::fake()->image($fileNameStart);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(10);

        $stream = $mockImage->shouldReceive('stream')->once()->andReturn($this->createMock(StreamInterface::class, []));

        $stream->shouldReceive('detach')->andReturn(fopen('php://memory', 'r+'));

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}")
            ->setVisibility('private')
            ->fromDisk('local')
            ->toDisk('local')
            ->setExtension('png')
            ->baseName($fileNameEnd)
            ->fileName('-full')
            ->save("private/");

        Storage::disk('local')->assertExists("private/{$fileNameEnd}/{$fileNameEnd}-full.png");

        $this->assertEquals(Storage::disk('local')->getVisibility("private/{$fileNameEnd}/{$fileNameEnd}-full.png"), 'private');
    }

    /**
     * Controlle la taille de l'image
     */
    public function test_create_preview_with_resize_1080_1080()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";
        $fileNameEnd = "123456";

        $file = UploadedFile::fake()->image($fileNameStart, 1080, 1080)->size(100);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(1080);

        $mockImage->shouldReceive('resize')->with(720, null, Mockery::type('Closure'))->once();

        $stream = $mockImage->shouldReceive('stream')->once()->andReturn($this->createMock(StreamInterface::class, []));

        $stream->shouldReceive('detach')->andReturn(fopen('php://memory', 'r+'));

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}")
            ->setVisibility('private')
            ->fromDisk('local')
            ->toDisk('local')
            ->baseName($fileNameEnd)
            ->resize(720)
            ->save("private/");
    }

    public function test_create_preview_with_resize_700_700()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";
        $fileNameEnd = "123456";

        $file = UploadedFile::fake()->image($fileNameStart, 700, 700)->size(100);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(700);

        $mockImage->shouldReceive('resize')->never();

        $stream = $mockImage->shouldReceive('stream')->once()->andReturn($this->createMock(StreamInterface::class, []));

        $stream->shouldReceive('detach')->andReturn(fopen('php://memory', 'r+'));

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}")
            ->setVisibility('private')
            ->fromDisk('local')
            ->toDisk('local')
            ->baseName($fileNameEnd)
            ->resize(720)
            ->save("private/");
    }

    public function test_create_preview_getOrientation_LANDSCAPE_from_square()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";

        $file = UploadedFile::fake()->image($fileNameStart, 1080, 1080)->size(100);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(1080);
        $mockImage->shouldReceive('getHeight')->once()->andReturn(1080);

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}");

        $this->assertEquals($previewService->getOrientation(), Media::LANDSCAPE);
    }

    public function test_create_preview_getOrientation_LANDSCAPE()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";

        $file = UploadedFile::fake()->image($fileNameStart, 1920, 1080)->size(100);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(1080);
        $mockImage->shouldReceive('getHeight')->once()->andReturn(1080);

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}");

        $this->assertEquals($previewService->getOrientation(), Media::LANDSCAPE);
    }

    public function test_create_preview_getOrientation_PROTRAIT()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";

        $file = UploadedFile::fake()->image($fileNameStart, 720, 1080)->size(100);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(720);
        $mockImage->shouldReceive('getHeight')->once()->andReturn(1080);

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}");

        $this->assertEquals($previewService->getOrientation(), Media::PORTRAIT);
    }

    public function test_create_preview_blur_image()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";
        $fileNameEnd = "123456";

        $file = UploadedFile::fake()->image($fileNameStart, 720, 1080)->size(100);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();

        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();

        $mockImage->shouldReceive('blur')->with(80)->once()->andReturnSelf();
        $mockImage->shouldReceive('brightness')->with(15)->once()->andReturnSelf();
        $mockImage->shouldReceive('pixelate')->with(30)->once()->andReturnSelf();

        $mockImage->shouldReceive('getWidth')->once()->andReturn(720);

        $stream = $mockImage->shouldReceive('stream')->once()->andReturn($this->createMock(StreamInterface::class, []));

        $stream->shouldReceive('detach')->andReturn(fopen('php://memory', 'r+'));

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}")
            ->setVisibility('private')
            ->fromDisk('local')
            ->toDisk('local')
            ->baseName($fileNameEnd)
            ->blurred()
            ->resize(720)
            ->save("private/");
    }
}
